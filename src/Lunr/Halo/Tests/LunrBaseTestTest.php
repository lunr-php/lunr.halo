<?php

/**
 * This file contains the LunrBaseTestTest class.
 *
 * SPDX-FileCopyrightText: Copyright 2013 M2mobi B.V., Amsterdam, The Netherlands
 * SPDX-FileCopyrightText: Copyright 2022 Move Agency Group B.V., Zwolle, The Netherlands
 * SPDX-License-Identifier: MIT
 */

namespace Lunr\Halo\Tests;

use Lunr\Halo\LunrBaseTest;

/**
 * This class contains the tests for the unit test base class.
 *
 * @covers Lunr\Halo\LunrBaseTest
 */
abstract class LunrBaseTestTest extends LunrBaseTest
{

    /**
     * Instance of the tested class.
     * @var MockClass
     */
    protected MockClass $class;

    /**
     * Instance of a child class.
     * @var MockChildClass
     */
    protected MockChildClass $child_class;

    /**
     * Unit test constructor.
     */
    public function setUp(): void
    {
        $this->child_class = new MockChildClass();

        $this->class = new MockClass();

        parent::baseSetUp($this->class);
    }

    /**
     * Unit test destructor.
     */
    public function tearDown(): void
    {
        parent::tearDown();

        unset($this->child_class);
        unset($this->class);
    }

}

?>
