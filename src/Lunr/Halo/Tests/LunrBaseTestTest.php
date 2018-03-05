<?php

/**
 * This file contains the LunrBaseTestTest class.
 *
 * PHP Version 5.3
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
     * Instance of a mock class.
     * @var MockClass
     */
    protected $class;

    /**
     * Reflection instance of the LunrBaseTest class.
     * @var ReflectionClass
     */
    protected $reflection;

    /**
     * Unit test constructor.
     */
    public function setUp()
    {
        $this->class      = $this->getMockBuilder('Lunr\Halo\Tests\MockClass')->getMock();
        $this->reflection = new ReflectionClass('Lunr\Halo\Tests\MockClass');
    }

    /**
     * Unit test destructor.
     */
    public function tearDown()
    {
        unset($this->class);
        unset($this->reflection);
    }

}

?>
