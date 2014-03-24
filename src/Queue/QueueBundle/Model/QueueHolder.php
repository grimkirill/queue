<?php
/**
 * Created by PhpStorm.
 * User: kirill
 * Date: 21.03.14 Time: 16:32
 * @author Kirill Skatov
 */

namespace Queue\QueueBundle\Model;


class QueueHolder 
{
    protected $consumerList = [];
    protected $consumerCount = [];

    public function addConsumer($id)
    {
        $this->consumerList[] = $id;
    }

    public function getConsumerList()
    {
        return $this->consumerList;
    }

    public function addConsumerCount($id, $count = 0)
    {
        $this->consumerCount[$id] = intval($count);
    }

    public function getConsumerCount($id)
    {
        if (isset($this->consumerCount[$id])) {
            return $this->consumerCount[$id];
        } else {
            return 0;
        }
    }
} 