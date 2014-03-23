<?php
/**
 * Created by PhpStorm.
 * User: kirill
 * Date: 21.03.14 Time: 16:13
 * @author Kirill Skatov
 */

namespace Queue\QueueBundle\Driver;


use Queue\QueueBundle\Model\Config;
use Queue\QueueBundle\Model\Consumer;
use Queue\QueueBundle\Model\ExecutionCondition;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

class DirectDriver implements DriverInterface, ContainerAwareInterface
{
    /**
     * @var ContainerInterface
     */
    protected $container;

    protected $messageList = array();

    /**
     * @inheritdoc
     */
    public function setContainer(ContainerInterface $container = null)
    {
        $this->container = $container;
    }

    public function send($data, Config $config)
    {
        $this->messageList[$config->getDestination()][] = [
            'message' => $data,
            'config'  => $config->getConfig(),
        ];
        foreach ($this->container->get('grimkirill.queue.holder')->getConsumerList() AS $id) {
            $condition = new ExecutionCondition();
            $condition->setTimeout(1);
            $this->container->get($id)->execute($condition);

        }

    }

    public function subscribe(Consumer $consumer, ExecutionCondition $condition)
    {
        if (array_key_exists($consumer->getConfig()->getDestination(), $this->messageList)) {
            $msgList = $this->messageList[$consumer->getConfig()->getDestination()];
            foreach ($msgList AS $msg) {
                $consumer->callback($msg['message']);
            }
        }
    }

    public function getMessageList()
    {
        return $this->messageList;
    }

} 