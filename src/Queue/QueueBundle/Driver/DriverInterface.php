<?php
/**
 * Created by PhpStorm.
 * User: lena
 * Date: 31.01.14
 * Time: 22:17
 */

namespace Queue\QueueBundle\Driver;


use Queue\QueueBundle\Model\Config;
use Queue\QueueBundle\Model\Consumer;
use Queue\QueueBundle\Model\ExecutionCondition;

interface DriverInterface
{
    public function setConfig(array $config);

    public function send($data, Config $config);

    public function subscribe(Consumer $consumer, ExecutionCondition $condition);
} 