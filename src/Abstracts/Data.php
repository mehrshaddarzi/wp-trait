<?php

namespace WPTrait\Abstracts;

use WPTrait\Data\Meta;
use WPTrait\Interfaces\toArray;
use WPTrait\Utils\Arr;

abstract class Data extends Result implements toArray
{

    /**
     * Object id
     *
     * @var int
     */
    public int $id = 0;

    /**
     * Object Meta type
     *
     * @var string
     */
    protected string $meta_type = 'post';

    /**
     * Object Meta List
     *
     * @var array|Meta
     */
    public array|Meta $meta;

    /**
     * Original Data Object
     *
     * @var array
     */
    protected array $original = [];

    /**
     * Changed Property Data
     *
     * @var array
     */
    protected array $changed = [];

    public function __construct($id, $meta_type)
    {
        $this->id = $id;
        $this->meta_type = $meta_type;
    }

    abstract public function save();

    abstract public function delete();

    abstract public static function find($id);

    abstract public static function findOr($id, $func);

    abstract public function get();

    public function refresh()
    {
        $this->get();
        $this->changed = [];
    }

    abstract public static function query();

    abstract public static function exists($id);

    abstract public static function new();

    protected static function instance($id, $meta_type)
    {
        $class = get_called_class();
        return new $class($id, $meta_type);
    }

    public function meta($list = []): Meta|static
    {
        if (Arr::isAssoc($list)) {
            $this->meta = $list;
            $this->changed('meta');
            return $this;
        }

        return new Meta($this->id, $this->meta_type);
    }

    public function getOriginal($key = '')
    {
        if (empty($key)) {
            return $this->original;
        }

        return ($this->original[$key] ?? null);
    }

    public function wasChanged($key): bool
    {
        return (in_array($key, $this->changed));
    }

    public function changed($key): void
    {
        $this->changed[] = $key;
    }

    public function toArray(): array
    {
        $obj = Arr::except(get_object_vars($this), ['original', 'changed', 'meta_type', 'params', 'meta']);
        if (func_num_args() > 0) {
            return Arr::only($obj, func_get_args());
        }

        return $obj;
    }
}