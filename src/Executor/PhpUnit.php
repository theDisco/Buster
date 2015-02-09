<?php

namespace Buster\Executor;

use Buster\Git\Hook\AbstractFileCollector;
use Symfony\Component\Process\ProcessBuilder;
use RuntimeException;

class PhpUnit extends AbstractExecutor
{
    /**
     * @var string
     */
    private $phpBin;

    /**
     * @param string $phpBin
     */
    public function __construct($phpBin = 'phpunit')
    {
        if (!is_file($phpBin) || !is_executable($phpBin)) {
            throw new RuntimeException("$phpBin is not an executable file");
        }

        $this->phpBin = $phpBin;
    }

    /**
     * @return string
     */
    public function getName()
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
        $processBuilder->setWorkingDirectory($this->getWorkingDirectory());
        $processBuilder->setTimeout(3600);
        $phpUnit = $processBuilder->getProcess();
        $phpUnit->run(array($this, 'notifyOutput'));

        if ($phpUnit->isSuccessful()) {
            return 0;
        }

        return $phpUnit->getExitCode();
    }
}
