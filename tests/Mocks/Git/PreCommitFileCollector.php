<?php

namespace Buster\Mocks\Git;

use Buster\Git\AbstractFileCollector;

class PreCommitFileCollector extends AbstractFileCollector
{
    /**
     * @var array
     */
    private $collectedFiles;

    /**
     * @param array $collectedFiles
     */
    public function __construct(array $collectedFiles)
    {
        $this->collectedFiles = $collectedFiles;
    }

    /**
     * @return string
     */
    public function collectionForHook()
    {
        return AbstractFileCollector::HOOK_PRE_COMMIT;
    }

    /**
     * @return array
     */
    protected function getCollectedFiles()
    {
        return $this->collectedFiles;
    }
}
