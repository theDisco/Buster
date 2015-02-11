<?php

namespace Buster\Mocks\Output;

use Buster\Output\OutputInterface;

class OutputCollector implements OutputInterface
{
    private $info;

    public function error($message)
    {
        // TODO: Implement error() method.
    }

    public function info($message)
    {
        $this->info[] = $message;
    }

    public function success($message)
    {
        // TODO: Implement success() method.
    }

    public function write($message)
    {
        // TODO: Implement write() method.
    }

    public function outputFromProcess($type, $message)
    {
        // TODO: Implement outputFromProcess() method.
    }

    public function getCollectedOutput()
    {
        return array(
            'info' => $this->info,
        );
    }
}
