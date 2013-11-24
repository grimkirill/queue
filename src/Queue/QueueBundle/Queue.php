<?php
/**
 * Created by PhpStorm.
 * User: lena
 * Date: 24.11.13
 * Time: 17:01
 */

namespace Queue\QueueBundle;


class Queue
{

    protected $client;

    public function send($destination, $message, $headers = array())
    {

    }

    public function subscribe($destination, $handler, $headers = array())
    {

    }
} 