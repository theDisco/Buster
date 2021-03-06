<?php

namespace Buster\Fixtures;

class LintFiles
{
    /**
     * @return array
     */
    public static function getValidAndInvalidFiles()
    {
        return array(
            RESOURCES_PATH . '/lint/correct.php',
            RESOURCES_PATH . '/lint/incorrect.php',
        );
    }

    /**
     * @return array
     */
    public static function getValidFiles()
    {
        return array(
            RESOURCES_PATH . '/lint/correct.php',
        );
    }
}