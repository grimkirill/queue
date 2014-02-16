<?php
/**
 * Created by PhpStorm.
 * User: lena
 * Date: 31.01.14
 * Time: 22:47
 */

namespace Queue\QueueBundle\Model;


class Config
{
    const DESTINATION = 'destination';
    const PARAMETERS  = 'parameters';
    const EXPIRE      = 'expire';

    protected $config = array();

    /**
     * @param $key
     * @param null $default
     * @return mixed
     */
    protected function get($key, $default = null)
    {
        if (array_key_exists($key, $this->config)) {
            return $this->config[$key];
        } else {
            return $default;
        }
    }

    public function setDestination($value)
    {
        $this->config[self::DESTINATION] = $value;
    }

    public function getDestination()
    {
        return $this->get(self::DESTINATION);
    }

    public function setExpire($expire)
    {
        $this->config[self::EXPIRE] = $expire;
    }

    /**
     * @return \DateInterval|null
     */
    public function getExpire()
    {
        if ($expire = $this->get(self::EXPIRE)) {
            if ($expire instanceof \DateInterval) {
                return $expire;
            } elseif (is_int($expire) || ctype_digit($expire)) {
                return new \DateInterval('PT' . $expire . 'S');
            } else {
                return new \DateInterval($expire);
            }
        }
        return null;
    }

    /**
     * @return array
     */
    public function getParameters()
    {
        return $this->get(self::PARAMETERS, array());
    }

    /**
     * @param array $params
     */
    public function setParameters($params = array())
    {
        $this->config[self::PARAMETERS] = $params;
    }

    /**
     * @param array $params
     */
    public function addParameters($params = array())
    {
        $this->config[self::PARAMETERS] = array_merge($this->getParameters(), $params);
    }

    /**
     * Получить конфигурацию
     *
     * @return array
     */
    public function getConfig()
    {
        return $this->config;
    }

    public function merge(Config $config)
    {
        $this->config = array_merge($this->config, $config->getConfig());
    }

} 