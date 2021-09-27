<?php

namespace PLUGIN_SLUG;

use WPTrait\Hook\Notice;
use WPTrait\Hook\RowActions;
use WPTrait\Model;

class Admin extends Model
{
    use Notice, RowActions;

    public function __construct($plugin)
    {
        parent::__construct($plugin);
    }

    public function admin_notices()
    {
        $text = __('This Notice is a example from your plugin', $this->plugin->textdomain);
        $text .= '<br />';
        $text .= __('You Can Call Method From all classes by plugin_slug() function.', $this->plugin->textdomain);
        $text .= __('For Example `plugin_slug()->Admin->method_name()` is: ', $this->plugin->textdomain);
        $text .= $this->method_name();

        echo $this->add_alert($text, 'info');
    }

    public function method_name()
    {
        return '<span style="color: red;">Code is Poetry</span>';
    }

    public function row_actions($actions, $object)
    {
        $actions['post-action'] = '<a href="' . $this->post->edit_post_link($object->ID) . '">Action Button</a>';

        return $actions;
    }

}