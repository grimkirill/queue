<?php
/**
 * Created by PhpStorm.
 * User: lena
 * Date: 16.11.13
 * Time: 20:44
 */

namespace Queue\QueueBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Process\PhpExecutableFinder;
use Symfony\Component\Process\PhpProcess;
use Symfony\Component\Process\Process;

/**
 * Class ServerCommand
 * @package Queue\QueueBundle\Command
 * @author Kirill Skatov kirill@noadmin.ru
 */
class ServerCommand extends ContainerAwareCommand {
    protected $running = true;

    protected function configure()
    {
        $this
            ->setName('queue:server')
            ->setDescription('Run queue server')
            ->addOption('timeout', null, InputOption::VALUE_OPTIONAL, 'Server timeout in seconds', 600)
        ;
    }

    /**
     * Called by pcntl_signal
     */
    public function signalStop()
    {
        $this->running = false;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        if (function_exists('pcntl_signal')) {
            declare(ticks=1);
            pcntl_signal(SIGTERM, array($this, 'signalStop'));
            pcntl_signal(SIGINT, array($this, 'signalStop'));
        }

        $php_bin = new PhpExecutableFinder();
        $cmdConsole = $php_bin->find(). ' ' . $_SERVER['SCRIPT_FILENAME'] . ' queue:consumer ';
        $cmdOptions = ' --env=' . $input->getOption('env')
            . ' --signal'
            . ' --timeout=' . $input->getOption('timeout');
        $queueHolder = $this->getContainer()->get('grimkirill.queue.holder');

        /**
         * @var $commandList Process[]
         */
        $commandList = [];

        foreach ($queueHolder->getConsumerList() AS $consumerId) {
            if ($count = $queueHolder->getConsumerCount($consumerId)) {
                if ($output->getVerbosity() > $output::VERBOSITY_VERBOSE) {
                    $output->writeln('Run consumer <info>' . $consumerId . '</info> <comment>' . $count . '</comment>');
                }

                for ($i = 0; $i < $count; $i++) {
                    $commandList[] = new Process($cmdConsole . $consumerId . $cmdOptions);
                }
            }
        }

        if ($commandList) {
            do {
                foreach ($commandList AS $key => $command) {
                    if (!$command->isRunning()) {
                        /*if ($command->getExitCode()) {
                            $output->writeln('<error>' . $command->getCommandLine() . '</error>');
                            $output->writeln('<error>' . $command->getErrorOutput() . '</error>');
                            unset($commandList[$key]);
                        } else {
                            $command->start();
                        }
                        */
                    }
                }
                sleep(1);
            } while ($this->running);
        }
        foreach ($commandList AS $command) {
            $command->stop();
        }

    }
} 