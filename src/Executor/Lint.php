<?php

namespace Buster\Executor;

use Buster\Git\Hook\AbstractCollector;

class Lint extends AbstractExecutor
{
    /**
     * @return array
     */
    protected function getAllowedHookTypes()
    {
        return array(
            AbstractCollector::HOOK_PRE_COMMIT,
        );
    }

    public function execute(AbstractCollector $collector)
    {
        $collection = $collector->collectByRegex('/(\.php)|(\.inc)$/');

        if ($collection->count()) {
            foreach ($collection as $file) {
                exec("php -l $file", $output, $return);
            }
        }

        exit(1);
    }
}
