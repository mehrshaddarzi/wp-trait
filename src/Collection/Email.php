<?php

namespace WPTrait\Collection;

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

if (!class_exists('WPTrait\Collection\Email')) {

    class Email
    {

        public $email;

        public function __construct($email = null)
        {
            $this->email = $email;
        }

        public function send($subject, $message, $headers = '', $attachments = [], $to = '')
        {
            if (empty($headers)) {
                $headers = array('Content-Type: text/html; charset=UTF-8');
            }

            return wp_mail((is_null($to) ? $this->email : $to), $subject, $message, $headers, $attachments);
        }

    }
}
