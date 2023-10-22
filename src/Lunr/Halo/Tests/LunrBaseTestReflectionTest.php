<?php

/**
 * This file contains the LunrBaseTestReflectionTest class.
 *
 * SPDX-FileCopyrightText: Copyright 2018 M2mobi B.V., Amsterdam, The Netherlands
 * SPDX-FileCopyrightText: Copyright 2022 Move Agency Group B.V., Zwolle, The Netherlands
 * SPDX-License-Identifier: MIT
 */

namespace Lunr\Halo\Tests;

use ReflectionMethod;
use ReflectionProperty;

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
    public function testGetAccessibleReflectionMethod(): void
    {
        $method = $this->get_accessible_reflection_method('baz');

        $this->assertInstanceOf(ReflectionMethod::class, $method);
        $this->assertEquals('baz', $method->name);
    }

    /**
     * Test get_reflection_method()
     *
     * @covers Lunr\Halo\LunrBaseTest::get_reflection_method
     */
    public function testGetReflectionMethod(): void
    {
        $method = $this->get_reflection_method('baz');

        $this->assertInstanceOf(ReflectionMethod::class, $method);
        $this->assertEquals('baz', $method->name);
    }

    /**
     * Test get_accessible_reflection_property()
     *
     * @covers Lunr\Halo\LunrBaseTest::get_accessible_reflection_property
     */
    public function testGetAccessibleReflectionProperty(): void
    {
        $property = $this->get_accessible_reflection_property('foo');

        $this->assertInstanceOf(ReflectionProperty::class, $property);
        $this->assertEquals('foo', $property->name);
    }

    /**
     * Test get_reflection_property()
     *
     * @covers Lunr\Halo\LunrBaseTest::get_reflection_property
     */
    public function testGetReflectionProperty(): void
    {
        $property = $this->get_reflection_property('foo');

        $this->assertInstanceOf(ReflectionProperty::class, $property);
        $this->assertEquals('foo', $property->name);
    }

    /**
     * Test get_reflection_property_value()
     *
     * @covers Lunr\Halo\LunrBaseTest::get_reflection_property_value
     */
    public function testGetReflectionPropertyValue(): void
    {
        $value = $this->get_reflection_property_value('foo');

        $this->assertEquals('bar', $value);
    }

    /**
     * Test set_reflection_property_value()
     *
     * @covers Lunr\Halo\LunrBaseTest::set_reflection_property_value
     */
    public function testSetReflectionPropertyValue(): void
    {
        $this->set_reflection_property_value('foo', 'foo');

        $value = $this->get_reflection_property_value('foo');

        $this->assertEquals('foo', $value);
    }

}

?>
