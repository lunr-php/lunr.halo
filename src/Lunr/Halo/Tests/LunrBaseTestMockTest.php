<?php

/**
 * This file contains the LunrBaseTestMockTest class.
 *
 * SPDX-FileCopyrightText: Copyright 2018 M2mobi B.V., Amsterdam, The Netherlands
 * SPDX-FileCopyrightText: Copyright 2022 Move Agency Group B.V., Zwolle, The Netherlands
 * SPDX-License-Identifier: MIT
 */

namespace Lunr\Halo\Tests;

/**
 * This class contains the tests for the unit test base class.
 *
 * @covers Lunr\Halo\LunrBaseTest
 */
class LunrBaseTestMockTest extends LunrBaseTestTest
{

    /**
     * Test mock_function()
     *
     * @covers Lunr\Halo\LunrBaseTest::mock_function()
     */
    public function testMockFunction(): void
    {
        $this->mock_function('is_int', function (){ return 'Nope!'; });

        $this->assertEquals('Nope!', is_int(1));

        $this->unmock_function('is_int');
    }

    /**
     * Test unmock_function()
     *
     * @covers Lunr\Halo\LunrBaseTest::unmock_function()
     */
    public function testUnmockFunction(): void
    {
        $this->mock_function('is_int', function (){ return 'Nope!'; });

        $this->assertEquals('Nope!', is_int(1));

        $this->unmock_function('is_int');

        $this->assertTrue(is_int(1));
    }

    /**
     * Test mock_method()
     *
     * @covers Lunr\Halo\LunrBaseTest::mock_method()
     */
    public function testMockMethod(): void
    {
        $class = new MockClass();

        $this->mock_method([ $class, 'baz' ], function (){ return 'Nope!'; });

        $this->assertEquals('Nope!', $class->baz());

        $this->unmock_method([ $class, 'baz' ]);
    }

    /**
     * Test mock_method()
     *
     * @covers Lunr\Halo\LunrBaseTest::mock_method()
     */
    public function testMockMethodFromObject(): void
    {
        $this->mock_method([ $this->class, 'baz' ], function (){ return 'Nope!'; });

        $this->assertEquals('Nope!', $this->class->baz());

        $this->unmock_method([ $this->class, 'baz' ]);
    }

    /**
     * Test mock_method()
     *
     * @covers Lunr\Halo\LunrBaseTest::mock_method()
     */
    public function testMockMethodFromParent(): void
    {
        $this->mock_method([ $this->child_class, 'baz' ], function (){ return 'Nope!'; });

        $this->assertEquals('Nope!', $this->class->baz());

        $this->unmock_method([ $this->child_class, 'baz' ]);
    }

    /**
     * Test unmock_method()
     *
     * @covers Lunr\Halo\LunrBaseTest::unmock_method()
     */
    public function testUnmockMethod(): void
    {
        $class = new MockClass();

        $this->mock_method([ $class, 'baz' ], function (){ return 'Nope!'; });

        $this->assertEquals('Nope!', $class->baz());

        $this->unmock_method([ $class, 'baz' ]);

        $this->assertEquals('string', $class->baz());
    }

    /**
     * Test unmock_method()
     *
     * @covers Lunr\Halo\LunrBaseTest::unmock_method()
     */
    public function testUnmockMethodFromObject(): void
    {
        if (phpversion('uopz')[0] < 6)
        {
            $this->markTestSkipped('This functionality requires uopz >= 6.0.x to work correctly');
        }

        $this->mock_method([ $this->class, 'baz' ], function (){ return 'Nope!'; });

        $this->assertEquals('Nope!', $this->class->baz());

        $this->unmock_method([ $this->class, 'baz' ]);

        $this->assertEquals('string', $this->class->baz());
    }

    /**
     * Test unmock_method()
     *
     * @covers Lunr\Halo\LunrBaseTest::unmock_method()
     */
    public function testUnmockMethodFromParent(): void
    {
        if (phpversion('uopz')[0] < 6)
        {
            $this->markTestSkipped('This functionality requires uopz >= 6.0.x to work correctly');
        }

        $this->mock_method([ $this->child_class, 'baz' ], function (){ return 'Nope!'; });

        $this->assertEquals('Nope!', $this->class->baz());

        $this->unmock_method([ $this->child_class, 'baz' ]);

        $this->assertEquals('string', $this->class->baz());
    }

    /**
     * Test constant_redefine()
     *
     * @covers Lunr\Halo\LunrBaseTest::constant_redefine()
     */
    public function testConstantRedefineWithPublicConstant(): void
    {
        $this->assertSame('constant', $this->class::FOOBAR);

        $this->constant_redefine('Lunr\Halo\Tests\MockClass::FOOBAR', 'new value');

        $class = new MockClass();

        $this->assertSame('new value', $class::FOOBAR);

        $this->constant_redefine('Lunr\Halo\Tests\MockClass::FOOBAR', 'constant');

        $class = new MockClass();

        $this->assertSame('constant', $class::FOOBAR);
    }

    /**
     * Test constant_redefine()
     *
     * @covers Lunr\Halo\LunrBaseTest::constant_redefine()
     */
    public function testConstantRedefineWithProtectedConstant(): void
    {
        $this->assertSame('constant', $this->class->constant());

        $constant = $this->reflection->getConstant('BARFOO');

        $this->assertSame('constant', $constant);

        $this->constant_redefine('Lunr\Halo\Tests\MockClass::BARFOO', 'new value');

        // https://github.com/krakjoe/uopz/issues/111
        //$this->assertSame('new value', $this->class->constant());

        $constant = $this->reflection->getConstant('BARFOO');

        $this->assertSame('new value', $constant);

        $this->constant_redefine('Lunr\Halo\Tests\MockClass::BARFOO', 'constant');

        $this->assertSame('constant', $this->class->constant());

        $constant = $this->reflection->getConstant('BARFOO');

        $this->assertSame('constant', $constant);
    }

    /**
     * Test constant_redefine() with a global constant
     *
     * @covers Lunr\Halo\LunrBaseTest::constant_redefine()
     */
    public function testGlobalConstantRedefine(): void
    {
        define('FOOBAR', 'constant');
        $this->assertSame('constant', FOOBAR);

        $this->constant_redefine('FOOBAR', 'new value');

        $this->assertSame('new value', FOOBAR);

        $this->constant_redefine('FOOBAR', 'constant');

        $this->assertSame('constant', FOOBAR);
    }

    /**
     * Test constant_undefine()
     *
     * @covers Lunr\Halo\LunrBaseTest::constant_undefine()
     */
    public function testConstantUndefineWithPublicConstant(): void
    {
        $this->assertSame('constant', $this->class::FOOBAR);

        $this->constant_undefine('Lunr\Halo\Tests\MockClass::FOOBAR');

        $this->assertFalse(defined('Lunr\Halo\Tests\MockClass::FOOBAR'));
    }

    /**
     * Test constant_undefine()
     *
     * @covers Lunr\Halo\LunrBaseTest::constant_undefine()
     */
    public function testConstantUndefineWithProtectedConstant(): void
    {
        $this->assertSame('constant', $this->class->constant());

        $constant = $this->reflection->getConstant('BARFOO');

        $this->assertSame('constant', $constant);

        $this->constant_undefine('Lunr\Halo\Tests\MockClass::BARFOO');

        $this->assertFalse(defined('Lunr\Halo\Tests\MockClass::BARFOO'));
    }

    /**
     * Test constant_undefine() with a global constant
     *
     * @covers Lunr\Halo\LunrBaseTest::constant_undefine()
     */
    public function testGlobalConstantUndefine(): void
    {
        $this->assertSame('constant', FOOBAR);

        $this->constant_undefine('FOOBAR');

        $this->assertFalse(defined('FOOBAR'));
    }

}

?>
