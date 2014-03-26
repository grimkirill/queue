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

class ConsumerCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('queue:consumer')
            ->setDescription('Run queue handler')
            ->addArgument('handler', InputArgument::REQUIRED, 'Id service container')
            ->addOption('timeout', null, InputOption::VALUE_OPTIONAL, 'Server timeout in seconds')
            ->addOption('signal', null, InputOption::VALUE_NONE, 'Handle signal')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        if ($this->getContainer()->has($input->getArgument('handler'))) {
            $consumer = $this->getContainer()->get($input->getArgument('handler'));
        } else {
            $consumer = $this->getContainer()->get('queue.consumer.' . $input->getArgument('handler'));
        }
        $condition = new ExecutionCondition();
        $condition->setTimeout($input->getOption('timeout'));
        if ($input->getOption('signal')) {
            $condition->enableSignal();
        }
        $startTime = microtime(true);
        $consumer->execute($condition);

        if ($output->getVerbosity() > $output::VERBOSITY_VERBOSE) {
            $output->writeln('<info>Processed message count:</info> <comment>' . $condition->getMessagesCount() . '</comment>');
            $output->writeln('<info>Processed message time:</info> <comment>' . round($consumer->getTotalWorkTime(), 3) . ' sec</comment>');
            $output->writeln('<info>Total time:</info> <comment>' . round(microtime(true) - $startTime, 3) . ' sec</comment>');
        }
    }
} 