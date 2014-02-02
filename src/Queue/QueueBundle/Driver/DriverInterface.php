<?php
/**
 * Created by PhpStorm.
 * User: lena
 * Date: 31.01.14
 * Time: 22:17
 */

namespace Queue\QueueBundle\Driver;


interface DriverInterface
{
    public function send($data);

    public function subscribe($data);
} 