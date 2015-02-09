<?php

namespace Buster\Executor;

use Buster\Git\Hook\AbstractFileCollector;
use Symfony\Component\Process\ProcessBuilder;
use RuntimeException;

class PhpCodeSniffer extends AbstractExecutor
{
    /**
     * @var string
     */
    private $phpBin;

    /**
     * @var string
     */
    private $standard;

    /**
     * @param string $phpBin
     * @param string $standard
     */
    public function __construct($phpBin = 'phpcs', $standard = 'PSR2')
    {
        if (!is_file($phpBin) || !is_executable($phpBin)) {
            throw new RuntimeException("$phpBin is not an executable file");
        }

        $this->phpBin = $phpBin;
        $this->standard = $standard;
    }

    /**
     * Return the name that identifies the executor. It is only used
     * for display purposes.
     *
     * @return string
     */
    public function getName()
    {
        return 'PHP Code Sniffer';
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
     * Execute the process.
     *
     * @return int
     */
    public function execute()
    {
        $collection = $this->getGitHookFileCollector()->collectByRegex('/(\.php)|(\.inc)$/');
        $exitCodes = 0;

        foreach ($collection as $file) {
            $exitCodes += $this->codeSniffer($file);
        }

        return $exitCodes;
    }

    /**
     * @param string $file
     * @return int
     */
    private function codeSniffer($file)
    {
        $processBuilder = new ProcessBuilder(
            array(
                'php',
                $this->phpBin,
                '--standard=' . $this->standard,
                $file
            )
        );
        $processBuilder->setWorkingDirectory($this->getWorkingDirectory());

        $process = $processBuilder->getProcess();
        $process->run(array($this, 'notifyOutput'));

        return $process->getExitCode();
    }
}
