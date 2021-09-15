<?php

namespace PLUGIN_SLUG;

use WPTrait\Admin\Taxonomy;
use WPTrait\Hook\RowActions;

class Country extends Taxonomy
{
    use RowActions;

    public $rowActions = array('type' => 'country');

    public function __construct($slug, $name, $post_types, $args, $plugin)
    {
        parent::__construct($slug, $name, $post_types, $args, $plugin);
    }

    public function row_actions($action, $object)
    {
        $action['new-button'] = '<a href="#">Action button</a>';
        return $action;
    }
}