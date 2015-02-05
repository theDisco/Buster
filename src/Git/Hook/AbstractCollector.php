<?php

namespace Buster\Git\Hook;

use Buster\Iterator;

abstract class AbstractCollector
{
    const HOOK_PRE_COMMIT = 'pre-commit';

    /**
     * @param string $regex
     * @return Iterator\RegexIterator
     */
    public function collectByRegex($regex)
    {
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