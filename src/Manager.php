<?php

namespace Buster;

use Buster\Git\Hook\AbstractFileCollector;
use Buster\Output\Console;

class Manager
{
    /**
     * @var AbstractFileCollector
     */
    private $collector;

    /**
     * @var Console
     */
    private $output;

    /**
     * @var Executor\AbstractExecutor[]
     */
    private $executors;

    /**
     * @param AbstractFileCollector $collector
     */
    public function __construct(AbstractFileCollector $collector)
    {
        $this->collector = $collector;
        $this->output = new Console;
    }

    /**
     * @param Executor\AbstractExecutor $executor
     * @return void
     */
    public function addExecutor(Executor\AbstractExecutor $executor)
    {
        $executor->acceptManager($this);
    }

    /**
     * @param Executor\AbstractExecutor $executor
     * @return void
     */
    public function visitExecutor(Executor\AbstractExecutor $executor)
    {
        $executor->setOutput($this->output);
        $executor->setGitHookFileCollector($this->collector);
        $this->executors[] = $executor;
    }

    /**
     * @return AbstractFileCollector
     */
    public function getGitHookFileCollector()
    {
        return $this->collector;
    }

    /**
     * @param $message
     * @return void
     */
    private function head($message)
    {
        if ($this->output) {
            $this->output->info('');
            $this->output->info(str_repeat('-', strlen($message) + 4));
            $this->output->info("  $message");
            $this->output->info(str_repeat('-', strlen($message) + 4));
        }
    }

    /**
     * @param $message
     * @return void
     */
    private function error($message)
    {
        if ($this->output) {
            $this->output->error('');
            $this->output->error(str_repeat('-', strlen($message) + 4));
            $this->output->error("  $message");
            $this->output->error(str_repeat('-', strlen($message) + 4));
        }
    }

    /**
     * @return int
     */
    public function execute()
    {
        $this->output->info('Starting the Buster');

        foreach ($this->executors as $executor) {
            $executorName = $executor->getName();
            $this->head("Executing $executorName");
            $exitCode = $executor->execute();

            if ($exitCode > 0) {
                $this->error("Executor $executorName failed. Please fix the errors.");

                return $exitCode;
            }

            if ($exitCode == 0) {
                $this->output->success('');
                $this->output->success("$executorName executed successfully");
            }
        }

        $this->output->info('');
        $this->output->info('Good Job!');

        return 0;
    }
}