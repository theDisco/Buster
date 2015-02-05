<?php

use Buster\Iterator\RegexIterator;

class RegexIteratorTest extends PHPUnit_Framework_TestCase
{
    public function testLocateFilesByRegex()
    {
        $files = array('test1.php', 'test2.exe', 'test3.php');
        $iterator = new RegexIterator($files, '/\.php$/');
        $result = iterator_to_array($iterator);

        $this->assertCount(2, $iterator);
        $this->assertSame('test1.php', $result[0]);
        $this->assertSame('test3.php', $result[2]);
    }
}
