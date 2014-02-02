<?php
/**
 * Created by PhpStorm.
 * User: lena
 * Date: 31.01.14
 * Time: 22:08
 */

namespace Queue\QueueBundle\Model;


abstract class DriverAbstract
{
    protected $client;

    protected $serializer;

    public function send($data)
    {

    }

} 