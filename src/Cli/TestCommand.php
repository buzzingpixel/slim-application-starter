<?php

declare(strict_types=1);

namespace App\Cli;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class TestCommand extends Command
{
    /** @var string */
    protected static $defaultName = 'app:test-command';

    protected function configure() : void
    {
        $this->setDescription('Test command');

        $this->setHelp('The test command help description');
    }

    protected function execute(InputInterface $input, OutputInterface $output) : int
    {
        $output->writeln('<fg=green>Test Command Output</>');

        // Command should return 1 if things didn't go fine
        return 0;
    }
}
