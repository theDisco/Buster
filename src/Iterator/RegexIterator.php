<?php

namespace Buster\Iterator;

use Countable;
use Iterator;

class RegexIterator implements Iterator, Countable
{
    private $files;
    private $regex;
    private $pointer;

    public function __construct(array $files, $regex)
    {
        $this->files = $files;
        $this->regex = $regex;
        $this->pointer = 0;
    }

    /**
     * @return string
     */
    public function current()
    {
        return $this->files[$this->pointer];
    }

    /**
     * @return void
     */
    public function next()
    {
        $this->pointer++;
    }

    /**
     * @return int
     */
    public function key()
    {
        return $this->pointer;
    }

    /**
     * @return bool
     */
    public function valid()
    {
        $current = isset($this->files[$this->pointer]) ? $this->files[$this->pointer] : null;

        if ($current && !preg_match($this->regex, $current)) {
            $this->next();
            return $this->valid();
        }

        return isset($this->files[$this->pointer]);
    }

    /**
     * @return void
     */
    public function rewind()
    {
        $this->pointer = 0;
    }

    /**
     * @return int
     */
    public function count()
    {
        $count = 0;
        $this->rewind();

        foreach ($this as $file) {
            $count++;
        }

        $this->rewind();

        return $count;
    }
}