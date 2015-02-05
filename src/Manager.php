<?php

namespace Buster;

use Buster\Git\Hook\AbstractCollector;

class Manager
{
    /**
     * @var AbstractCollector
     */
    private $collector;

    /**
     * @var Executor\AbstractExecutor[]
     */
    private $executors;

    public function __construct(AbstractCollector $collector)
    {
        $this->collector = $collector;
    }

    public function addExecutor(Executor\AbstractExecutor $executor)
    {
        $this->executors[] = $executor;
    }

    public function execute()
    {
        foreach ($this->executors as $executor) {
            if (!$executor->shouldExecute($this->collector)) {
                continue;
            }

            $executor->execute($this->collector);
        }
    }
}