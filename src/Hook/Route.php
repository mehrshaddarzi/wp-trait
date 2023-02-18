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
            $this->addRewriteRule($routeName, $RouteArg['tags']);
            if (!empty($RouteArg['template']) && method_exists($this, $RouteArg['template'])) {
                add_filter('template_include', [$this, $RouteArg['template']]);
            }
            if (!empty($RouteArg['title']) && method_exists($this, $RouteArg['title'])) {
                add_filter('pre_get_document_title', [$this, $RouteArg['title']]);
            }
        }
        
    }
  public function addRewriteRule($routeName, $queryNames)
    {
        $query = '';
        $key = 1;
        foreach ($queryNames  as $keyName => $value) {
            if(is_numeric($keyName)){
                $query .= $value . '=$matches[' . $key . ']&';
                $key++;
                add_rewrite_tag('%' . $value . '%', '([^&/]+)');
            }else{
                $query .= $keyName . '='.$value.'&';
                add_rewrite_tag('%' . $keyName . '%', '([^&/]+)');
            }

            
        }

        add_rewrite_rule($routeName, 'index.php?' . $query, 'top');

    }
}

}
