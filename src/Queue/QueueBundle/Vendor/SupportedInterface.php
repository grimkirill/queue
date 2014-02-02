<?php
/**
 * Created by PhpStorm.
 * User: lena
 * Date: 20.01.14
 * Time: 22:41
 */

namespace Queue\QueueBundle\Vendor;


interface SupportedInterface
{
    /**
     * @param \DateInterval $interval
     * @return array
     */
    public function getExpire(\DateInterval $interval);
} 