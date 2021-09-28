# Fast and standard development of WordPress plugins

![Packagist](https://img.shields.io/github/license/mehrshaddarzi/wp-trait)
![Packagist Version](https://img.shields.io/github/v/release/mehrshaddarzi/wp-trait)
![GitHub repo size](https://img.shields.io/github/repo-size/mehrshaddarzi/wp-trait)

## Installation

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

    public function __construct($slug, $args = array())
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

## Example

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

## Global function

You can access to all classes method with global template function by your plugin slug. for example if your plugin slug is `wp-user-mobile`, you can call method from `Admin` class:

```php
echo wp_user_mobile()->Admin->method_name();
```

this function show `Code is Poetry`.


## Trait For WordPress Hooks

This package has list of php trait for WordPress Hooks, that you can uses.
trait Lists are available under [/Hook](https://github.com/mehrshaddarzi/wp-trait/tree/master/src/Hook).

#### how To Work Trait Hooks?

1) first add trait in your class.

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

// Get Multiple Customer Meta Keys
$this-post(1)->meta->only(['key_1', 'key_2']);

// Save Post Meta
$this->post(1)->meta->save('key', 'value');

// Delete Post
$this->post(1)->delete();

// Get List Of post
$this->post->list(['type' => 'post', 'status' => 'publish', 'cache' => false]);

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

// Check Attachment type File (image or video or audio)
if($this->attachment(1)->is() == "image") { }

// Get Size Of Attachment
$this->attachment(1)->size();
```

### User

```php

// Get User
$user = $this->user(1)->get();
# ['data' => '', 'ID' => '', 'roles' => '', 'allcaps' => '']

// Get Meta
$this->user(1)->meta->all();

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

// Check Exist User Id
$this->user->exists(12);

// Set Role and Capability for user
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

// Save Option
$this->option('name')->update('value');

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

### Request

```php
// Get Request (GET or POST) field
// ?first_name=mehrshad&last_name=darzi&email=info@site.com
$this->request->input('first_name');

// Get Only `GET` fields
$this->request->query('email');

// Check Has input
$this->request->has('first_name');

// Check Equal fields
$this->request->equal('first_name', 'mehrshad');

// Check Exist and Not Empty fields
$this->request->filled('first_name');

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

// New Request
$this->request->new('https://jsonplaceholder.typicode.com/todos/1', 'GET', ['timeout' => 30, 'ssl' => false]);
```

### Handle Error
```php
# Define Error System in your Custom Method

$input_email = $this->request->input('email');
if(empty($input_email)) {
    $error = $this->error->new('empty_email', __('Please Fill Your Email', 'my-plugin'));
}

if(!is_email($input_email)){
    $error->add('valid_email', __('Please Fill valid Email', 'my-plugin'));
}

if($this->error->has($error)){
    return $error; // Or use $error->get_error_messages();
} else {
    return true;
}
```

### Event
```php
// Define single Event
$this->event->single($this->constant('hour'), 'action_name');

// Define recurring Event
$this->event->add(time(), 'hourly', 'action_name', array());

// Delete Event
$this->event->delete('action_name');

// Check Equal fields
$this->request->equal('first_name', 'mehrshad');
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

