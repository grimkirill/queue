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

class DirectDriver extends ArrayDriver implements ContainerAwareInterface
{
    /**
     * @var ContainerInterface
     */
    protected $container;

    /**
     * @inheritdoc
     */
    public function setContainer(ContainerInterface $container = null)
    {
        $this->container = $container;
    }

    public function send($data, Config $config)
    {
        parent::send($data, $config);

        foreach ($this->container->get('grimkirill.queue.holder')->getConsumerList() AS $id) {
            $condition = new ExecutionCondition();
            $condition->setTimeout(1);
            $this->container->get($id)->execute($condition);
        }
    }

} 