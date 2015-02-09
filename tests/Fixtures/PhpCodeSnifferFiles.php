<?php

namespace Buster\Fixtures;

class PhpCodeSnifferFiles
{
    /**
     * @return array
     */
    public static function getValidAndInvalidFiles()
    {
        return array(
            RESOURCES_PATH . '/code-sniffer/correct.php',
            RESOURCES_PATH . '/code-sniffer/incorrect.php',
        );
    }

    /**
     * @return array
     */
    public static function getValidFiles()
    {
        return array(
            RESOURCES_PATH . '/code-sniffer/correct.php',
        );
    }
}
