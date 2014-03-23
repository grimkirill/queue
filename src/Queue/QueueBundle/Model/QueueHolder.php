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

    public function addConsumer($id)
    {
        $this->consumerList[] = $id;
    }

    public function getConsumerList()
    {
        return $this->consumerList;
    }
} 