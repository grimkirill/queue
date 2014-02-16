<?php
/**
 * Created by PhpStorm.
 * User: lena
 * Date: 24.11.13
 * Time: 16:28
 */

namespace Queue\QueueBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class TestCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('queue:test')
            ->setDescription('Run queue work test')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $client = $this->getContainer()->get('queue.producer.test');

        $client->publish('test real');
    }
} 