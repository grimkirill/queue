<?php
/**
 * Created by PhpStorm.
 * User: lena
 * Date: 16.02.14
 * Time: 13:18
 */

namespace Queue\QueueBundle\Driver;


use Queue\QueueBundle\Model\Config;
use Stomp\Client;

class StompApolloDriver implements DriverInterface
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
        if ($expire = $config->getExpire()) {
            $date = new \DateTime();
            $date->add($expire);
            $headers['expires'] = $date->format('U000');
        }
        print_r($config->getConfig());

        $this->stompClient->send($config->getDestination(), $data, $headers);
    }

    public function subscribe($data)
    {
        // TODO: Implement subscribe() method.
    }

} 