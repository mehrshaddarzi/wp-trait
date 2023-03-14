<?php

namespace WPTrait\Traits;

use WPTrait\Collection\File;

trait FileSystem
{

    /**
     * FileSystem Class
     *
     * @var File
     */
    public $file;

    public function __construct()
    {
        $this->file = new File();
    }

    public function file($file): File
    {
        return new File($file);
    }
}