<?php

/**
 * This file contains a mock class.
 *
 * SPDX-FileCopyrightText: Copyright 2018 M2mobi B.V., Amsterdam, The Netherlands
 * SPDX-FileCopyrightText: Copyright 2022 Move Agency Group B.V., Zwolle, The Netherlands
 * SPDX-License-Identifier: MIT
 */

namespace Lunr\Halo\Tests;

/**
 * A mock class.
 */
class MockClass
{

    /**
     * A constant.
     * @var string
     */
    public const FOOBAR = 'constant';

    /**
     * Another constant.
     * @var string
     */
    protected const BARFOO = 'constant';

    /**
     * Protected property.
     * @var string
     */
    protected $foo;

    /**
     * Constructor.
     */
    public function __construct()
    {
        $this->foo = 'bar';
    }

    /**
     * Destructor.
     */
    public function __destruct()
    {
        unset($this->foo);
    }

    /**
     * A method returning a constant value.
     *
     * @return string String value
     */
    public function constant(): string
    {
        return self::BARFOO;
    }

    /**
     * A public method.
     *
     * @return string String value
     */
    public function baz(): string
    {
        return 'string';
    }

    /**
     * Another public method.
     *
     * @param string $string String value
     *
     * @return string String value
     */
    public function baz2(string $string): string
    {
        return $string;
    }

}

?>
