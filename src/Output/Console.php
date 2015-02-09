<?php

namespace Buster\Output;

use Symfony\Component\Console\Output\ConsoleOutput;

class Console implements OutputInterface
{
    /**
     * @var ConsoleOutput
     */
    private $output;

    public function __construct()
    {
        $this->output = new ConsoleOutput;
    }

    /**
     * @param string $message
     * @return void
     */
    public function error($message)
    {
        $this->output->writeln("<fg=red>$message</fg=red>");
    }

    /**
     * @param string $message
     * @return void
     */
    public function info($message)
    {
        $this->output->writeln("<fg=blue>$message</fg=blue>");
    }

    /**
     * @param string $message
     * @return void
     */
    public function success($message)
    {
        $this->output->writeln("<fg=green>$message</fg=green>");
    }

    /**
     * @param string $message
     * @return void
     */
    public function write($message)
    {
        $this->output->write($message);
    }
}
