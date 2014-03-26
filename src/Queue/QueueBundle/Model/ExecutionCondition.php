<?php
/**
 * Created by PhpStorm.
 * User: kirill
 * Date: 11.03.14 Time: 13:43
 * @author Kirill Skatov
 */

namespace Queue\QueueBundle\Model;

/**
 * Class ExecutionCondition
 *
 * @package Queue\QueueBundle\Model
 * @author Kirill Skatov kirill@noadmin.ru
 */
class ExecutionCondition
{
    protected $startTime;

    protected $timeout;

    protected $stop = false;

    protected $processedMessagesCount = 0;

    protected $processedMessagesLimit = 0;

    protected $callbackList = [];

    function __construct()
    {
        $this->startTime = time();
    }

    /**
     * Остановить
     */
    public function stop()
    {
        $this->stop = true;
    }

    /**
     * Включить обработку сигналов
     *
     * @return bool
     */
    public function enableSignal()
    {
        if (function_exists('pcntl_signal')) {
            declare(ticks=1);

            pcntl_signal(SIGTERM, array($this, 'stop'));
            pcntl_signal(SIGINT, array($this, 'stop'));
            return true;
        }
        return false;
    }

    /**
     * Установить время выполенения
     *
     * @param mixed $timeout
     */
    public function setTimeout($timeout)
    {
        $this->timeout = $timeout;
    }

    /**
     * Условия позволяют продолжать обработку сообщений
     *
     * @return bool
     */
    public function isValid()
    {
        if ($this->stop) {
            return false;
        }

        if ($this->timeout) {
            if (($this->timeout + $this->startTime) < time()) {
                return false;
            }
        }

        if ($this->processedMessagesLimit) {
            if ($this->processedMessagesCount >= $this->processedMessagesLimit) {
                return false;
            }
        }

        if (!$this->isValidCallback()) {
            return false;
        }

        return true;
    }

    /**
     * Увеличить количество обработанных сообщений
     *
     */
    public function incrementMessagesCount()
    {
        $this->processedMessagesCount ++;
    }

    public function getMessagesCount()
    {
        return $this->processedMessagesCount;
    }

    /**
     * @param int $processedMessagesLimit
     */
    public function setProcessedMessagesLimit($processedMessagesLimit)
    {
        $this->processedMessagesLimit = $processedMessagesLimit;
    }

    /**
     * Добавить условие выполнения
     *
     * @param callable $callback
     */
    public function addCallback(callable $callback)
    {
        $this->callbackList[] = $callback;
    }

    public function isValidCallback()
    {
        foreach ($this->callbackList AS $callback) {
            if (!$callback()) {
                return false;
            }
        }
        return true;
    }

} 