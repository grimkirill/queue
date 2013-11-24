<?php

namespace Queue\QueueBundle;

/**
 * Interface QueueJobHandleInterface is the interface you need to implement
 * handle of message or task
 *
 * @package Queue\QueueBundle
 * @author Kirill Skatov
 */
interface QueueJobHandleInterface
{
    /**
     * Handle message from queue system
     * @param string $message
     * @param array $headers
     * @return bool
     */
    public function queueJobHandle($message, $headers = array());
} 