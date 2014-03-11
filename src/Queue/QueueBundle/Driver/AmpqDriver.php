<?php
/**
 * Created by PhpStorm.
 * User: kirill
 * Date: 11.03.14 Time: 10:07
 * @author Kirill Skatov
 */

namespace Queue\QueueBundle\Driver;


use PhpAmqpLib\Connection\AMQPConnection;
use PhpAmqpLib\Message\AMQPMessage;
use Queue\QueueBundle\Model\Config;
use Queue\QueueBundle\Model\Consumer;

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

    function __construct($params = array())
    {
        if (!($port = $params['port'])) {
            $port = 5672;
        }
        $this->client = new AMQPConnection($params['host'], $port, $params['user'], $params['password'], $params['vhost']);
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
        $result = $this->consumer->callback($message->body);
        if ($result) {
            $message->delivery_info['channel']->basic_ack($message->delivery_info['delivery_tag']);
        } else {
            $message->delivery_info['channel']->basic_nack($message->delivery_info['delivery_tag'], false, true);
        }
        return $result;
    }

    public function subscribe(Consumer $consumer)
    {
        $startTime = time();
        $this->consumer = $consumer;
        $channel = $this->client->channel();
        $channel->basic_consume($consumer->getConfig()->getDestination(), '', false, false, false, false, [$this, 'callback']);
        while (($startTime + 10) > time()) {
            $channel->wait(null, null, 10);
        }
    }

} 