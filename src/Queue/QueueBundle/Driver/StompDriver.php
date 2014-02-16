<?php
/**
 * Created by PhpStorm.
 * User: lena
 * Date: 11.02.14
 * Time: 21:35
 */

namespace Queue\QueueBundle\Driver;


use Queue\QueueBundle\Model\Config;
use Stomp\Client;

class StompDriver implements DriverInterface
{
    /**
     * @var Client
     */
    protected $stompClient;


    /**
     * @param \Stomp\Client $stompClient
     */
    public function setStompClient($stompClient)
    {
        $this->stompClient = $stompClient;
    }

    public function send($data, Config $config)
    {
        $headers = $config->getParameters();

        $this->stompClient->send($config->getDestination(), $data, $headers);
    }


    public function subscribe($data)
    {
        // TODO: Implement subscribe() method.
    }

} 