<?php

namespace Buster\Executor;

use Buster\Git\Hook\AbstractFileCollector;
use Symfony\Component\Process\ProcessBuilder;

class PhpUnit extends AbstractExecutor
{
    /**
     * @var string
     */
    private $workingDir;

    /**
     * @var string
     */
    private $phpBin;

    /**
     * @param string $workingDir
     * @param string $phpBin
     */
    public function __construct($workingDir, $phpBin = 'phpunit')
    {
        $this->workingDir = $workingDir;
        $this->phpBin = $phpBin;
    }

    /**
     * @return string
     */
    function getName()
    {
        return 'PHPUnit';
    }

    /**
     * Return an array of hook names an executor can be attached to.
     *
     * @return array
     */
    protected function getAllowedHookTypes()
    {
        return array(AbstractFileCollector::HOOK_PRE_COMMIT);
    }

    /**
     * @return int
     */
    public function execute()
    {
        $processBuilder = new ProcessBuilder(array('php', $this->phpBin));
        $processBuilder->setWorkingDirectory($this->workingDir);
        $processBuilder->setTimeout(3600);
        $phpUnit = $processBuilder->getProcess();
        $phpUnit->run(array($this, 'notifyOutput'));

        if ($phpUnit->isSuccessful()) {
            return 0;
        }

        return $phpUnit->getExitCode();
    }
}
