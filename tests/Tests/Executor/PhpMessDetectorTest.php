<?php

use Buster\Executor\PhpMessDetector;
use Buster\Fixtures;
use Buster\Mocks;

class PhpMessDetectorTest extends PHPUnit_Framework_TestCase
{
    public function testFailIfPhpBinNotFound()
    {
        $this->setExpectedException('\RuntimeException');
        new PhpMessDetector('phpmd_exe');
    }

    public function testReturnExitCodeGreaterThanZeroIfAtLeastOneFileContainsErrors()
    {
        $exitCode = $this->runMessDetectorForFileCollection(Fixtures\PhpMessDetectorFiles::getValidAndInvalidFiles());
        $this->assertTrue($exitCode > 0);
    }

    public function testReturnExitCodeZeroIfAllFilesDoNotContainErrors()
    {
        $this->assertSame(0, $this->runMessDetectorForFileCollection(Fixtures\PhpMessDetectorFiles::getValidFiles()));
    }

    public function runMessDetectorForFileCollection(array $fileCollection)
    {
        $messDetector = new PhpMessDetector(VENDOR_BIN_DIR . '/phpmd');
        $messDetector->setGitHookFileCollector(new Mocks\PreCommitFileCollector($fileCollection));

        return $messDetector->execute();
    }
}