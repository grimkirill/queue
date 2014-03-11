<?php
/**
 * Created by PhpStorm.
 * User: lena
 * Date: 16.11.13
 * Time: 21:05
 */

namespace Queue\QueueBundle\Command;

use Queue\QueueBundle\Model\ExecutionCondition;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class HandlerCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('queue:consumer')
            ->setDescription('Run queue handler')
            ->addArgument('handler', InputArgument::REQUIRED, 'Id service container')
            ->addOption('timeout', null, InputOption::VALUE_OPTIONAL, 'Server timeout in seconds', 600)
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $consumer = $this->getContainer()->get('queue.consumer.' . $input->getArgument('handler'));
        $condition = new ExecutionCondition();
        $consumer->execute($condition);
    }
} 