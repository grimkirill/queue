<?php
/**
 * Created by PhpStorm.
 * User: lena
 * Date: 31.01.14
 * Time: 21:57
 */

namespace Queue\QueueBundle\Serializer;

/**
 * Serialize via internal serialize
 *
 * @package Queue\QueueBundle\Model\Serializer
 * @author Kirill Skatov kirill@noadmin.ru
 */
class Serialize implements SerializerInterface
{
    /**
     * @inheritdoc
     */
    public function serialize($data)
    {
        return serialize($data);
    }

    /**
     * @inheritdoc
     */
    public function unSerialize($data)
    {
        return unserialize($data);
    }

} 