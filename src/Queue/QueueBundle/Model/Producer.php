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

    /**
     * @var Config
     */
    protected $config;

    function __construct($driver, $serializer)
    {
        $this->driver = $driver;
        $this->serializer = $serializer;
    }

    /**
     * @param \Queue\QueueBundle\Model\Config $config
     */
    public function setConfig($config)
    {
        $this->config = $config;
    }

    public function publish($message, Config $config = null)
    {
        $queueConfig = clone $this->config;
        if ($config) {
            $queueConfig->merge($config);
        }
        return $this->driver->send($this->serializer->serialize($message), $queueConfig);
    }

} 