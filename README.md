# Fast and standard development of WordPress plugins

![Packagist](https://img.shields.io/github/license/mehrshaddarzi/wp-trait)
![Packagist Version](https://img.shields.io/github/v/release/mehrshaddarzi/wp-trait)
![GitHub repo size](https://img.shields.io/github/repo-size/mehrshaddarzi/wp-trait)

WP-Trait is an easy framework for Standard and Fast development of WordPress plugins according php MVC model.

## Table of Contents

* [Installation](#installation)
    + [install with WP-CLI](#install-with-wp-cli)
    + [install with Composer](#install-with-composer)
* [Create New Model](#create-new-model)
    + [Generate Model in Command Line](#generate-model-in-command-line)
        - [Generate New Post-Type Model](#generate-new-post-type-model)
        - [Generate New Taxonomy Model](#generate-new-taxonomy-model)
    + [Generate Model manually](#generate-model-manually)
* [Global Function](#global-function)
    + [How to Change Global variable and function name](#how-to-change-global-variable-and-function-name)
* [Model Properties](#model-properties)
    + [WordPress Hooks](#wordpress-hooks)
    + [Use WPDB](#use-wpdb)
    + [Current Plugin information](#current-plugin-information)
    + [Get WordPress default Constants](#get-wordpress-default-constants)
        + [Get WordPress urls and path](#get-wordpress-urls-and-path)
        + [Time in Seconds and Debug Constants](#time-in-seconds-and-debug-constants)
    + [Get WordPress Global Variables](#get-wordpress-global-variables)
    + [WordPress URL Generation](#wordpress-url-generation)
    + [Get Current User data](#get-current-user-data)
* [Collection Class](#collection-class)
    + [Post](#post)
    + [Attachment](#attachment)
    + [User](#user)
    + [Term](#term)
    + [Option](#option)
    + [Comment](#comment)
    + [Meta](#meta)
    + [Request](#request)
    + [Handle Error](#handle-error)
    + [Cache and Transient](#cache-and-transient)
    + [REST API](#rest-api)
    + [Cookie](#cookie)
    + [Session](#session)
    + [Event](#event)
    + [Nonce](#nonce)
    + [File System](#file-system)
    + [Email](#email)
    + [Log](#log)
* [Views Templates](#views-templates)
    + [Overriding templates via a theme](#overriding-templates-via-a-theme)
    + [Set template attribute](#set-template-attribute)
* [Trait For WordPress Hooks](#trait-for-wordpress-hooks)
    + [How To Work Trait Hooks](#how-to-work-trait-hooks)
    + [List Of Trait With Prefix Method Name](#list-of-trait-with-prefix-method-name)
    + [Example Create Ajax Request with Trait](#example-create-ajax-request-with-trait)
* [Utility](#utility)
    + [Singleton Design Pattern](#singleton-design-pattern)
* [Starter Plugin](#starter-plugin)
* [Contributing](#contributing)
* [License](#license)

## Installation

### install with WP-CLI

You Can Generate new plugin with `WP-Trait` Structure:

```console
wp trait start
```

And fill Your Plugin information e.g. slug and namespace:

```
1/12 [--slug=<slug>]: wp-plugin
2/12 [--namespace=<namespace>]: WP_Plugin
3/12 [--plugin_name=<title>]: plugin-name
4/12 [--plugin_description=<description>]: plugin description
5/12 [--plugin_author=<author>]: Mehrshad Darzi
6/12 [--plugin_author_uri=<url>]: https://profiles.wordpress.org/mehrshaddarzi/
7/12 [--plugin_uri=<url>]: https://github.com/mehrshaddarzi/wp-trait
8/12 [--skip-tests] (Y/n): n
9/12 [--ci=<provider>]: travis
10/12 [--activate] (Y/n): y
11/12 [--activate-network] (Y/n): n
12/12 [--force] (Y/n): y
```

Read More About [wp-cli-trait-command](https://github.com/mehrshaddarzi/wp-cli-trait-command) Package.

### install with Composer

1) First Create a new directory in your WordPress plugins dir e.g. `wp-content/plugins/wp-user-mobile`.

3) Run This Command in your directory:

```console
composer require mehrshaddarzi/wp-trait
```

3) Create plugin main file e.g. `wp-user-mobile.php` and write:

```php
/**
 * Plugin Name:       My Basics Plugin
 * Plugin URI:        https://example.com/plugins/the-basics/
 * Description:       Handle the basics with this plugin.
 * Version:           1.10.3
 * Requires at least: 5.2
 * Requires PHP:      7.2
 * Author:            John Smith
 * Author URI:        https://author.example.com/
 * License:           GPL v2 or later
 * License URI:       https://www.gnu.org/licenses/gpl-2.0.html
 * Update URI:        https://example.com/my-plugin/
 * Text Domain:       my-basics-plugin
 * Domain Path:       /languages
 */

# Load Package
require_once dirname(__FILE__) . '/vendor/autoload.php';

# Define Main Class
class WP_User_Mobile extends \WPTrait\Plugin
{

    public function __construct($slug, $args = [])
    {
        parent::__construct($slug, $args);
    }

    public function instantiate(){}

    public function register_activation_hook(){}

    public function register_deactivation_hook(){}

    public static function register_uninstall_hook(){}
}

new WP_User_Mobile('wp-user-mobile');
```

4) You can add PSR-4 namespace in your Composer.json file:

```
{
    "require": {
        "mehrshaddarzi/wp-trait": "^1.0"
    },
    "autoload": {
        "psr-4": {
            "WP_User_Mobile\\": "src/"
        }
    }
}
```

## Create New Model

### Generate Model in Command Line

You Can Create new Model With Custom namespace in WP-CLI:

```console
wp trait make model <class>
```

For Example:

```console
wp trait make model Option
```

or

```console
wp trait make model User\Register
```

#### Generate New Post-Type Model

```console
wp trait make post-type Order
```

#### Generate New Taxonomy Model

```console
wp trait make taxonomy City
```

Read More Options [wp-cli-trait-command](https://github.com/mehrshaddarzi/wp-cli-trait-command) Package.

### Generate Model manually

1) Add new `Admin.php` file in `src/` dir:

```php
namespace WP_User_Mobile;

use WPTrait\Hook\Notice;
use WPTrait\Model;

class Admin extends Model
{
    use Notice;

    public function __construct($plugin)
    {
        parent::__construct($plugin);
    }

    public function admin_notices()
    {
        $text = __('This Notice is a example from your plugin', $this->plugin->textDomain);
        echo $this->add_alert($text, 'info');
    }
    
    public function method_name()
    {
        return 'Code is Poetry';
    }
}
```

2) For Create new instance from this Class add to plugin main file in instantiate method:

```php
public function instantiate()
{
    $this->Admin = new \WP_User_Mobile\Admin($this->plugin);
}
```

## Global Function

You can access to all classes method with global template function by your plugin slug.
for example if your plugin slug is `wp-user-mobile`, you can call method from `Admin` class:

```php
echo wp_user_mobile()->Admin->method_name();
```

or use global variables:

```php
gloabl $wp_user_mobile;
echo $wp_user_mobile->Admin->method_name();
```

This function show `Code is Poetry`.

### How to Change Global variable and function name

You can add `global` parameters in PHP Main WordPress File:

```php
new WP_User_Mobile('wp-user-mobile', ['global' => 'my_global']);
```

and Usage:

```php
echo my_global()->Admin->method_name();
```

Also for disable global function set `null`.

```php
new WP_User_Mobile('wp-user-mobile', ['global' => null]);
```

List of arguments when Create new Plugin object:

```php
$default = [
   'main_file' => '',
   'global' => null,
   'prefix' => null,
   'when_load' => ['action' => 'plugins_loaded', 'priority' => 10]
];
```

## Model Properties

### WordPress Hooks

You can use `$actions` and `$filters` property in all class, for example:

```php
use WPTrait\Model;

class Post extends Model
{
    public $actions = [
        'init' => 'init_check_user',
        'save_post' => ['save_post_view', 10, 3],
        'pre_get_posts' => 'custom_query_action',
        'admin_notices' => [
            ['save_user_address', 12, 1],
            ['disable_plugin_option', 10, 1]
        ]
    ];

    public $filters = [
        'the_content' => 'add_custom_text',
        'show_admin_bar' => '__return_false',
        'rest_enabled' => false,
        'pre_http_request' => ['disable_custom_api', 10, 3]
    ];

    public function add_custom_text($content)
    {
        return $content . 'My Text';
    }

    public function save_post_view($post_ID, $post, $update)
    {
        if (!$update) {
            $this->post($post_ID)->meta->save('views', 1);
        }
    }
    
    public function disable_custom_api($preempt, $parsed_args, $url) {
        if( strpos($url, 'https://any_domain.com') !==false ){
            return new \WP_Error( 'http_request_block', "This request is not allowed" );
        }
        return $preempt;
    }
}
```

### Use WPDB

use `$this->db` for run Query in WordPress Database:

```php
// Get List of students from custom WordPress table
$lists = $this->db->get_results("SELECT ID, first_name FROM {$this->db->prefix}students ORDER BY ID");
foreach ($lists as $student) {
    echo $student->ID;
}

// Insert new item in Database
$this->db->insert($this->db->prefix.'students', [
'name' => 'Mehrshad',
'family' => Darzi
]);
echo $this->db->insert_id;
```

### Current Plugin information

For get current plugin information use `$this->plugin` variable:

```php
// Get Plugin Base Url
$this->plugin->url

// Get Plugin Base Path
$this->plugin->path

// Get Plugin TextDomain
$this->plugin->textDomain

// Get Plugin Main PHP File path
$this->plugin->mainFile

// Get Plugin Name
$this->plugin->name

// Get Plugin version
$this->plugin->version

// Get Plugin description
$this->plugin->description

// Get Plugin author name
$this->plugin->author

// Get Plugin Minimum required version of WordPress
$this->plugin->requiresWP

// Get Plugin Minimum required version of PHP
$this->plugin->requiresPHP

// Whether the plugin can only be activated network-wide. (boolean)
$this->plugin->network

// Get file url from plugin
// https://site.com/wp-contents/plugins/my-plugin/images/logo.png
$this->plugin->url('images/logo.png')

// Get file path from plugin
// ~ wp-contents/plugins/my-plugins/templates/email.php
$this->plugin->path('templates/email.php')

// Get All plugins data as Object
$this->plugin->data
```

### Get WordPress default Constants

For get WordPress default Constants use `$this->constant` variable:

#### Get WordPress default urls and path:

```php
// Get ABSPATH (WordPress Root Directory)
$this->constant->root

// Get WP-Content Dir Path
$this->constant->content_dir

// Get WP-Content Dir Url
$this->constant->content_url

// Get Plugins Dir Path
$this->constant->plugin_dir

// Get Plugins Dir Url
$this->constant->plugin_url

// Get Uploads Directory in WordPress
// Object {basedir|baseurl|subdir|path|url}
$this->constant->uploads

// Get Base Uploads dir path
$this->constant->uploads->basedir;

// Get Base Uploads dir url
$this->constant->uploads->baseurl;

// Get Active Theme path
$this->constant->template_path;

// Get Active Theme url
$this->constant->template_url;

// Get themes dir path
$this->constant->theme_root;

// Get themes directory url
$this->constant->theme_root_url;

// Get Mu-Plugins dir path
$this->constant->mu_plugin_dir;

// Get Mu-Plugins dir url
$this->constant->mu_plugin_url;
```

#### Time in Seconds and Debug Constants:

```php
// Check WP_DEBUG is true (boolean)
$this->constant->debug

// Get WordPress environment type
$this->constant->environment

// Check SCRIPT_DEBUG is true (boolean)
$this->constant->script_debug

// Check WP_CACHE is true (boolean)
$this->constant->cache

// Get Time in Seconds
$this->constant->minute
$this->constant->hour
$this->constant->day 
$this->constant->week
$this->constant->month
$this->constant->year
```

### Get WordPress Global Variables

For get WordPress global variables use `$this->global` variable:

```php
// WordPress Current global $post data
$this->global->post

// WordPress Current global $wp_query data
$this->global->query

// WordPress Current Version
$this->global->version

// WordPress Current db Version
$this->global->db_version

// WordPress WP Request Object
$this->global->wp

// WordPress Rewrite Object Request
$this->global->rewrite

// WordPress User Roles list
$this->global->roles

// WordPress Locale
$this->global->locale

// WordPress AdminBar Object
$this->global->admin_bar

// WordPress Current Admin Page Now
$this->global->page_now

// Get Current Admin Screen detail
$this->global->screen

// Get List Of Admin Menu
$this->global->menu

// Get List Of Submenus in Admin
$this->global->submenu

// Get List Of registered Sidebars
$this->global->sidebars

// Get List of Registered Meta Boxes by current Screen
$this->global->meta_boxes
```

### WordPress URL Generation

Use `$this->url` variable:

```php
// WordPress Home Url
$this->url->home

// WordPress Site Url
$this->url->site

// WordPress Site Url with Custom Path and queries
// Site.Com/blog?filter=category&ids=3,4
$this->url->get('/blog', ['filter' => 'category', 'ids' => '3,4'])

// WordPress Admin Url
// Site.com/wp-admin/users.php?sort=ID
$this->url->admin('users.php', ['sort' => 'ID'])

// WordPress Admin Ajax Url
// Site.com/wp-admin/admin-ajax.php?action=new_user&cache=no
$this->url->ajax('new_user', ['cache' => 'no'])

// WordPress REST API Url
// Site.com/wp-json/wp/v2/search?filter=name&s=Mehrshad
$this->url->rest('wp/v2/search', ['filter' => 'name', 's' => 'Mehrshad'])

// WordPress REST API Prefix
// Default is: wp-json
$this->url->restPrefix()

// WordPress CronJob Url
// Site.com/wp-cron.php?doing_wp_cron
$this->url->cron()

// Generate Url
// https://site.com?query=value
$this->url->generate('https://site.com', ['query' => 'value']);

// Parse URl
// @see https://www.php.net/manual/en/function.parse-url.php
$this->url->parse('https://site.com?query=value');

// Sanitize Url
$this->url->sanitize('https://site.com?query=value');

// Validate Url
$this->url->isValidate('https://site.com<script>alert("xss")<script>?query=value');

// Escape Url
$this->url->esc('https://site.com?query=value');
```


### Get Current User data

for get Current User data use `$this->user` variable:

```php
// Get Current User ID
// You Can Access All Object From WP_User Class
$this->user->id;

// Get Current User Email
$this->user->email;

// Get Current User Role
$this->user->roles;

// Get All User Meta
// Check Meta Collection Class
$this->user->meta->all();
```

## Collection Class

This package has list of WordPress helper class, that you can use it.

### Post

```php
// Get Post
$this->post(1)->get();

// Get Post Meta
$this->post(1)->meta->all();

// Get Custom Meta
$this->post(1)->meta->get('key');

// Get Multiple Custom Meta Keys
$this->post(1)->meta->only(['key_1', 'key_2']);

// Save Post Meta
$this->post(1)->meta->save('key', 'value');

// Delete Post
$this->post(1)->delete();

// Get List Of post
$this->post->list(['type' => 'post', 'status' => 'publish', 'cache' => false]);

// Get Only SQL Query
$this->post->toSql([
    'type' => 'post',
    'status' => 'publish',
    'meta' => [
        'key' => 'is_active',
        'value' => 'yes',
        'compare' => '='
    ]
]);

// Get Post Thumbnail
$this->post(1)->thumbnail()->url

// Add Post
$insert_post = $this->post->add(['title' => '', 'content' => '']);
if($this->error->has($insert_post)){
    echo $this->error->message($insert_post);
}

// Edit Post
$this->post(38)->update(['title' => '']);

// Permalink
$this->post(1)->permalink();

// Check Exist
$this->post(53)->exists();

// Post Terms
$this->post(1)->terms('category');

// Post Comments
$this->post(1)->comments();

// Collection { Post + Meta + Terms }
$this->post(1)->collection(['meta_1', 'meta_2'], ['category', 'post_tag']);

```

### Attachment

```php

// Get Attachment
$attachment = $this->attachment(1)->get();

// Get Meta
$this->attachment(1)->meta->all();

// Delete Attachment
$this->attachment(1)->delete();

// Get Url
$this->attachment(1)->url();

// Get Image Src in Custom image size
$this->attachment(1)->src('thumbnail');

// Get Attachment File Path
$this->attachment(1)->path();

// Get Attachment Meta Data
$this->attachment(1)->metadata();

// Auto Upload File in WordPress Library
$attachment_id = $this->attachment->upload('image'); // <input type="file" name="image" />

// Regenerate Attachment image Size
$this->attachment(1)->generate_thumbnail();

// Get List Of WordPres Image Sizes
$this->attachment->get_wordpress_image_sizes();

// Get Uploads Dir
$this->attachment->upload_dir();

// Check Attachment type File (image or video or audio or other)
$this->attachment(1)->is('image');

// Get Size Of Attachment
$this->attachment(1)->size();
```

### User

```php
// Get User
$user = $this->user(1)->get();
/**
* List of object return:
* 
* $user->ID
* $user->user_login
* $user->user_pass
* $user->user_nicename
* $user->user_email
* $user->user_url
* $user->user_registered
* $user->user_activation_key
* $user->user_status
* $user->display_name
* $user->first_name
* $user->last_name
* $user->caps
* $user->roles
* $user->allcaps
*/

// Get All Meta
$this->user(1)->meta->all();

// Get Custom Meta
$this->user(1)->meta->get('meta_name');

// Save Meta
$this->user(1)->meta->update('phone', '09xxxxxxxx');

// Delete User
$this->user(1)->delete();

// Update User
$this->user(1)->update(['name' => 'Mehrshad Darzi', 'password' => '12345']);

// Add User
$this->user->add(['email' => 'info@site.com', 'username' => 'mehrshad']);

// Get Current User
$this->user->current();

// Check User is Login
$this->user->auth();

// Get current User id
$this->user->id();

// Check User Has Role
$this->user->has_role('administrator');

// Check User Has Capability
$this->user(1)->can('manage_options');

// Check Exist User Id
$this->user->exists(12);

// Login User
$this->user->login($username, $password, $remember = true);

// Authenticate User [Useful for REST-API or Ajax Without set any Cookie]
$this->user->authenticate($username, $password);

// Set New Password For User
$this->user(1)->password->set('new_password');

// Check User Password
$this->user(1)->password->check($this->request->input('password', 'trim'), $hash);

// Convert PlainText Password To Hash
$this->user->password->hash('123456');

// Generate Password With custom length
$this->user->password->generate(8, $special_chars = false);

// Set Role and Capability for User
$user = $this->user(1)->get();
$user->set_role('author');
$user->add_cap('cap_name');
$user->remove_cap('cap_name');
$user->add_role('role_name');
$user->remove_role('role_name');
$user->remove_all_caps();
```

### Term

```php
// Get Term
$this->term(1)->get();

// Get Meta
$this->term(1)->meta->all();

// Save Meta
$this->term(1)->meta->update('key', 'value');

// Delete Term
$this->term(1)->delete();

// Update Term
$this->term(1)->update(['name' => 'New name']);

// Add Term
$this->term->add('term name', ['parent' => 4, 'description' => ''], 'post_tag');

// Get List Terms
$this->term->list(['taxonomy' => 'product_cat', 'return' => 'id']);

// Get All Taxonomies in WordPress
$this->terms->get_taxonomies();
```

### Option

```php
// Get Option
$this->option('name')->get();

// Get default Value if Not Found
$this->option('name')->get($default);

// Get Nested Array Option Value With dot
$this->option('settings.user.id')->get();

// Save Option
$this->option('name')->save('value');

// Delete Options
$this->option('name')->delete();

// Add Option
$this->option->add('name', 'value', 'no');
```

### Comment

```php
// Get Comment
$this->comment(1)->get();

// Get Meta
$this->comment(1)->meta->all();

// Save Meta
$this->comment(1)->meta->update('key', 'value');

// Delete Meta
$this->comment(1)->meta->delete('key');

// Delete Comment
$this->comment(1)->delete();

// Update Comment
$this->comment(1)->update(['name' => 'Ali', 'approved' => true]);

// Add Comment
$this->comment->add(['post_id' => 1, 'name' => 'Mehrshad Darzi', 'content' => '']);

// Get List Comments
$this->comment->list(['post_id' => 1, 'nested' => true]);
```

### Meta

Meta data list: `post`, `user`, `term`, `comment`.

```php
// Get All Meta From Object
$this->post(1)->meta->all();

// Get Custom Meta
$this->user(1)->meta->get('first_name');

// Get Multiple Custom Meta Keys
$this->post(1)->meta->only(['key_1', 'key_2']);

// Get All Meta Key Except Custom keys
$this->post(1)->meta->except(['_edit_lock', '_edit_last']);

// Delete Meta
$this->user(1)->meta->delete('mobile');

// Save Meta
$this->term(1)->meta->save('key', 'value');

// Remove all Meta from Object
$this->comment(1)->meta->clean();
```

### Request

```php
// Get Request fields { GET + POST }
$this->request->input('first_name');

// only `GET` fields
$this->request->query('email');

// Get Field with Custom filter e.g. trim value
$this->request->input('name', 'trim');

// Get field with multiple filter
$this->request->input('post_excerpt', ['trim', 'strip_tags']);

// Check Has exists input
$this->request->has('first_name');

// Check Exist and Not Empty fields
$this->request->filled('first_name');

// Check Exist and is Numeric value
$this->request->numeric('year');

// Check exists fields and Equal with value 
$this->request->equal('first_name', 'mehrshad');

// Check is Numeric value and is positive Number (x >0)
$this->request->numeric('age', true);

// Check value is Enum list
$this->request->enum('post_status', ['publish', 'draft']);

// Get Custom Fields From Request
$this->request->only(['email', 'last_name']);

// Redirect in WordPress
$this->request->redirect('https://google.com', 302);

// Get $_FILES by id
// From Html Form Input: <input type="file" name="image" />
$this->request->file('image'); 

// Check Exists File
$this->request->hasFile('image');

// Get Cookie
$this->request->cookie('name');

// Get $_SERVER params
$this->request->server('REQUEST_URI');

// Check is REST API request
$this->request->is_rest();

// Check is Ajax Request
$this->request->is_ajax();

// Check is CronJob Request
$this->request->is_cron();

// Check is XML-RPC Request
$this->request->is_xmlrpc();

// Check is WP-CLI Request
$this->request->is_cli();

// Get Method Of Request
$this->request->get_method();

// Check Method Of Request {boolean}
$this->request->is_method('PUT');

// Return Json Response
$this->response->json(['data' => 'value'], 200);
```

### Handle Error

```php
$input_email = $this->request->input('email');

// Define new error Handle system
$error = $this->error->new();

if(empty($input_email)) {
    $error->add('empty_email', __('Please Fill Your Email', 'my-plugin'));
}

if(!is_email($input_email)){
    $error->add('valid_email', __('Please Fill valid Email', 'my-plugin'));
}

if($this->error->has($error)){
    return $error; # Or use $error->get_error_messages();
} else {
    return true;
}
```

### Cache and Transient

```php
// Remember Cache last Post in One Hour
$this->cache->remember('latest_post', function(){
    return $this->post->list(['type' => 'product', 'return' => 'id'])
}, 'cache_group_name', $this->constant->hour);

// Delete Cache
$this->cache->delete('cache_name', 'group');

// Add Cache
$this->cache->add('cache_name', $value, 'group_name', 5 * $this->constant->minute);

// Get Cache
$this->cache->get('name', 'group');

// Remember Transient
$this->transient->remember('latest_users', function(){
    return $this->user->list(['role' => 'subscriber', 'return' => 'id'])
}, $this->constant->hour);

// Delete transient
$this->transient->delete('name');

// Add Transient
$this->transient->add('name', $value, $this->constant->day);

// Get Transient
$this->transient->get('name');
```

### REST API

```php
// Get REST API prefix url
$this->rest->prefix();

// get REST API url
$this->rest->url('namespace/endpoint');

// Making WordPress REST API Calls Internally
$this->rest->request('GET', 'wp/v2/posts', [ 'per_page' => 12 ]);

// Define New Custom EndPoint in WordPress REST API
$this->route->add('form', 'contact', [
    'method' => 'post',
    'function' => 'send_form',
    'arg' => [
        'title' => [
            'require' => true,
        ]
    ]
]);
        
// Example new route in WordPress REST API with Trait
class MY_REST_API extends Model
{
    use RestAPI;
    
    public function rest_api_init()
    {
        $this->route->add('student', 'register', [
            'method' => 'post',
            'function' => 'register',
            'arg' => [
                'age' => [
                    'require' => true,
                    'validate' => function ($param, $request, $key) {
                        return is_numeric($param);
                    }
                ],
                'name' => [
                    'require' => true,
                    'sanitize' => function ($param, $request, $key) {
                        return strtolower($param);
                    }
                ]
            ]
        ]);
    }

    public function register($request)
    {
        # Get Params
        $name = $request->get_param('name');
        $age = $request->get_param('age');

        # insert To Database
        $this->db->insert(
            $this->db->prefix . 'student',
            ['name' => $name, 'age' => $age]
        );

        # Result Json
        return $this->response->json(
            ['message' => 'Completed Register', 'id' => $this->db->insert_id],
            200,
            ['X-Custom-Header' => 'value']
        );
    }
}

// Remove Route
$this->route->remove('/wp/v2/posts');

// Get List WordPress REST API routes
$list = $this->route->all();
$list->namespaces;
$list->routes;
```

### Cookie

```php
// set new Cookie for One Hour
$this->cookie->set('user_data', ['name' => 'Mehrshad', 'family' => 'Darzi'], $this->constant->hour);

// Check exist cookie {boolean}
$this->cookie->has('user_data');

// Get cookie Value { auto convert json to Array }
$this->cookie->get('user_data');

// Remove Cookie
$this->cookie->delete('user_data');

// Get All Cookie in WordPress Site
$this->cookie->all();
```

### Session

```php
// set new session
$this->session->set('redirect_from', add_query_arg( 'type', 'error', $this->constant->home ));

// Check exist session {boolean}
$this->session->has('redirect_from');

// Get session Value
$this->session->get('redirect_from');

// Remove Session
$this->session->delete('redirect_from');

// Get All Session in WordPress Site
$this->session->all();

// Get Session ID
$this->session->id();

// Destroy All Sessions
$this->session->destroy();
```

##### How to start session in WordPress?

```php
add_action('init', 'register_session');
public function register_session()
{
    if (session_status() == PHP_SESSION_NONE) {
        session_start([
            'read_and_close' => true
        ]);
    }
}
```

### Event

```php
// Define single Event
$this->event->single($this->constant->week, 'action_name');

// Define recurring Event
$this->event->add(time(), 'hourly', 'action_name', []);

// Delete Event
$this->event->delete('action_name');

// Retrieve supported event recurrence schedules
$this->event->schedules();

// Get List Current CrobJobs
$this->event->list();
```

### Nonce

```php
// Create new Nonce
$this->nonce->create('_my_nonce');

// Verify Nonce Field {boolean}
$this->nonce->verify('input_name', '_my_nonce');

// Generate Html Input Hidden nonce Field for Using form
$this->nonce->input('_my_nonce', 'nonce_input_name');
```

### File System

```php
// Get Example file Path
$path = path_join($this->constant->content_dir, 'object.php');

// Check file exits
$this->file($path)->exists();

// Check file missing
$this->file($path)->missing();

// Get File Content
$this->file($path)->get();

// Delete File
$this->file($path)->delete();

// Create File with Content
$content = '<?php echo "Hi"; ?>';
$this->file($path)->create($content);

// Create Directory
$this->file->mkdir($this->constant->content_dir.'/excel');

// Change Permission File
$this->file($path)->chmod(0600);

// Copy File
$new_path = $this->constant->content_dir.'/backup/object.php';
$this->file($path)->copy($new_path);

// Move File
$this->file($path)->move($new_path);

// Get File Extension
$this->file($path)->extension();

// Get File BaseName
$this->file($path)->basename();

// Get file DirName
$this->file($path)->dirname();

// Get file last Modified
$this->file($path)->lastModified();

// Get file size (bytes)
$this->file($path)->size();
```

List of Methods File Systems
{[See Collection](https://github.com/mehrshaddarzi/wp-trait/blob/master/src/Collection/File.php)}:


### Email

```php
// Send Html Body Mail
$this->email('email@site.com')->send('Subject', '<p>Message Body</p>');

// Send to Multiple Email With Custom Header and Attachment
$headers = [
    'Content-Type: text/html; charset=UTF-8',
    'From: Site name <info@sitename.com>'
];
$attachment = [$this->constant->uploads->basedir.'/file.zip'];
$this->email(['email@site.com', 'mail@domain.com'])
     ->send('Subject', 'Message Body', $headers, $attachment);
```

### Log

```php
// Add text log
# wp-content/debug.log
$this->log('text log', 'debug');

// Add Array log
$this->log(['user_id' => 1, 'status' => true], 'debug');

// Custom Log File
# wp-content/db.log
$this->log('text log', 'db');

// Custom Condition
# By Default when WP_DEBUG_LOG === true
# wp-content/plugin-slug.log
$is_active_plugin_log = get_option('my_plugin_active_log');
$this->log('text log', 'plugin-slug', $is_active_plugin_log);

// Change Datetime in Log File
# By default the dates are saved in the log file based on `UTC`
add_filter('wp_trait_log_date', function ($date, $type) {
    if ($type == "my-plugin-slug") {
        return date_i18n(get_option( 'date_format' ), current_time('timestamp')) . ' UTC+3.5';
    }
    return $date;
});
```

Collections Lists are available
under [/Collection](https://github.com/mehrshaddarzi/wp-trait/tree/master/src/Collection).

## Views Templates

For use template engine in this package, use `Views` Collection.
Create a folder with name `templates` in your plugin and put your php file, for example:

```php
# ~/wp-content/plugins/{plugin-slug}/templates/students/table.php
<p><?= $title; ?></p>
<table>
<tr>
<td><?php _e('Name'); ?></td>
<td><?php _e('Family'); ?></td>
</tr>
  <?php
    foreach($students as $student) {
  ?>
      <tr>
      <td><?php $student['name']; ?></td>
      <td><?php $student['family']; ?></td>
      </tr>
  <?php
    }
  ?>
</table>
```

For Load `students/table.php` files in your Model, use `render` Method:

```php
$data = [
    'title' => 'Students List',
    'students' => [
        ['name' => 'Mehrshad', 'family' => 'Darzi'],
        ['name' => 'John', 'family' => 'Smith'],
    ]
];

echo $this->view->render('students.table', $data);
```

### Overriding templates via a theme

By default, Users who use your plugin can change plugin templates in your active WordPress theme.

```php
# ~/wp-content/themes/twentyeleven/{plugin-slug}/students/table.php
<div class="text-right bg-black text-white"><?= $title; ?></div>
<?php
  foreach($students as $student) {
?>
  <div class="card">
    <div class="card-body">
      <p><?php $student['name']; ?></p>
      <p><?php $student['family']; ?></p>
    </div>
  </div>
<?php
}
?>
```

For disable Overriding templates in custom file, set `false` in render method:

```php
$this->view->render('students.table', $data, [], $canOverride = false);
```

### Set template attribute

There is several Ways for set attribute:

```php
// First Way
$content = $this->view()
  ->attribute('text', $text)
  ->attribute([
      'description' => __('This is the description', $this->plugin->textDomain)
  ])
  ->render('notice');
echo $this->add_alert($content, 'info');

// Second Way
$content = $this->view()->render('notice', [
  'text' => $text
], [
  'text' => __('This is the description', $this->plugin->textDomain)
]);
echo $this->add_alert($content, 'info');

// Third Way
$content = $this->view();
$content->text = $text;
echo $this->add_alert($content('notice'), 'info');

// Property Way
$view = $this->view->render('notice', [
  'text' => $text . ' with property way'
]);
echo $this->add_alert($view, 'info');
```

## Trait For WordPress Hooks

This package has list of php trait for WordPress Hooks, that you can uses.
trait Lists are available under [/Hook](https://github.com/mehrshaddarzi/wp-trait/tree/master/src/Hook).

### How To Work Trait Hooks

1) First add `trait` in your class.

```php
use Init;
```

2) every method in your class that have `init` prefix in method name call in this action:

```php
public function init(){
  // Code Here
}

public function init_check_user_login(){
  // Code Here
}

public function init_save_form_data() {
  // Code Here
}
```

### List Of Trait With Prefix Method Name

<table>

 <tr>
 <td>Usage</td>
 <td>Method Prefix</td>
 <td>Variable option on your model</td>
 </tr>

 <tr> 
 <td>use Init;</td>
 <td>init_</td>
 <td>public $init;</td>
 </tr>

<tr> 
<td>use AdminAssets;</td>
<td>admin_enqueue_scripts_</td>
<td>public $adminAssets;</td>
</tr>

<tr> 
<td>use AdminFooter;</td>
<td>admin_footer_</td>
<td>public $adminFooter;</td>
</tr>

<tr> 
<td>use AdminInit;</td>
<td>admin_init_</td>
<td>public $adminInit;</td>
</tr>

<tr> 
<td>use AdminMenu;</td>
<td>admin_menu_</td>
<td>public $adminMenu;</td>
</tr>

<tr> 
<td>use AdminSearchBox;</td>
<td>get_search_fields_</td>
<td>public $adminSearchBox;</td>
</tr>

<tr> 
<td>use Ajax;</td>
<td>admin_ajax_{$method_name}</td>
<td>public $ajax;</td>
</tr>

<tr> 
<td>use BulkActions;</td>
<td>bulk_actions_ & handle_bulk_actions_</td>
<td>public $bulkActions;</td>
</tr>

<tr> 
<td>use FrontAssets;</td>
<td>wp_enqueue_scripts_</td>
<td>public $frontAssets;</td>
</tr>

<tr> 
<td>use ImageSize;</td>
<td>setup_image_size_</td>
<td>public $imageSize;</td>
</tr>

<tr> 
<td>use Notice;</td>
<td>admin_notices_</td>
<td>public $notice;</td>
</tr>

<tr> 
<td>use PostTypeColumns;</td>
<td>columns_ & content_columns_</td>
<td>public $postTypeColumns;</td>
</tr>

<tr> 
<td>use PreGetQuery;</td>
<td>pre_get_posts_ & pre_get_users_ & pre_get_terms_</td>
<td>public $preGetQuery;</td>
</tr>

<tr> 
<td>use RestAPI;</td>
<td>rest_api_init_</td>
<td>public $restAPI;</td>
</tr>

<tr> 
<td>use RowActions;</td>
<td>row_actions_</td>
<td>public $rowActions;</td>
</tr>

<tr> 
<td>use Shortcode;</td>
<td>add_shortcode_</td>
<td>public $shortcode;</td>
</tr>

<tr> 
<td>use SortableColumns;</td>
<td>sortable_columns_</td>
<td>public $sortableColumns;</td>
</tr>

<tr> 
<td>use TaxonomyColumns;</td>
<td>columns_ & content_columns_</td>
<td>public $taxonomyColumns;</td>
</tr>

<tr> 
<td>use UserColumns;</td>
<td>columns_ & content_columns_</td>
<td>public $userColumns;</td>
</tr>

<tr> 
<td>use UserProfileFields;</td>
<td>admin_user_profile_fields_ & save_admin_user_profile_fields_</td>
<td>public $userProfileFields;</td>
</tr>


<tr> 
<td>use ViewsSub;</td>
<td>views_edit_sub_</td>
<td>public $viewsSub;</td>
</tr>

</table>

### Example Create Ajax Request with Trait

```php
use WPTrait\Hook\Ajax;

class Admin extends Model
{
    use Ajax;

    public $ajax = [
        'methods' => ['signup_user']
    ];

    public function admin_ajax_signup_user()
    {
        # Check User is Auth
        if ($this->user->auth()) {
            $this->response->json(['message' => __('You are a user of the site', 'wp-plugin')], 400);
        }

        # Get Input Email
        $email = $this->request->input('email');

        # Create User
        $user_id = $this->user->add([
            'email' => $email,
            'username' => $email
        ]);
        if ($this->error->has($user_id)) {
            $this->response->json(['message' => $this->error->message($user_id)], 400);
        }

        # Return Success
        $this->response->json(['user_id' => $user_id], 200);
    }
}
```

You can access top ajax request:

```
http://site.com/wp-admin/admin-ajax.php?action=signup_user&email=info@site.com
```

## Utility

### Singleton Design Pattern

For Create a Singleton Design Pattern, use `Singleton` trait in your Class:

```php
// Class which uses singleton trait.
class MyClass {
    use Singleton;
}

// To get the instance of the class.
$instance = MyClass::instance();
```

## Starter Plugin

You Can read example folder ReadMe.md files [/example](https://github.com/mehrshaddarzi/wp-trait/tree/master/example).
and start your project very fast.

## Contributing

- [Mehrshad Darzi](https://www.linkedin.com/in/mehrshaddarzi/)

We appreciate you taking the initiative to contribute to this project.
Contributing isn’t limited to just code. We encourage you to contribute in the way that best fits your abilities, by
writing tutorials, giving a demo at your local meetup, helping other users with their support questions, or revising our
documentation.

## License

The WP-Trait is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).

