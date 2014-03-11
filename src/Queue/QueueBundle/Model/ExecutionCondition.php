<?php
/**
 * Created by PhpStorm.
 * User: kirill
 * Date: 11.03.14 Time: 13:43
 * @author Kirill Skatov
 */

namespace Queue\QueueBundle\Model;


class ExecutionCondition 
{
    protected $startTime;

    protected $timeout;

    function __construct()
    {
        $this->startTime = time();
    }

    /**
     * @param mixed $timeout
     */
    public function setTimeout($timeout)
    {
        $this->timeout = $timeout;
    }


    public function isValid()
    {
        if ($this->timeout) {
            if (($this->timeout + $this->startTime) < time()) {
                return false;
            }
        }

        return true;
    }

} 