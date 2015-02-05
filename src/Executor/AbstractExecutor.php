<?php

namespace Buster\Executor;

use Buster\Git\Hook\AbstractCollector;

abstract class AbstractExecutor
{
    /**
     * Validates, if the executor should be executed for
     * the file collection provided from current hook.
     *
     * @param AbstractCollector $collector
     * @return bool
     */
    public function shouldExecute(AbstractCollector $collector)
    {
        if (!in_array($collector->collectionForHook(), $this->getAllowedHookTypes())) {
            return false;
        }

        return true;
    }

    /**
     * Return an array of hook names an executor can be attached to.
     *
     * @return array
     */
    abstract protected function getAllowedHookTypes();

    abstract public function execute(AbstractCollector $collector);
}
