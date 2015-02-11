<?php

namespace Buster\Git\Hook;

use Buster\Iterator;

abstract class AbstractFileCollector
{
    const HOOK_PRE_COMMIT = 'pre-commit';

    /**
     * @param string $regex
     * @return Iterator\RegexIterator
     */
    public function collectByRegex($regex)
    {
        // TODO: accept iterator instead of initializing it
        return new Iterator\RegexIterator($this->getCollectedFiles(), $regex);
    }

    /**
     * @return string
     */
    abstract public function collectionForHook();

    /**
     * @return array
     */
    abstract protected function getCollectedFiles();
}
