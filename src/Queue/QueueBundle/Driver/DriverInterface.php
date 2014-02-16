<?php
/**
 * Created by PhpStorm.
 * User: lena
 * Date: 31.01.14
 * Time: 22:17
 */

namespace Queue\QueueBundle\Driver;


use Queue\QueueBundle\Model\Config;

interface DriverInterface
{

    public function send($data, Config $config);

    public function subscribe($data);
} 