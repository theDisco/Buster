<?php

use Buster\Manager;
use Buster\Mocks\Output\OutputCollector;
use Buster\Mocks\Executor\NullExecutor;
use Buster\Mocks\Git\NotExistingCollector;

class ManagerTest extends PHPUnit_Framework_TestCase
{
    public function testDoNotExecuteExecutorsNotAllowedForCollection()
    {
        $executor = new NullExecutor;
        $output = new OutputCollector;

        $manager = new Manager(new NotExistingCollector);
        $manager->setOutput($output);
        $manager->addExecutor($executor);
        $manager->execute();

        $this->assertFalse($executor->wasExecuted());
    }
}
