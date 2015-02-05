<?php

namespace Buster\Git\Hook;

class PreCommit extends AbstractCollector
{
    /**
     * @return string
     */
    public function collectionForHook()
    {
        return self::HOOK_PRE_COMMIT;
    }

    /**
     * @return array
     */
    protected function getCollectedFiles()
    {
        $against = 'HEAD';
        exec('git rev-parse --verify HEAD >/dev/null 2>&1', $output, $return);

        if ($return > 0) {
            $against = '4b825dc642cb6eb9a060e54bf8d69288fbee4904';
        }

        exec("git diff-index --check --cached $against --", $output, $return);

        return $output;
    }
}
