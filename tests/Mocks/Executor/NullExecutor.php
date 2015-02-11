<?php

namespace Buster\Mocks\Executor;

use Buster\Executor\AbstractExecutor;
use Buster\Git\AbstractFileCollector;

class NullExecutor extends AbstractExecutor
{
    /**
     * @var bool
     */
    private $wasExecuted = false;

    /**
     * Return the name that identifies the executor. It is only used
     * for display purposes.
     *
     * @return string
     */
    public function getName()
    {
        return 'Null Executor';
    }

    /**
     * Return an array of hook names an executor can be attached to.
     *
     * @return array
     */
    protected function getAllowedHookTypes()
    {
        return array(
            AbstractFileCollector::HOOK_PRE_COMMIT
        );
    }

    /**
     * Execute the process.
     *
     * @return int
     */
    public function execute()
    {
        $this->wasExecuted = true;
    }

    /**
     * @return bool
     */
    public function wasExecuted()
    {
        return $this->wasExecuted;
    }
}
