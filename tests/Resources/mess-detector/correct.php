<?php

class CorrectPhpClassWithAName
{
    private $test;

    public function __construct()
    {
        $this->test = 'test';
    }

    public function getTest()
    {
        return $this->test;
    }
}
