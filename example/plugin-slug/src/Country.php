<?php

namespace PLUGIN_SLUG;

use WPTrait\Admin\Taxonomy;
use WPTrait\Hook\RowActions;
use WPTrait\Information;

class Country extends Taxonomy
{
    use RowActions;

    public array $rowActions = ['type' => 'country'];

    public function __construct(Information $plugin, $slug, $name, $post_types, $args)
    {
        parent::__construct($plugin, $slug, $name, $post_types, $args);
    }

    public function row_actions($action, $object)
    {
        $action['new-button'] = '<a href="#">Action button</a>';
        return $action;
    }
}
