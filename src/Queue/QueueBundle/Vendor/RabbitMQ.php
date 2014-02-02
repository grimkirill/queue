<?php
/**
 * Created by PhpStorm.
 * User: lena
 * Date: 20.01.14
 * Time: 22:59
 */

namespace Queue\QueueBundle\Vendor;


class RabbitMQ implements SupportedInterface
{
    /**
     * @param \DateInterval $interval
     * @return array
     */
    public function getExpire(\DateInterval $interval)
    {
        $ttl = 1000 * ($interval->s + $interval->i * 60 + $interval->h * 3600 + $interval->d * 24 * 3600);
        return array(
            'x-message-ttl' => $ttl
        );
    }

} 