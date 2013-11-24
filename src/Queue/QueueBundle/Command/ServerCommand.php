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

class ServerCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('queue:server')
            ->setDescription('Run queue server')
            ->addOption('timeout', null, InputOption::VALUE_OPTIONAL, 'Server timeout in seconds', 600)
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        //$output->writeln($input->getOption('env'));
        $php_bin = new PhpExecutableFinder();

        $command = new Process($php_bin->find(). ' ' . $_SERVER['SCRIPT_FILENAME'] . ' queue:handler -h');
        //$command = new Process($_SERVER['SCRIPT_FILENAME'] . ' queue:handler -h');

        $command->run();
        var_dump($command->getCommandLine());
        var_dump($command->getOutput());
        var_dump($command->getErrorOutput());

    }
} 