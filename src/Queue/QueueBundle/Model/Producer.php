<?php
/**
 * Created by PhpStorm.
 * User: lena
 * Date: 27.01.14
 * Time: 0:12
 */

namespace Queue\QueueBundle\Model;

use Queue\QueueBundle\Driver\DriverInterface;
use Queue\QueueBundle\Serializer\SerializerInterface;

class Producer
{
    /**
     * @var SerializerInterface
     */
    protected $serializer;

    /**
     * @var DriverInterface
     */
    protected $driver;

    public function publish($message)
    {
        return $this->driver->send($this->serializer->serialize($message));
    }


} 