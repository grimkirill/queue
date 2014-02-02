<?php
/**
 * Created by PhpStorm.
 * User: lena
 * Date: 20.01.14
 * Time: 23:01
 */

namespace Queue\QueueBundle\Vendor;


class ActiveMQ implements SupportedInterface
{
    /**
     * @param \DateInterval $interval
     * @return array
     */
    public function getExpire(\DateInterval $interval)
    {
        $date = new \DateTime();
        $date->add($interval);
        return array('expires' => $date->format('U000'));
    }

} 