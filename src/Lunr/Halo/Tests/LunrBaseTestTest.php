<?php

/**
 * This file contains the LunrBaseTestTest class.
 *
 * @package    Lunr\Halo
 * @author     Heinz Wiesinger <heinz@m2mobi.com>
 * @copyright  2013-2018, M2Mobi BV, Amsterdam, The Netherlands
 * @license    http://lunr.nl/LICENSE MIT License
 */

namespace Lunr\Halo\Tests;

use Lunr\Halo\LunrBaseTest;
use ReflectionClass;

/**
 * This class contains the tests for the unit test base class.
 *
 * @covers Lunr\Halo\LunrBaseTest
 */
class LunrBaseTestTest extends LunrBaseTest
{

    /**
     * Instance of a child class.
     * @var MockChildClass
     */
    protected $child_class;

    /**
     * Unit test constructor.
     */
    public function setUp(): void
    {
        $this->child_class = new MockChildClass();

        $this->class      = new MockClass();
        $this->reflection = new ReflectionClass('Lunr\Halo\Tests\MockClass');
    }

    /**
     * Unit test destructor.
     */
    public function tearDown(): void
    {
        unset($this->child_class);
        unset($this->class);
        unset($this->reflection);
    }

}

?>
