<?php

namespace WPTraitTests\Unit;

use WPTrait\Data\Post;

class PostClassTest extends \PHPUnit\Framework\TestCase
{

    public static array $meta_input = [
        'meta_key_1' => 'meta_value_1',
        'meta_key_2' => 'meta_value_2'
    ];

    public array $post_ids = [];

    public int $post_id = 0;

    public static function setUpBeforeClass(): void
    {

    }

    public static function tearDownAfterClass(): void
    {

    }

    public function setUp(): void
    {
        $this->post_id = wp_insert_post([
            'post_title' => 'Sample Post',
            'post_content' => 'This is just some sample post content.',
            'post_type' => 'post',
            'meta_input' => self::$meta_input,
            'post_status' => 'publish',
            'post_author' => 1
        ]);
    }

    public function tearDown(): void
    {
        foreach (array_filter(array_merge($this->post_ids, [$this->post_id])) as $post_id) {
            if (is_int($post_id) and $post_id > 0) {
                wp_delete_post($post_id, true);
            }
        }
    }

    public function test_existClass()
    {
        $this->assertTrue(class_exists('\WPTrait\Data\Post'));
    }

    public function test_extendClass()
    {
        $post = new Post();
        $this->assertInstanceOf('\WPTrait\Abstracts\Data', $post);
    }

    public function test_isObjectClass()
    {
        $post = new Post;
        $this->assertIsObject($post);
    }

    public function test_existPropertyObjectClass()
    {
        $properties = [
            'author',
            'date',
            'date_gmt',
            'modified',
            'modified_gmt',
            'content',
            'content_filtered',
            'title',
            'excerpt',
            'status',
            'type',
            'comment_status',
            'ping_status',
            'password',
            'slug',
            'parent',
            'menu_order',
            'mime_type',
            'guid',
            'comment_count',
            'template',

            # Extended
            'meta',
            'meta_type',
            'id',
            'original',
            'changed',
            'params'
        ];

        $post = new Post;
        foreach ($properties as $property) {
            $this->assertTrue(property_exists($post, $property), 'The ' . $property . ' is not exist in Post Class');
        }
    }

    private function setupNewPost(): Post
    {
        $post = new Post;
        $time = '2024-10-02 10:21:34';
        $post->author(1)
            ->date($time)
            ->date_gmt($time)
            ->modified($time)
            ->modified_gmt($time)
            ->content('lorem')
            ->content_filtered('lorem with content filtered')
            ->title('post title')
            ->excerpt('post excerpt')
            ->status('publish')
            ->type('post')
            ->comment_status(false)
            ->ping_status(true)
            ->slug('post-slug')
            ->parent(100)
            ->menu_order(200)
            ->mime_type('image/jpeg')
            ->guid('https://google.com')
            ->template('page_template_name')
            ->meta(self::$meta_input);

        return $post;
    }

    public function test_setupProperty()
    {
        $post = $this->setupNewPost();
        $this->assertEquals(1, $post->author);
        $this->assertEquals('2024-10-02 10:21:34', $post->date);
        $this->assertEquals('2024-10-02 10:21:34', $post->date_gmt);
        $this->assertEquals('2024-10-02 10:21:34', $post->modified);
        $this->assertEquals('2024-10-02 10:21:34', $post->modified_gmt);
        $this->assertEquals('lorem', $post->content);
        $this->assertEquals('lorem with content filtered', $post->content_filtered);
        $this->assertEquals('post title', $post->title);
        $this->assertEquals('post excerpt', $post->excerpt);
        $this->assertEquals('publish', $post->status);
        $this->assertEquals('post', $post->type);
        $this->assertEquals('closed', $post->comment_status);
        $this->assertEquals('open', $post->ping_status);
        $this->assertEquals('post-slug', $post->slug);
        $this->assertEquals(100, $post->parent);
        $this->assertEquals(200, $post->menu_order);
        $this->assertEquals('image/jpeg', $post->mime_type);
        $this->assertEquals('https://google.com', $post->guid);
        $this->assertEquals('page_template_name', $post->template);
        $this->assertEquals(self::$meta_input, $post->meta);
    }

    public function test_setupPropertyWasChanged()
    {
        $post = new Post;
        $time = current_time('mysql');
        $post->date($time);
        $this->assertTrue($post->wasChanged('date'));
    }

    public function test_setParamsBeforeInsert()
    {
        $post = $this->setupNewPost();
        $post->setParams();
        $params = $post->getParams();

        $this->assertNotEmpty($params);
        $this->assertIsArray($params);
        $this->assertArrayNotHasKey('ID', $params);

        $arrayKeys = [
            'post_content',
            'post_content_filtered',
            'post_title',
            'post_excerpt',
            'post_status',
            'post_password',
            'post_name',
            'post_parent',
            'menu_order',
            'post_mime_type',
            'guid',
            'post_type',
            'post_author',
            'post_date',
            'post_date_gmt',
            'post_modified',
            'post_modified_gmt',
            'comment_status',
            'ping_status',
            'meta_input',
        ];
        foreach ($arrayKeys as $key) {
            $this->assertArrayHasKey($key, $params);
        }
    }

    public function test_toArray()
    {
        $post = $this->setupNewPost();
        $post->setParams();

        $array = $post->toArray();
        $this->assertCount(22, array_keys($array));

        $properties = [
            'author',
            'date',
            'date_gmt',
            'modified',
            'modified_gmt',
            'content',
            'content_filtered',
            'title',
            'excerpt',
            'status',
            'type',
            'comment_status',
            'ping_status',
            'password',
            'slug',
            'parent',
            'menu_order',
            'mime_type',
            'guid',
            'comment_count',
            'template',
            'id',
        ];
        foreach ($properties as $property) {
            $this->assertArrayHasKey($property, $array);
        }
    }

    public function test_createPostInDatabase()
    {
        $post = $this
            ->setupNewPost()
            ->save(false);

        $this->assertIsInt($post->id);
        $this->assertGreaterThan(0, $post->id);

        $wp_post = get_post($post->id);

        $list = [
            'author' => 'post_author',
            'date' => 'post_date',
            'date_gmt' => 'post_date_gmt',
            'modified' => 'post_modified',
            'modified_gmt' => 'post_modified_gmt',
            'content' => 'post_content',
            'content_filtered' => 'post_content_filtered',
            'title' => 'post_title',
            'excerpt' => 'post_excerpt',
            'status' => 'post_status',
            'type' => 'post_type',
            'comment_status' => 'comment_status',
            'ping_status' => 'ping_status',
            'password' => 'post_password',
            'slug' => 'post_name',
            'parent' => 'post_parent',
            'menu_order' => 'menu_order',
            'mime_type' => 'post_mime_type',
            'guid' => 'guid',
            'template' => 'page_template',
            'comment_count' => 'comment_count',
        ];
        foreach ($list as $postClassKey => $WP_PostClassKey) {
            $this->assertEquals($post->{$postClassKey}, $wp_post->{$WP_PostClassKey}, 'The ' . $postClassKey . ' not equal with ' . $WP_PostClassKey . ' after insert');
        }

        $this->post_ids[] = $post->id;
    }

    public function test_existsPost()
    {
        $this->assertTrue(Post::exists($this->post_id));
    }

    public function test_getDataWithObject()
    {
        $post = (new Post($this->post_id))->get();
        $this->assertEquals($post->id, $this->post_id);
        $this->assertEquals('Sample Post', $post->title);
    }

    public function test_getDataWithFind()
    {
        $post = Post::find($this->post_id);
        $this->assertEquals($post->id, $this->post_id);
        $this->assertEquals('Sample Post', $post->title);
    }

    public function test_getDataWithFindOrFunction()
    {
        $post = Post::findOr(0, function () {
            return 'No Post Found';
        });
        $this->assertEquals('No Post Found', $post);
    }

    public function test_updatePostInDatabase()
    {
        $post = Post::find($this->post_id)
            ->title('new title')
            ->content('new content')
            ->save();

        $this->assertEquals($this->post_id, $post->id);
        $this->assertEquals('new title', $post->title);
        $this->assertEquals('new content', $post->content);
    }

    public function test_getOriginal()
    {
        $wp_post = get_post($this->post_id);

        $post = (new Post($this->post_id))
            ->title('secondary title');
        $this->assertNotEquals($wp_post->post_title, $post->getOriginal('title'));

        $obj = Post::find($this->post_id)
            ->content('secondary content');
        $this->assertEquals($wp_post->post_content, $obj->getOriginal('content'));
    }

    public function test_trashPost()
    {
        $post = Post::find($this->post_id)->trash();
        $this->assertEquals('trash', $post->status);

        $wp_post = get_post($this->post_id);
        $this->assertEquals('trash', $wp_post->post_status);
    }

    public function test_restorePost()
    {
        $post = Post::find($this->post_id)
            ->trash()
            ->restore();
        $this->assertNotEquals('trash', $post->status);

        $wp_post = get_post($this->post_id);
        $this->assertNotEquals('trash', $wp_post->post_status);
    }

    public function test_deletePost()
    {
        $post = Post::find($this->post_id)
            ->delete();
        $this->assertTrue($post);

        $wp_post = get_post($this->post_id);
        $this->assertNull($wp_post);

        $obj = Post::exists($this->post_id);
        $this->assertEquals(false, $obj);
    }

    public function test_permalinkPost()
    {
        $url = Post::find($this->post_id)
            ->permalink();
        $this->assertEquals(esc_url_raw($url), $url);
    }
}