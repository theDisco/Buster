<?php

use Buster\Executor\Lint;
use Buster\Fixtures;
use Buster\Mocks;

class LintTest extends PHPUnit_Framework_TestCase
{
    public function testReturnExitCodeGreaterThanZeroIfAtLeastOneFileContainsErrors()
    {
        $exitCode = $this->runLintForFileCollection(Fixtures\LintFiles::getValidAndInvalidFiles());
        $this->assertTrue($exitCode > 0);
    }

    public function testReturnExitCodeZeroIfAllFilesDoNotContainErrors()
    {
        $this->assertSame(0, $this->runLintForFileCollection(Fixtures\LintFiles::getValidFiles()));
    }

    public function runLintForFileCollection(array $fileCollection)
    {
        $lint = new Lint;
        $lint->setGitHookFileCollector(new Mocks\Git\PreCommitFileCollector($fileCollection));

        return $lint->execute();
    }
}
