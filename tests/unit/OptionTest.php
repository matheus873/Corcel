<?php

use Corcel\Option;
use Illuminate\Support\Collection;

/**
 * Class OptionTest
 *
 * @author Junior Grossi <juniorgro@gmail.com>
 */
class OptionTest extends PHPUnit_Framework_TestCase
{
    /**
     * @test
     */
    public function as_array_method_values()
    {
        factory(Option::class)->create([
            'option_name' => 'foo',
            'option_value' => 'bar',
        ]);

        $options = Option::asArray();
        $expected = ['foo' => 'bar'];

        $this->assertArraySubset($expected, $options);
        $this->assertArrayHasKey('foo', $options);
        $this->assertEquals('bar', $options['foo']);
    }

    /**
     * @test
     */
    public function as_array_method_count()
    {
        factory(Option::class, 2)->create();

        $options = Option::asArray();

        $this->assertTrue(is_array($options));
        $this->assertTrue(count($options) > 0);
    }

    /**
     * @test
     */
    public function option_can_have_serialized_data()
    {
        factory(Option::class)->create([
            'option_name' => 'foo',
            'option_value' => serialize($array = ['foo', 'bar']),
        ]);

        $options = Option::asArray();

        $this->assertArrayHasKey('foo', $options);
        $this->assertInternalType('array', $options['foo']);
        $this->assertContains($array, $options);
        $this->assertArraySubset($array, $options['foo']);
    }

    /**
     * @test
     */
    public function get_method_returns_null_if_not_found()
    {
        $value = Option::get('b03e3fd');

        $this->assertNull($value);
    }

    /**
     * @test
     */
    public function option_has_simple_value_attribute()
    {
        $option = factory(Option::class)->create([
            'option_name' => 'foo',
            'option_value' => 'bar',
        ]);

        $this->assertEquals('bar', $option->value);
    }

    /**
     * @test
     */
    public function option_value_attribute_unserialize_if_necessary()
    {
        $option = factory(Option::class)->create([
            'option_name' => 'foo',
            'option_value' => serialize($array = [1, 2, 3]),
        ]);

        $this->assertEquals($array, $option->value);
    }

    /**
     * @test
     */
    public function option_object_can_be_converted_to_simple_array()
    {
        $option = factory(Option::class)->create([
            'option_name' => 'foo',
            'option_value' => 'bar',
        ]);

        $this->assertArraySubset(['foo' => 'bar'], $option->toArray());
    }

    /**
     * @test
     */
    public function can_add_new_option_using_add_static_method()
    {
        $option = Option::add('foo', 'bar');

        $this->assertEquals('bar', $option->value);
        $this->assertArraySubset(['foo' => 'bar'], $option->toArray());
    }
}
