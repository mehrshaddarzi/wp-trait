<?php

namespace WPTrait\Interfaces;

interface Hooks
{

    public function add($hook_name, $callback, $priority = 10, $accepted_args = 1);

    public function remove($hook_name, $callback, $priority = 10);

    public function current();

    public function all();

    public function has($hook_name, $callback = false);

    public function doing($hook_name = null);

    public function reset($hook_name, $priority = false);

}