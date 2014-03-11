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

    public function send($data, Config $config)
    {
        $headers = $config->getParameters();

        $this->stompClient->send($config->getDestination(), $data, $headers);
    }


    public function subscribe(Consumer $consumer)
    {
        // TODO: Implement subscribe() method.
    }

} 