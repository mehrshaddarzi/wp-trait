<?php

namespace PLUGIN_SLUG;

use WPTrait\Has\HasNotice;
use WPTrait\Model;

class Admin extends Model
{
    use HasNotice;

    public $textDomain;

    public function __construct()
    {
        # Parent construct
        parent::__construct();

        # Register Admin Notice
        $this->register_admin_notices();

        # Get Plugin Text Domain
        $this->textDomain = plugin_slug()->plugin->TextDomain;
    }

    public function admin_notices()
    {
        $text = __('This Notice is a example from your plugin', $this->textDomain);
        $text .= '<br />';
        $text .= __('You Can Call Method From all classes by plugin_slug() function.', $this->textDomain);
        $text .= __('For Example `plugin_slug()->Admin->method_name()` is: ', $this->textDomain);
        $text .= $this->method_name();

        echo $this->add_alert($text, 'info');
    }

    public function method_name()
    {
        return '<span style="color: red;">Code is Poetry</span>';
    }

}