<?php


namespace WPTrait\Hook;

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

if (!trait_exists('WPTrait\Hook\Route')) {

trait Route
{




    public function bootRoute($arg = [])
    {
        if (empty($arg)) return;

        $this->addRoute($arg);

        if (WP_DEBUG) {
            flush_rewrite_rules();
        }
    }

    public function addRoute($arg)
    {
        foreach ($arg as $routeName => $RouteArg) {
            $this->addRewriteRule($routeName, $RouteArg['tags']);
            if (!empty($RouteArg['cb']) && method_exists($this, $RouteArg['cb'])) {

                if (!empty($RouteArg['template'])) {
                    add_filter('template_include', function ($template) use ($RouteArg) {
                        return  $this->{$RouteArg['cb']}($template);
                        exit;
                    });
                } else {
                    add_filter('template_redirect', function () use ($RouteArg) {
                        $this->ajaxHeader();
                        $this->{$RouteArg['cb']}();
                        exit;
                    });
                }
                if (!empty($RouteArg['title']) && method_exists($this, $RouteArg['title'])) {
                    add_filter('pre_get_document_title', [$this, $RouteArg['title']]);
                }
            }
        }
    }

    public function addRewriteRule($routeName, $queryNames)
    {
        $query = '';
        $key = 1;
        foreach ($queryNames  as $keyName => $value) {
            if (is_numeric($keyName)) {
                $query .= $value . '=$matches[' . $key . ']&';
                $key++;
                add_rewrite_tag('%' . $value . '%', '([^&/]+)');
            } else {
                $query .= $keyName . '=' . $value . '&';
                add_rewrite_tag('%' . $keyName . '%', '([^&/]+)');
            }
        }


        add_rewrite_rule($routeName, 'index.php?' . rtrim($query, '&'), 'top');


    }


    public function ajaxHeader()
    {


        if (!WP_DEBUG || (WP_DEBUG && !WP_DEBUG_DISPLAY)) {
            @ini_set('display_errors', 0); // Turn off display_errors during AJAX events to prevent malformed JSON.
        }


        if (!defined("DOING_AJAX")) define("DOING_AJAX", true);
        if (!defined("DONOTCACHEPAGE")) define("DONOTCACHEPAGE", true);
        if (!defined("DONOTCACHEOBJECT")) define("DONOTCACHEOBJECT", true);
        if (!defined("DONOTCACHEDB")) define("DONOTCACHEDB", true);


        $this->db->hide_errors();

        if (!headers_sent()) {
            send_origin_headers();
            send_nosniff_header();
            nocache_headers();
            header('Content-Type: text/html; charset=' . get_option('blog_charset'));
            header('X-Robots-Tag: noindex');
            status_header(200);
        } elseif (WP_DEBUG) {
            headers_sent($file, $line);
            trigger_error("ajax_headers cannot set headers - headers already sent by {$file} on line {$line}", E_USER_NOTICE);
        }
    }
}

}
