<?php

namespace Buster\Fixtures;

class PhpMessDetectorFiles
{
    /**
     * @return array
     */
    public static function getValidAndInvalidFiles()
    {
        return array(
            RESOURCES_PATH . '/mess-detector/correct.php',
            RESOURCES_PATH . '/mess-detector/incorrect.php',
        );
    }

    /**
     * @return array
     */
    public static function getValidFiles()
    {
        return array(
            RESOURCES_PATH . '/mess-detector/correct.php',
        );
    }
}
