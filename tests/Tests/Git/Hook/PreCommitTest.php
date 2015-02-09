<?php

use Buster\Git\Hook\PreCommit;

class PreCommitTest extends PHPUnit_Framework_TestCase
{
    private $testDir;

    private $testFiles;

    public function setUp()
    {
        $this->testDir = $this->join(array(sys_get_temp_dir(), 'buster-test'));

        if (is_dir($this->testDir)) {
            $this->removeRecursively($this->testDir);
        }

        $this->testFiles = array(
            $this->join(array($this->testDir, 'test1.php')),
            $this->join(array($this->testDir, 'test2.php')),
            $this->join(array($this->testDir, 'test3.php')),
        );
    }

    public function testCollectFilesFromPreCommitOnAnEmptyRepository()
    {
        $this->initRepository('initialized');
        $this->runPreCommitHookTest();
    }

    public function testCollectFilesFromPreCommitOnAnExistingRepository()
    {
        $this->initRepository('initialized');
        $this->commitToRepository();

        array_shift($this->testFiles);

        $this->writeContentToFiles('after initialization');
        $this->runPreCommitHookTest();
    }

    private function runPreCommitHookTest()
    {
        $preCommit = new PreCommit;
        $iterator = $preCommit->collectByRegex('/.*/');
        $foundFiles = iterator_to_array($iterator);

        foreach ($this->testFiles as $index => $testFile) {
            $fileInfo = new \SplFileInfo($testFile);
            $this->assertSame($fileInfo->getFilename(), $foundFiles[$index]);
        }
    }

    public function testDoNotListDeletedFiles()
    {
        $this->initRepository('initialized');
        $this->commitToRepository();

        exec('git rm test2.php');
        exec('git rm test3.php');

        $preCommit = new PreCommit;
        $iterator = $preCommit->collectByRegex('/.*/');
        $this->assertEmpty(iterator_to_array($iterator));
    }

    private function initRepository($fileContent)
    {
        if (!is_dir($this->testDir)) {
            mkdir($this->testDir);
        }

        chdir($this->testDir);
        exec('git init');

        $this->writeContentToFiles($fileContent);
    }

    private function writeContentToFiles($fileContent)
    {
        foreach ($this->testFiles as $testFile) {
            file_put_contents($testFile, $fileContent);
        }

        exec('git add .');
    }

    private function commitToRepository()
    {
        exec('git commit -a -m "initial commit"');
    }

    public function tearDown()
    {
        if (is_dir($this->testDir)) {
            $this->removeRecursively($this->testDir);
        }
    }

    private function removeRecursively($dir)
    {
        $files = scandir($dir);

        foreach ($files as $file) {
            if ($file != '.' && $file != '..') {
                if (filetype($this->join(array($dir, $file))) == "dir") {
                    $this->removeRecursively($this->join(array($dir, $file)));
                } else {
                    unlink($this->join(array($dir, $file)));
                }
            }
        }

        reset($files);
        rmdir($dir);
    }

    private function join(array $parts)
    {
        return implode(DIRECTORY_SEPARATOR, $parts);
    }
}
