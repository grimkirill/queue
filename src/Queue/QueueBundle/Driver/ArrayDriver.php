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
    public function setConfig(array $config)
    {

    }

    protected $messageList = array();

    public function send($data, Config $config)
    {
        $this->messageList[$config->getDestination()][] = [
            'message' => $data,
            'config'  => $config->getConfig(),
        ];
    }

    public function subscribe(Consumer $consumer, ExecutionCondition $condition)
    {
        if (array_key_exists($consumer->getConfig()->getDestination(), $this->messageList)) {
            $msgList = $this->messageList[$consumer->getConfig()->getDestination()];
            foreach ($msgList AS $key => $msg) {
                if ($condition->isValid()) {
                    $condition->incrementMessagesCount();
                    $consumer->callback($msg['message']);
                    unset($this->messageList[$consumer->getConfig()->getDestination()][$key]);
                }
            }
        }
    }

    public function getMessageList()
    {
        return $this->messageList;
    }

} 