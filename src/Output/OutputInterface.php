<?php

namespace Buster\Output;

interface OutputInterface
{
    public function error($message);

    public function info($message);

    public function success($message);

    public function write($message);

    public function outputFromProcess($type, $message);
}
