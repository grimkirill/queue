<?php
/**
 * Created by PhpStorm.
 * User: lena
 * Date: 27.01.14
 * Time: 0:18
 */

namespace Queue\QueueBundle\Serializer;

/**
 * Interface SerializerInterface
 *
 * @package Queue\QueueBundle\Model\Serializer
 * @author Kirill Skatov kirill@noadmin.ru
 */
interface SerializerInterface
{
    /**
     * Сериализация данных в строку для помещения в очередь
     *
     * @param $data
     * @return string
     */
    public function serialize($data);

    /**
     * Десериализация данных из строки в представление
     *
     * @param $data
     * @return mixed
     */
    public function unSerialize($data);
} 