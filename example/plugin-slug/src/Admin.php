<?php

namespace PLUGIN_SLUG;

use WPTrait\Hook\Ajax;
use WPTrait\Hook\Notice;
use WPTrait\Hook\RowActions;
use WPTrait\Model;

class Admin extends Model
{
    use Notice, RowActions, Ajax;

    public $ajax = [
        'methods' => ['signup_user']
    ];

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

    public function admin_ajax_signup_user()
    {
        # Check User is Auth
        if ($this->user->auth()) {
            $this->request->json(['message' => __('You are a user of the site', 'wp-plugin')], 400);
        }

        # Get Input Email
        $email = $this->request->input('email');

        # Check empty email
        if (!$this->request->filled('email')) {
            $this->request->json(['message' => __('Please fill your email', 'wp-plugin')], 400);
        }

        # Check this Email has in site
        if ($this->user->exists($email)) {
            $this->request->json(['message' => __('Sorry, that email address is already used!', 'wp-plugin')], 400);
        }

        # Create User
        $user_id = $this->user->add([
            'email' => $email,
            'username' => $email
        ]);
        if ($this->error->has($user_id)) {
            $this->request->json(['message' => $this->error->message($user_id)], 400);
        }

        # Return Success
        $this->request->json(['user_id' => $user_id], 200);

        # Need for End of WordPress Ajax request
        exit;
    }

}