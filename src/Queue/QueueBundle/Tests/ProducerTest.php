<?php
/**
 * Created by PhpStorm.
 * User: lena
 * Date: 10.02.14
 * Time: 22:00
 */

namespace Queue\QueueBundle\Tests;


use Queue\QueueBundle\Driver\ArrayDriver;
use Queue\QueueBundle\Model\Producer;
use Queue\QueueBundle\Serializer\Json;

class ProducerTest extends \PHPUnit_Framework_TestCase {

    public function testSend()
    {
        $driver = new ArrayDriver();
        $serializer = new Json();
        $producer = new Producer($driver, $serializer);
        $producer->publish(['id' => 123]);

        print_r($driver->getMessageList());
    }
}
 