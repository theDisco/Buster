<?php

use Buster\Executor\PhpCodeSniffer;
use Buster\Fixtures;
use Buster\Mocks;

class PhpCodeSnifferTest extends PHPUnit_Framework_TestCase
{
    public function testFailIfPhpBinNotFound()
    {
        $this->setExpectedException('\RuntimeException');
        new PhpCodeSniffer('phpcs_exe');
    }

    public function testReturnExitCodeGreaterThanZeroIfAtLeastOneFileContainsErrors()
    {
        $exitCode = $this->runCodeSnifferForFileCollection(Fixtures\PhpCodeSnifferFiles::getValidAndInvalidFiles());
        $this->assertTrue($exitCode > 0);
    }

    public function testReturnExitCodeZeroIfAllFilesDoNotContainErrors()
    {
        $this->assertSame(0, $this->runCodeSnifferForFileCollection(Fixtures\PhpCodeSnifferFiles::getValidFiles()));
    }

    public function runCodeSnifferForFileCollection(array $fileCollection)
    {
        $codeSniffer = new PhpCodeSniffer(VENDOR_BIN_DIR . '/phpcs');
        $codeSniffer->setGitHookFileCollector(new Mocks\Git\PreCommitFileCollector($fileCollection));

        return $codeSniffer->execute();
    }
}
