<?php

namespace Buster\Executor;

use Buster\Git\AbstractFileCollector;
use Symfony\Component\Process\ProcessBuilder;
use RuntimeException;

class PhpMessDetector extends AbstractExecutor
{
    /**
     * @var string
     */
    private $phpBin;

    /**
     * @var string
     */
    private $checks;

    /**
     * @param string $phpBin
     * @param string $checks
     */
    public function __construct($phpBin, $checks = null)
    {
        if (!is_file($phpBin) || !is_executable($phpBin)) {
            throw new RuntimeException("$phpBin is not an executable file");
        }

        $this->phpBin = $phpBin;
        $this->checks = $checks;

        if (is_null($checks)) {
            $this->checks = 'controversial,unusedcode,codesize,design,naming';
        }
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'PHP Mess Detector';
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
        $collection = $this->getGitHookFileCollector()->collectByRegex('/(\.php)|(\.inc)$/');
        $exitCodes = 0;

        foreach ($collection as $file) {
            $exitCodes += $this->phpMd($file);
        }

        return $exitCodes;
    }

    /**
     * @param string $file
     * @return int
     */
    private function phpMd($file)
    {
        $processBuilder = new ProcessBuilder(
            array(
                'php',
                $this->phpBin,
                $file,
                'text',
                $this->checks
            )
        );
        $processBuilder->setWorkingDirectory($this->getWorkingDirectory());
        $process = $processBuilder->getProcess();
        $collected = '';
        $process->run(
            function ($type, $buffer) use (&$collected) {
                if ($type == 'out') {
                    $collected .= $buffer;
                }
            }
        );

        if (strlen($collected) > 1) {
            $this->notifyOutput('err', $collected . "\n");
        }

        return $process->getExitCode();
    }
}
