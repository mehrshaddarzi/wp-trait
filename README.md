# Fast and standard development of WordPress plugins

![Packagist](https://img.shields.io/github/license/mehrshaddarzi/wp-trait)
![Packagist Version](https://img.shields.io/github/v/release/mehrshaddarzi/wp-trait)
![GitHub repo size](https://img.shields.io/github/repo-size/mehrshaddarzi/wp-trait)

WP-Trait is an easy framework for Standard and Fast development of WordPress plugins according php MVC model.

## Table of Contents

- [Installation](#installation)
  * [install with WP-CLI](#install-with-wp-cli)
  * [install with Composer](#install-with-composer)
- [Create New Model](#create-new-model)
  * [Generate Model in Command Line](#generate-model-in-command-line)
    + [Generate New Post-Type Model](#generate-new-post-type-model)
    + [Generate New Taxonomy Model](#generate-new-taxonomy-model)
  * [Generate Model manually](#generate-model-manually)
- [Global Function](#global-function)
  * [How to Change Global variable and function name](#how-to-change-global-variable-and-function-name)
- [Trait For WordPress Hooks](#trait-for-wordpress-hooks)
  * [How To Work Trait Hooks](#how-to-work-trait-hooks)
  * [List Of Trait With Prefix Method Name](#list-of-trait-with-prefix-method-name)
  * [Example Create Ajax Request with Trait](#example-create-ajax-request-with-trait)
- [Collection Class](#collection-class)
  * [Post](#post)
  * [Attachment](#attachment)
  * [User](#user)
  * [Term](#term)
  * [Option](#option)
  * [Comment](#comment)
  * [Meta](#meta)
  * [Request](#request)
  * [Handle Error](#handle-error)
  * [Cache and Transient](#cache-and-transient)
  * [REST API](#rest-api)
  * [Event](#event)
  * [Log](#log)
- [Starter Plugin](#starter-plugin)
- [Contributing](#contributing)
- [License](#license)

## Installation

### install with WP-CLI

You Can Generate new plugin with `Wp-Trait` Structure:

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
wp make model <class>
```

For Example:

```console
wp make model Option
```

or

```console
wp make model User\Register
```

#### Generate New Post-Type Model

```console
wp make post-type Order
```

#### Generate New Taxonomy Model

```console
wp make taxonomy City
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
        $text = __('This Notice is a example from your plugin', $this->plugin->textdomain);
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
            $this->request->json(['message' => __('You are a user of the site', 'wp-plugin')], 400);
        }

        # Get Input Email
        $email = $this->request->input('email');

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
```

You can access top ajax request:

```
http://site.com/wp-admin/admin-ajax.php?action=signup_user&email=info@site.com
```

## Collection Class

This package has list of wordpress helper class, that you can uses.


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

// Save Meta
$this->term(1)->meta->save('key', 'value');

// Remove all Meta from Object
$this->comment(1)->meta->clean();
```

### Request

```php
// Get Request (GET or POST) field
# ?first_name=mehrshad&last_name=darzi&email=info@site.com&age=29
$this->request->input('first_name');

// Get Only `GET` fields
$this->request->query('email');

// Get Field with Custom filter e.g. trim value
$this->request->input('name', 'trim');

// Get field with multiple filter
$this->request->input('post_excerpt', ['trim', 'strip_tags']);

// Check Has input
$this->request->has('first_name');

// Check Equal fields
$this->request->equal('first_name', 'mehrshad');

// Check Exist and Not Empty fields
$this->request->filled('first_name');

// Check Exist and is Numeric value
$this->request->numeric('age');

// Get Custom Fields From Request
$this->request->only(['email', 'last_name']);

// Redirect in WordPress
$this->request->redirect('https://google.com', 302);

// Get $_FILES by id
$this->request->file('image'); //<input type="file" name="image" />

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

// New Request
$request = $this->request->new(
    'https://jsonplaceholder.typicode.com/todos/1',
    'GET',
    [
        'timeout' => 30,
        'ssl' => false,
        'headers' => [
            'Content-Type' => 'application/json',
        ]
    ]
);

if(!$this->error->has($request)) {
    return $request;
    # $request is an array:
    [
        'headers' => '', 
        'body' => '', 
        'response' => ['code' => '', 'message' => ''], 
        'cookies' => '', 
        'http_response' => ''
    ]
}

// Return Json Response
$this->request->json(['data' => 'value'], 200);
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
}, 'cache_group_name', $this->constant('hour'));

// Delete Cache
$this->cache->delete('cache_name', 'group');

// Add Cache
$this->cache->add('cache_name', $value, 'group_name', 5 * $this->constant('minute'));

// Get Cache
$this->cache->get('name', 'group');

// Remember Transient
$this->transient->remember('latest_users', function(){
    return $this->user->list(['role' => 'subscriber', 'return' => 'id'])
}, $this->constant('hour'));

// Delete transient
$this->transient->delete('name');

// Add Transient
$this->transient->add('name', $value, $this->constant('day'));

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

// Define new route in WordPress REST API with trait
class MY_REST_API extends Model
{
    use RestAPI;
    
    public function rest_api_init()
    {
        $this->route->add('student', 'register', [
            'method' => 'post',
            'function' => [$this, 'register'],
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
        return $this->request->json(
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

### Event
```php
// Define single Event
$this->event->single($this->constant('hour'), 'action_name');

// Define recurring Event
$this->event->add(time(), 'hourly', 'action_name', []);

// Delete Event
$this->event->delete('action_name');

// Retrieve supported event recurrence schedules
$this->event->schedules();

// Get List Current CrobJobs
$this->event->list();
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
```

Collections Lists are available under [/Collection](https://github.com/mehrshaddarzi/wp-trait/tree/master/src/Collection).

## Starter Plugin

You Can read example folder ReadMe.md files [/example](https://github.com/mehrshaddarzi/wp-trait/tree/master/example). and start your project very fast.

## Contributing

- [Mehrshad Darzi](https://www.linkedin.com/in/mehrshaddarzi/)

We appreciate you taking the initiative to contribute to this project.
Contributing isn’t limited to just code. We encourage you to contribute in the way that best fits your abilities, by writing tutorials, giving a demo at your local meetup, helping other users with their support questions, or revising our documentation.

## License

The WP-Trait is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).

