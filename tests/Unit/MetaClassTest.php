<?php

namespace WPTraitTests\Unit;

use WPTrait\Data\Meta;
use WPTrait\Data\Post;
use WPTrait\Utils\Arr;

class MetaClassTest extends \PHPUnit\Framework\TestCase
{

    public static array $meta_input = [
        'meta_key_1' => 'meta_value_1',
        'meta_key_2' => ['meta_value_one', 'meta_value_two'],
        'meta_key_3' => ['name' => 'Mehrshad', 'family' => 'Darzi']
    ];

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
        wp_delete_post($this->post_id, true);
    }

    public function test_existClass()
    {
        $this->assertTrue(class_exists('\WPTrait\Data\Meta'));
    }

    public function test_isObjectClass()
    {
        $post = new Meta();
        $this->assertIsObject($post);
    }

    public function test_existPropertyObjectClass()
    {
        $properties = [
            'type',
            'id'
        ];

        $post = new Meta;
        foreach ($properties as $property) {
            $this->assertTrue(property_exists($post, $property), 'The ' . $property . ' is not exist in Meta Class');
        }
    }

    public function test_setupProperty()
    {
        $meta = new Meta(100, 'user');
        $this->assertEquals(100, $meta->id);
        $this->assertEquals('user', $meta->type);
    }

    public function test_getAllMeta()
    {
        $meta = (new Meta($this->post_id, 'post'))->all();

        $this->assertIsArray($meta);
        $this->assertTrue(Arr::isAssoc($meta));
        $this->assertGreaterThanOrEqual(3, $meta);
        $this->assertArrayHasKey('meta_key_1', $meta);
        $this->assertArrayHasKey('meta_key_2', $meta);
        $this->assertArrayHasKey('meta_key_3', $meta);
        $this->assertEquals('meta_value_1', $meta['meta_key_1']);
        $this->assertEquals(['meta_value_one', 'meta_value_two'], $meta['meta_key_2']);
        $this->assertEquals(['name' => 'Mehrshad', 'family' => 'Darzi'], $meta['meta_key_3']);
    }

    public function test_getSingleMeta()
    {
        $meta = (new Meta($this->post_id, 'post'));
        $this->assertEquals('meta_value_1', $meta->get('meta_key_1'));
        $this->assertEquals(['meta_value_one', 'meta_value_two'], $meta->get('meta_key_2'));
        $this->assertEquals(['name' => 'Mehrshad', 'family' => 'Darzi'], $meta->get('meta_key_3'));
    }

    public function test_createMeta()
    {
        $meta = new Meta($this->post_id, 'post');
        $meta->create('_name', 'mehrshad');
        $meta->create('_array', ['name' => 'mehrshad', 'family' => 'darzi']);

        $this->assertEquals('mehrshad', get_post_meta($this->post_id, '_name', true));
        $this->assertEquals($meta->get('_name'), get_post_meta($this->post_id, '_name', true));
        $this->assertEquals(['name' => 'mehrshad', 'family' => 'darzi'], get_post_meta($this->post_id, '_array', true));
        $this->assertEquals($meta->get('_array'), get_post_meta($this->post_id, '_array', true));

        $meta->create([
            '_multi_key_1' => 'multi_value_1',
            '_multi_key_2' => 'multi_value_2',
        ]);
        $this->assertEquals('multi_value_1', get_post_meta($this->post_id, '_multi_key_1', true));
        $this->assertEquals($meta->get('_multi_key_1'), get_post_meta($this->post_id, '_multi_key_1', true));
        $this->assertEquals('multi_value_2', get_post_meta($this->post_id, '_multi_key_2', true));
        $this->assertEquals($meta->get('_multi_key_2'), get_post_meta($this->post_id, '_multi_key_2', true));
    }

    public function test_saveMeta()
    {
        $meta = (new Meta($this->post_id, 'post'));
        $meta->save('_name', 'mehrshad');
        $meta->save('_array', ['name' => 'mehrshad', 'family' => 'darzi']);

        $this->assertEquals('mehrshad', get_post_meta($this->post_id, '_name', true));
        $this->assertEquals($meta->get('_name'), get_post_meta($this->post_id, '_name', true));
        $this->assertEquals(['name' => 'mehrshad', 'family' => 'darzi'], get_post_meta($this->post_id, '_array', true));
        $this->assertEquals($meta->get('_array'), get_post_meta($this->post_id, '_array', true));

        $meta->save([
            '_multi_key_1' => 'multi_value_1',
            '_multi_key_2' => ['one', 'two'],
        ]);
        $this->assertEquals('multi_value_1', get_post_meta($this->post_id, '_multi_key_1', true));
        $this->assertEquals($meta->get('_multi_key_1'), get_post_meta($this->post_id, '_multi_key_1', true));
        $this->assertEquals(['one', 'two'], get_post_meta($this->post_id, '_multi_key_2', true));
        $this->assertEquals($meta->get('_multi_key_2'), get_post_meta($this->post_id, '_multi_key_2', true));
    }

    public function test_existsMeta()
    {
        $meta = (new Meta($this->post_id, 'post'));
        $this->assertTrue($meta->exists('meta_key_1'));
        $this->assertFalse($meta->exists('meta_key_10'));
    }

    public function test_onlyMetaList()
    {
        $meta = (new Meta($this->post_id, 'post'))
            ->only('meta_key_1', 'meta_key_2');

        $this->assertIsArray($meta);
        $this->assertTrue(Arr::isAssoc($meta));
        $this->assertGreaterThanOrEqual(2, $meta);
        $this->assertArrayHasKey('meta_key_1', $meta);
        $this->assertArrayHasKey('meta_key_2', $meta);
        $this->assertEquals('meta_value_1', $meta['meta_key_1']);
        $this->assertEquals(['meta_value_one', 'meta_value_two'], $meta['meta_key_2']);
    }

    public function test_exceptMetaList()
    {
        $meta = (new Meta($this->post_id, 'post'))
            ->except('meta_key_1', 'meta_key_2');

        $this->assertIsArray($meta);
        $this->assertTrue(Arr::isAssoc($meta));
        $this->assertGreaterThanOrEqual(1, $meta);
        $this->assertArrayNotHasKey('meta_key_1', $meta);
        $this->assertArrayNotHasKey('meta_key_2', $meta);
        $this->assertArrayHasKey('meta_key_3', $meta);
        $this->assertEquals(['name' => 'Mehrshad', 'family' => 'Darzi'], $meta['meta_key_3']);
    }

    public function test_deleteMeta()
    {
        $meta = new Meta($this->post_id, 'post');
        $meta->delete('meta_key_1');
        $this->assertArrayNotHasKey('meta_key_1', $meta->all());

        $meta->delete(['meta_key_2', 'meta_key_3']);
        $this->assertArrayNotHasKey('meta_key_2', $meta->all());
        $this->assertArrayNotHasKey('meta_key_3', $meta->all());
    }

    public function test_cleanMeta()
    {
        $meta = (new Meta($this->post_id, 'post'))->clean();

        $this->assertIsArray($meta->all());
        $this->assertEmpty($meta->all());
    }

    public function test_postClassObject()
    {
        $post = Post::find($this->post_id);
        $meta = $post->meta()->all();
        $this->assertGreaterThanOrEqual(3, $meta);
        $this->assertArrayHasKey('meta_key_1', $meta);
        $this->assertArrayHasKey('meta_key_2', $meta);
        $this->assertArrayHasKey('meta_key_3', $meta);
        $this->assertEquals('meta_value_1', $meta['meta_key_1']);
        $this->assertEquals(['name' => 'Mehrshad', 'family' => 'Darzi'], $post->meta()->get('meta_key_3'));
    }
}