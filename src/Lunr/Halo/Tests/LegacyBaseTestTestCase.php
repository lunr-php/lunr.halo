<?php

/**
 * This file contains the LegacyBaseTestTest class.
 *
 * SPDX-FileCopyrightText: Copyright 2013 M2mobi B.V., Amsterdam, The Netherlands
 * SPDX-FileCopyrightText: Copyright 2022 Move Agency Group B.V., Zwolle, The Netherlands
 * SPDX-License-Identifier: MIT
 */

namespace Lunr\Halo\Tests;

use Lunr\Halo\LegacyBaseTest;
use ReflectionClass;

/**
 * This class contains the tests for the unit test base class.
 *
 * @covers Lunr\Halo\LegacyBaseTest
 */
abstract class LegacyBaseTestTestCase extends LegacyBaseTest
{

    /**
     * Instance of a child class.
     * @var MockChildClass
     */
    protected MockChildClass $childClass;

    /**
     * Unit test constructor.
     */
    public function setUp(): void
    {
        $this->childClass = new MockChildClass();

        $this->class      = new MockClass();
        $this->reflection = new ReflectionClass(MockClass::class);
    }

    /**
     * Unit test destructor.
     */
    public function tearDown(): void
    {
        unset($this->childClass);
        unset($this->class);
        unset($this->reflection);
    }

}

?>
