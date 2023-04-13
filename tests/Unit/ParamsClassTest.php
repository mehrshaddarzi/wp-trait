<?php

namespace WPTraitTests\Unit;

use WPTrait\Abstracts\Params;

class ParamsClassTest extends \PHPUnit\Framework\TestCase
{

    public function test_existClass()
    {
        $this->assertTrue(class_exists('\WPTrait\Abstracts\Params'));
    }

    public function test_existPropertyObjectClass()
    {
        // Create Class fly
        $Params = new class extends Params {
            public function __construct()
            {
            }
        };

        // List Of properties
        $properties = [
            'params'
        ];

        $class = new $Params;
        foreach ($properties as $property) {
            $this->assertTrue(property_exists($class, $property), 'The ' . $property . ' is not exist in Params Class');
        }

        $reflection = new \ReflectionClass($class);
        $property = $reflection->getProperties();
        $this->assertCount(1, $property);
    }

    public function test_setParams()
    {
        // Create Class fly
        $Params = new class extends Params {

            public function __construct()
            {
            }

            public function setParams(): static
            {
                $this->params = [
                    'name' => 'Mehrshad'
                ];
                return $this;
            }
        };

        $class = new $Params;
        $params = $class->toParams();
        $this->assertIsArray($class->getParams());
        $this->assertIsArray($params);
        $this->assertCount(1, $class->getParams());
        $this->assertEquals(['name' => 'Mehrshad'], $class->getParams());
    }

}