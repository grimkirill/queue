<?php
/**
 * Created by PhpStorm.
 * User: lena
 * Date: 27.01.14
 * Time: 0:28
 */

namespace Queue\QueueBundle\Serializer;

/**
 * Serialize via json
 *
 * @package Queue\QueueBundle\Model\Serializer
 * @author Kirill Skatov kirill@noadmin.ru
 */
class Json implements SerializerInterface
{
    /**
     * @inheritdoc
     */
    public function serialize($data)
    {
        return json_encode($data);
    }

    /**
     * @inheritdoc
     */
    public function unSerialize($data)
    {
        return json_decode($data, true);
    }

} 