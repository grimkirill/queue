<?php
/**
 * Created by PhpStorm.
 * User: lena
 * Date: 16.02.14
 * Time: 13:18
 */

namespace Queue\QueueBundle\Driver;


use Queue\QueueBundle\Model\Config;
use Queue\QueueBundle\Model\Consumer;
use Stomp\Client;

class StompApolloDriver extends StompDriver
{

    public function send($data, Config $config)
    {
        $headers = $config->getParameters();
        if ($expire = $config->getExpire()) {
            $date = new \DateTime();
            $date->add($expire);
            $headers['expires'] = $date->format('U000');
        }
        $this->stompClient->send($config->getDestination(), $data, $headers);
    }

    public function subscribe(Consumer $consumer)
    {
        // TODO: Implement subscribe() method.
    }

} 