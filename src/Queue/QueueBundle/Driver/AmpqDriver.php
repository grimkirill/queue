<?php
/**
 * Created by PhpStorm.
 * User: kirill
 * Date: 11.03.14 Time: 10:07
 * @author Kirill Skatov
 */

namespace Queue\QueueBundle\Driver;


use PhpAmqpLib\Connection\AMQPConnection;
use PhpAmqpLib\Exception\AMQPTimeoutException;
use PhpAmqpLib\Message\AMQPMessage;
use Queue\QueueBundle\Model\Config;
use Queue\QueueBundle\Model\Consumer;
use Queue\QueueBundle\Model\ExecutionCondition;

class AmpqDriver implements DriverInterface
{

    /**
     * @var AMQPConnection
     */
    protected $client;

    /**
     * @var Consumer
     */
    protected $consumer;

    /**
     * @var ExecutionCondition
     */
    protected $condition;

    public function setConfig(array $config)
    {
        if (!($port = $config['port'])) {
            $port = 5672;
        }
        $this->client = new AMQPConnection($config['host'], $port, $config['user'], $config['password'], $config['vhost']);
    }


    public function send($data, Config $config)
    {

        $channel = $this->client->channel();
        $channel->queue_declare($config->getDestination(), false, true, false, false);
        $msg = new AMQPMessage($data, $config->getParameters());
        $channel->basic_publish($msg, '', $config->getDestination());

    }

    public function callback(AMQPMessage $message)
    {
        $this->condition->incrementMessagesCount();
        $result = $this->consumer->callback($message->body);
        if ($result) {
            $message->delivery_info['channel']->basic_ack($message->delivery_info['delivery_tag']);
        } else {
            $message->delivery_info['channel']->basic_nack($message->delivery_info['delivery_tag'], false, true);
        }
        return $result;
    }

    public function subscribe(Consumer $consumer, ExecutionCondition $condition)
    {
        $this->consumer = $consumer;
        $this->condition = $condition;
        $channel = $this->client->channel();
        $channel->basic_consume($consumer->getConfig()->getDestination(), '', false, false, false, false, [$this, 'callback']);
        while ($condition->isValid()) {
            try {
                $channel->wait(null, null, 10);
            } catch (AMQPTimeoutException $e) {

            }
        }
    }

} 