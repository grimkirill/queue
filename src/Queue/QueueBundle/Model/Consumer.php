<?php
/**
 * Created by PhpStorm.
 * User: kirill
 * Date: 11.03.14 Time: 10:59
 * @author Kirill Skatov
 */

namespace Queue\QueueBundle\Model;

use Queue\QueueBundle\Driver\DriverInterface;
use Queue\QueueBundle\Serializer\SerializerInterface;


class Consumer
{

    /**
     * @var SerializerInterface
     */
    protected $serializer;

    /**
     * @var DriverInterface
     */
    protected $driver;

    protected $callback;

    /**
     * @var Config
     */
    protected $config;

    /**
     * @param mixed $callback
     */
    public function setCallback($callback)
    {
        $this->callback = $callback;
    }

    /**
     * @param \Queue\QueueBundle\Serializer\SerializerInterface $serializer
     */
    public function setSerializer($serializer)
    {
        $this->serializer = $serializer;
    }

    /**
     * @param \Queue\QueueBundle\Driver\DriverInterface $driver
     */
    public function setDriver($driver)
    {
        $this->driver = $driver;
    }

    /**
     * @param \Queue\QueueBundle\Model\Config $config
     */
    public function setConfig($config)
    {
        $this->config = $config;
    }

    /**
     * @return \Queue\QueueBundle\Model\Config
     */
    public function getConfig()
    {
        return $this->config;
    }


    /**
     * Выполнить вызываемый метод
     *
     * @param $message
     * @return mixed
     */
    public function callback($message)
    {
        $data = $this->serializer->unSerialize($message);
        $result = call_user_func($this->callback, $data);
        return $result;
    }

    public function execute()
    {
        $this->driver->subscribe($this);
    }

    function __invoke($message)
    {
        return $this->callback($message);
    }


} 