<?php
/**
 * Created by PhpStorm.
 * User: lena
 * Date: 31.01.14
 * Time: 21:53
 */

namespace Queue\QueueBundle\Serializer;

/**
 * No serialize needed
 *
 * @package Queue\QueueBundle\Model\Serializer
 * @author Kirill Skatov kirill@noadmin.ru
 */
class String implements SerializerInterface
{
    /**
     * @inheritdoc
     */
    public function serialize($data)
    {
        return strval($data);
    }

    /**
     * @inheritdoc
     */
    public function unSerialize($data)
    {
        return $data;
    }

} 