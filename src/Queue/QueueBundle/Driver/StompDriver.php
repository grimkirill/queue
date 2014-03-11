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

    function __construct($params = array())
    {
        $host = $params['host'];
        $config = array(
            'login' => $params['user'],
            'passcode' => $params['password'],
            'host'     => $params['vhost'],
            'queue_prefix' => '/queue/'
        );
        $this->stompClient = new Client($host, $config);
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