<?php

/**
 * This file contains a mock class.
 *
 * @package    Lunr\Halo
 * @author     Heinz Wiesinger <heinz@m2mobi.com>
 * @copyright  2018, M2Mobi BV, Amsterdam, The Netherlands
 * @license    http://lunr.nl/LICENSE MIT License
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
    const FOOBAR = 'constant';

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
