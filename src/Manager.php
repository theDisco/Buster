<?php

namespace Buster;

use Buster\Git\AbstractFileCollector;
use Buster\Output\Console;
use Buster\Output\OutputInterface;
use Exception;

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
    private $executors = [];

    /**
     * @param AbstractFileCollector $collector
     * @param string $workingDirectory
     */
    public function __construct(AbstractFileCollector $collector, $workingDirectory = '.')
    {
        $this->collector = $collector;
        $this->output = new Console;
        $this->workingDirectory = $workingDirectory;

        $this->initErrorHandlers();
    }

    /**
     * Overwrite the default output
     *
     * @param OutputInterface $output
     * @return void
     */
    public function setOutput(OutputInterface $output)
    {
        $this->output = $output;
    }

    /**
     * @return void
     */
    private function initErrorHandlers()
    {
        assert_options(ASSERT_BAIL, 1);
        set_exception_handler(array($this, 'handleException'));
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
        $executor->setWorkingDirectory($this->workingDirectory);

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
     * @param Exception $exception
     * @return void
     */
    public function handleException(Exception $exception)
    {
        $this->error($exception->getMessage() . ' in ' . $exception->getFile());
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
