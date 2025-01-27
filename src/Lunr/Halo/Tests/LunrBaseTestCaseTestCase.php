<?php

/**
 * This file contains the LunrBaseTestCaseTestCase class.
 *
 * SPDX-FileCopyrightText: Copyright 2013 M2mobi B.V., Amsterdam, The Netherlands
 * SPDX-FileCopyrightText: Copyright 2022 Move Agency Group B.V., Zwolle, The Netherlands
 * SPDX-License-Identifier: MIT
 */

namespace Lunr\Halo\Tests;

use Lunr\Halo\LunrBaseTestCase;

/**
 * This class contains the tests for the unit test base class.
 *
 * @covers Lunr\Halo\LunrBaseTestCase
 */
abstract class LunrBaseTestCaseTestCase extends LunrBaseTestCase
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
    protected MockChildClass $childClass;

    /**
     * Unit test constructor.
     */
    public function setUp(): void
    {
        $this->childClass = new MockChildClass();

        $this->class = new MockClass();

        parent::baseSetUp($this->class);
    }

    /**
     * Unit test destructor.
     */
    public function tearDown(): void
    {
        parent::tearDown();

        unset($this->childClass);
        unset($this->class);
    }

}

?>
