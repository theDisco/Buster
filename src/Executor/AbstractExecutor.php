<?php

namespace Buster\Executor;

use Buster\Git\AbstractFileCollector;
use Buster\Manager;
use Buster\Output\OutputInterface;

abstract class AbstractExecutor
{
    /**
     * @var OutputInterface
     */
    private $output;

    /**
     * @var AbstractFileCollector
     */
    private $gitFileHookCollector;

    /**
     * @var string
     */
    private $workingDirectory;

    /**
     * @param Manager $manager
     * @return void
     */
    public function acceptManager(Manager $manager)
    {
        if ($this->shouldExecute($manager->getGitHookFileCollector())) {
            $manager->visitExecutor($this);
        }
    }

    /**
     * Validates, if the executor should be executed for
     * the file collection provided from current hook.
     *
     * @param AbstractFileCollector $collector
     * @return bool
     */
    private function shouldExecute(AbstractFileCollector $collector)
    {
        if (!in_array($collector->collectionForHook(), $this->getAllowedHookTypes())) {
            return false;
        }

        return true;
    }

    /**
     * @param OutputInterface $output
     * @return void
     */
    public function setOutput(OutputInterface $output)
    {
        $this->output = $output;
    }

    /**
     * @param $workingDirectory
     * @return void
     */
    public function setWorkingDirectory($workingDirectory)
    {
        assert(is_dir($workingDirectory));
        $this->workingDirectory = $workingDirectory;
    }

    /**
     * @return string
     */
    protected function getWorkingDirectory()
    {
        return $this->workingDirectory;
    }

    /**
     * @param string $type
     * @param string $message
     * @return void
     */
    public function notifyOutput($type, $message)
    {
        if (!$this->output) {
            return;
        }

        $this->output->outputFromProcess($type, $message);
    }

    /**
     * @param AbstractFileCollector $collector
     * @return void
     */
    public function setGitHookFileCollector(AbstractFileCollector $collector)
    {
        $this->gitFileHookCollector = $collector;
    }

    /**
     * @return AbstractFileCollector
     */
    protected function getGitHookFileCollector()
    {
        return $this->gitFileHookCollector;
    }

    /**
     * Return the name that identifies the executor. It is only used
     * for display purposes.
     *
     * @return string
     */
    abstract public function getName();

    /**
     * Return an array of hook names an executor can be attached to.
     *
     * @return array
     */
    abstract protected function getAllowedHookTypes();

    /**
     * Execute the process.
     *
     * @return int
     */
    abstract public function execute();
}
