<?php

namespace Buster\Executor;

use Buster\Git\Hook\AbstractFileCollector;
use Symfony\Component\Process\ProcessBuilder;

class Lint extends AbstractExecutor
{
    /**
     * @return string
     */
    public function getName()
    {
        return 'PHP Lint';
    }

    /**
     * @return array
     */
    protected function getAllowedHookTypes()
    {
        return array(
            AbstractFileCollector::HOOK_PRE_COMMIT,
        );
    }

    /**
     * @return int
     */
    public function execute()
    {
        $collection = $this->getGitHookFileCollector()->collectByRegex('/(\.php)|(\.inc)$/');
        $exitCodes = 0;

        foreach ($collection as $file) {
            $exitCodes += $this->lint($file);
        }

        return $exitCodes;
    }

    /**
     * @param string $file
     * @return int
     */
    private function lint($file)
    {
        $processBuilder = new ProcessBuilder(array('php', '-l', $file, '2>/dev/null'));
        $lint = $processBuilder->getProcess();
        $lint->run(array($this, 'notifyOutput'));

        return $lint->getExitCode();
    }
}
