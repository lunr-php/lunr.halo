<?php

/**
 * This file contains the LunrBaseTestReflectionTest class.
 *
 * @package    Lunr\Halo
 * @author     Heinz Wiesinger <heinz@m2mobi.com>
 * @copyright  2018, M2Mobi BV, Amsterdam, The Netherlands
 * @license    http://lunr.nl/LICENSE MIT License
 */

namespace Lunr\Halo\Tests;

/**
 * This class contains the tests for the unit test base class.
 *
 * @covers Lunr\Halo\LunrBaseTest
 */
class LunrBaseTestReflectionTest extends LunrBaseTestTest
{

    /**
     * Test get_accessible_reflection_method()
     *
     * @covers Lunr\Halo\LunrBaseTest::get_accessible_reflection_method
     */
    public function testGetAccessibleReflectionMethod()
    {
        $method = $this->get_accessible_reflection_method('baz');

        $this->assertInstanceOf('ReflectionMethod', $method);
        $this->assertEquals('baz', $method->name);
    }

    /**
     * Test get_accessible_reflection_property()
     *
     * @covers Lunr\Halo\LunrBaseTest::get_accessible_reflection_property
     */
    public function testGetAccessibleReflectionProperty()
    {
        $property = $this->get_accessible_reflection_property('foo');

        $this->assertInstanceOf('ReflectionProperty', $property);
        $this->assertEquals('foo', $property->name);
    }

    /**
     * Test get_reflection_property_value()
     *
     * @covers Lunr\Halo\LunrBaseTest::get_reflection_property_value
     */
    public function testGetReflectionPropertyValue()
    {
        $value = $this->get_reflection_property_value('foo');

        $this->assertEquals('bar', $value);
    }

    /**
     * Test set_reflection_property_value()
     *
     * @covers Lunr\Halo\LunrBaseTest::set_reflection_property_value
     */
    public function testSetReflectionPropertyValue()
    {
        $this->set_reflection_property_value('foo', 'foo');

        $value = $this->get_reflection_property_value('foo');

        $this->assertEquals('foo', $value);
    }

}

?>
