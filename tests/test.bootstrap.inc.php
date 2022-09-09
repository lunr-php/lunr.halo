<?php

/**
 * PHPUnit bootstrap file.
 *
 * Set include path and initialize autoloader.
 *
 * @package   Lunr\Halo
 * @author    Heinz Wiesinger <heinz@m2mobi.com>
 * @copyright 2011-2018, M2Mobi BV, Amsterdam, The Netherlands
 * @license   http://lunr.nl/LICENSE MIT License
 */

$base = __DIR__ . '/..';

set_include_path(
    $base . '/src:' .
    $base . '/config:' .
    $base . '/system:' .
    $base . '/tests/statics:' .
    $base . '/tests/statics/Core:' .
    get_include_path()
);

if (file_exists($base . '/vendor/autoload.php') == TRUE)
{
    // Load composer autoloader.
    require_once $base . '/vendor/autoload.php';
}
else
{
    // Load decomposer autoloader.
    require_once $base . '/decomposer.autoload.inc.php';
}

if (defined('TEST_STATICS') === FALSE)
{
    define('TEST_STATICS', __DIR__ . '/statics');
}

?>
