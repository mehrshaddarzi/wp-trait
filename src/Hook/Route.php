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
        $this->addRoute();
        
        if (  defined( 'WP_DEBUG' ) ) {
            flush_rewrite_rules();
        }
        
    }

    public function addRoute()
    {
        foreach ($this->Route as $routeName => $RouteArg) {
            $this->addRewriteRule($routeName, $RouteArg[0]);
            if (!empty($RouteArg[1]) && method_exists($this, $RouteArg[1])) {
                add_filter('template_include', [$this, $RouteArg[1]]);
            }
        }
        
    }

    public function addRewriteRule($routeName, $queryNames, $routeCallBack)
    {
        $query = '';
        $keys = 1;
        foreach ($queryNames  as $value) {
            $query .= $value . '=$matches[' . $keys . ']&';
            $keys++;
            add_rewrite_tag('%' . $value . '%', '([^&/]+)');
        }

        add_rewrite_rule($routeName, 'index.php?' . $query, 'top');


    }
}

}
