<?php
/**
 * Created by PhpStorm.
 * User: lena
 * Date: 10.02.14
 * Time: 22:11
 */

namespace Queue\QueueBundle\Driver;


use Queue\QueueBundle\Model\Config;
use Queue\QueueBundle\Model\Consumer;
use Queue\QueueBundle\Model\ExecutionCondition;

class ArrayDriver implements DriverInterface
{
    protected $messageList = array();

    public function send($data, Config $config)
    {
        $this->messageList[$config->getDestination()][] = $data;
    }

    public function subscribe(Consumer $consumer, ExecutionCondition $condition)
    {
        // TODO: Implement subscribe() method.
    }

    public function getMessageList()
    {
        return $this->messageList;
    }

} 