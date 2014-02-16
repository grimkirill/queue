<?php
/**
 * Created by PhpStorm.
 * User: lena
 * Date: 10.02.14
 * Time: 22:11
 */

namespace Queue\QueueBundle\Driver;


use Queue\QueueBundle\Model\Config;

class ArrayDriver implements DriverInterface
{
    protected $messageList = array();

    public function send($data, Config $config)
    {
        $this->messageList[$config->getDestination()][] = $data;
    }

    public function subscribe($data)
    {
        // TODO: Implement subscribe() method.
    }

    public function getMessageList()
    {
        return $this->messageList;
    }

} 