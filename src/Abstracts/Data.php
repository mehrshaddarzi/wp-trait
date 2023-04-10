<?php

namespace WPTrait\Abstracts;

abstract class Data
{

    /**
     * Object id
     *
     * @var int
     */
    public int $id = 0;

    public function __construct($id = 0)
    {
        $this->id = $id;
    }

    abstract public function save();

    abstract public function delete();

    abstract public function create();

    abstract public function get();

    abstract public function query();
}