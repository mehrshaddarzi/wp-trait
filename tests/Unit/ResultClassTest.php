<?php

namespace WPTraitTests\Unit;

use WPTrait\Abstracts\Result;

class ResultClassTest extends \PHPUnit\Framework\TestCase
{

    public function test_existClass()
    {
        $this->assertTrue(class_exists('\WPTrait\Abstracts\Result'));
    }

    public function test_existPropertyObjectClass()
    {
        // Create Class fly
        $Result = new class extends Result {
            public function __construct()
            {
            }
        };

        // List Of properties
        $properties = [
            'response',
            'params'
        ];

        $class = new $Result;
        foreach ($properties as $property) {
            $this->assertTrue(property_exists($class, $property), 'The ' . $property . ' is not exist in Result Class');
        }

        $reflection = new \ReflectionClass($class);
        $property = $reflection->getProperties();
        $this->assertCount(2, $property);
    }

    public function test_ResultWithError()
    {
        // Create Class fly
        $Result = new class extends Result {

            public function __construct()
            {
                $this->response = $this->example_with_error();
            }

            public function example_with_error(): \WP_Error
            {
                $WP_Error = new \WP_Error();
                $WP_Error->add('invalid_code', 'Your Code is Invalid');
                $WP_Error->add('invalid_name', 'Your Name is Invalid');
                return $WP_Error;
            }
        };

        $class = new $Result;

        $this->assertTrue($class->hasError());
        $this->assertEquals('Your Code is Invalid', $class->getError());
        $this->assertEquals('invalid_code', $class->getErrorCode());
        $this->assertIsArray($class->getErrors());
        $this->assertCount(2, $class->getErrors());
        $this->assertEquals(['Your Code is Invalid', 'Your Name is Invalid'], $class->getErrors());
        $this->assertEquals(['invalid_code', 'invalid_name'], $class->getErrorCodes());
    }

    public function test_ResultWithSuccess()
    {
        // Create Class fly
        $Result = new class extends Result {

            public function __construct()
            {
                $this->response = $this->example_with_success();
            }

            public function example_with_success(): string
            {
                return 'everything is ok';
            }
        };

        $class = new $Result;
        $this->assertFalse($class->hasError());
        $this->assertEquals('', $class->getError());
        $this->assertEquals('', $class->getErrorCode());
        $this->assertIsArray($class->getErrors());
        $this->assertCount(0, $class->getErrors());
        $this->assertEquals([], $class->getErrors());
        $this->assertEquals([], $class->getErrorCodes());
    }
}