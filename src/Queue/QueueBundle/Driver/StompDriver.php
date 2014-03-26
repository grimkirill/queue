<?php
/**
 * Created by PhpStorm.
 * User: lena
 * Date: 11.02.14
 * Time: 21:35
 */

namespace Queue\QueueBundle\Driver;


use Queue\QueueBundle\Model\Config;
use Queue\QueueBundle\Model\Consumer;
use Queue\QueueBundle\Model\ExecutionCondition;
use Stomp\Client;

class StompDriver implements DriverInterface
{
    /**
     * @var Client
     */
    protected $stompClient;

    public function setConfig(array $config)
    {
        $host = $config['host'];
        $params = array(
            'login' => $config['user'],
            'passcode' => $config['password'],
            'host'     => $config['vhost'],
            'queue_prefix' => '/queue/'
        );
        $this->stompClient = new Client($host, $params);
    }


    /**
     * @inheritdoc
     */
    public function send($data, Config $config)
    {
        $headers = $config->getParameters();

        $this->stompClient->send($config->getDestination(), $data, $headers);
    }

    /**
     * @inheritdoc
     */
    public function subscribe(Consumer $consumer, ExecutionCondition $condition)
    {
        $this->stompClient->subscribe($consumer->getConfig()->getDestination());
        while ($condition->isValid()) {
            if ($message = $this->stompClient->readMessage(10)) {
                $condition->incrementMessagesCount();
                $result = $consumer->callback($message->getBody());
                if ($result) {
                    $message->ack();
                } else {
                    $message->nack();
                }
            }
        }
    }

} 