<?php

/**
 * PHPStan bootstrap file.
 *
 * Set include path and initialize autoloader.
 *
 * SPDX-FileCopyrightText: Copyright 2022 Move Agency Group B.V., Zwolle, The Netherlands
 * SPDX-License-Identifier: MIT
 */

$base = __DIR__ . '/..';

if (file_exists($base . '/vendor/autoload.php') == TRUE)
{
    // Load composer autoloader.
    $autoload_file = $base . '/vendor/autoload.php';
}
else
{
    // Load decomposer autoloader.
    $autoload_file = $base . '/decomposer.autoload.inc.php';
}

require_once $autoload_file;

if (file_exists($base . '/vendor/autoload.php') == FALSE)
{
    include_once 'Framework/MockObject/Runtime/Interface/Stub.php';
    include_once 'Framework/MockObject/Runtime/Interface/MockObject.php';
    include_once 'Framework/Assert.php';
    include_once 'Framework/Reorderable.php';
    include_once 'Framework/SelfDescribing.php';
    include_once 'Framework/Test.php';
    include_once 'Framework/TestCase.php';
}

// Define application config lookup path
$paths = [
    get_include_path(),
    $base . '/config',
    $base . '/src',
];

set_include_path(
    implode(':', $paths)
);

?>
