<?php

namespace Buster\Mocks\Git;

use Buster\Git\AbstractFileCollector;

class NotExistingCollector extends AbstractFileCollector
{
    /**
     * @return string
     */
    public function collectionForHook()
    {
        return 'not existing collector';
    }

    /**
     * @return array
     */
    protected function getCollectedFiles()
    {
        // noop
    }
}
