<?php

/**
 * This file contains the shared Lunr base test class.
 *
 * SPDX-FileCopyrightText: Copyright 2013 M2mobi B.V., Amsterdam, The Netherlands
 * SPDX-FileCopyrightText: Copyright 2022 Move Agency Group B.V., Zwolle, The Netherlands
 * SPDX-License-Identifier: MIT
 */

namespace Lunr\Halo;

use Closure;
use PHPUnit\Framework\TestCase;
use ReflectionClass;
use ReflectionMethod;
use ReflectionProperty;
use RuntimeException;

/**
 * This class contains helper code for the Lunr unit tests.
 *
 * @deprecated Use `LunrBaseTestCase` instead
 *
 * @phpstan-type CallableMethod array{0:object|string,1:string}
 */
abstract class LegacyBaseTest extends TestCase
{

    /**
     * Instance of the tested class.
     * @var object
     */
    protected $class;

    /**
     * Array of mock class remaps for uopz.
     * @var array<string, array<string, string>>
     */
    protected array $mockRemap = [];

    /**
     * Array of output messages to expect.
     * @var string[]
     */
    private array $outputStrings = [];

    /**
     * Whether we have an error handler set for E_USER_NOTICE.
     * @var bool
     */
    private bool $isUserNoticeHandlerSet = FALSE;

    /**
     * Whether we have an error handler set for E_USER_WARNING.
     * @var bool
     */
    private bool $isUserWarningHandlerSet = FALSE;

    /**
     * Whether we have an error handler set for E_USER_ERROR.
     * @var bool
     */
    private bool $isUserErrorHandlerSet = FALSE;

    /**
     * Whether we have an error handler set for E_USER_DEPRECATED.
     * @var bool
     */
    private bool $isUserDeprecatedHandlerSet = FALSE;

    /**
     * Reflection instance of the tested class.
     * @var ReflectionClass<object>
     */
    protected ReflectionClass $reflection;

    /**
     * Testcase Destructor.
     */
    public function tearDown(): void
    {
        if ($this->isUserNoticeHandlerSet)
        {
            restore_error_handler();
            $this->isUserNoticeHandlerSet = FALSE;
        }

        if ($this->isUserWarningHandlerSet)
        {
            restore_error_handler();
            $this->isUserWarningHandlerSet = FALSE;
        }

        if ($this->isUserErrorHandlerSet)
        {
            restore_error_handler();
            $this->isUserErrorHandlerSet = FALSE;
        }

        if ($this->isUserDeprecatedHandlerSet)
        {
            restore_error_handler();
            $this->isUserDeprecatedHandlerSet = FALSE;
        }

        unset($this->mockRemap);
        unset($this->class);
        unset($this->reflection);
    }

    /**
     * Get an accessible ReflectionMethod.
     *
     * @param string $method Method name
     *
     * @deprecated Use get_reflection_method() instead
     *
     * @return ReflectionMethod The ReflectionMethod instance
     */
    protected function get_accessible_reflection_method(string $method): ReflectionMethod
    {
        return $this->get_reflection_method($method);
    }

    /**
     * Get a ReflectionMethod.
     *
     * @param string $method Method name
     *
     * @return ReflectionMethod The ReflectionMethod instance
     */
    protected function get_reflection_method(string $method): ReflectionMethod
    {
        return $this->reflection->getMethod($method);
    }

    /**
     * Set a value for a class property.
     *
     * @param string $property Property name
     * @param mixed  $value    New value of the property
     *
     * @return void
     */
    protected function set_reflection_property_value(string $property, $value): void
    {
        $this->get_reflection_property($property)
             ->setValue($this->class, $value);
    }

    /**
     * Get an accessible ReflectionProperty.
     *
     * @param string $property Property name
     *
     * @deprecated Use get_reflection_property() instead
     *
     * @return ReflectionProperty The ReflectionProperty instance
     */
    protected function get_accessible_reflection_property(string $property): ReflectionProperty
    {
        return $this->get_reflection_property($property);
    }

    /**
     * Get a ReflectionProperty.
     *
     * @param string $property Property name
     *
     * @return ReflectionProperty The ReflectionProperty instance
     */
    protected function get_reflection_property(string $property): ReflectionProperty
    {
        return $this->reflection->getProperty($property);
    }

    /**
     * Get a value from a class property.
     *
     * @param string $property Property name
     *
     * @return mixed Property value
     */
    protected function get_reflection_property_value(string $property): mixed
    {
        return $this->get_reflection_property($property)
                    ->getValue($this->class);
    }

    /**
     * Mock a PHP function.
     *
     * @param string  $name Function name
     * @param Closure $mock Replacement code for the function
     *
     * @return void
     */
    protected function mock_function(string $name, Closure $mock): void
    {
        $this->uopz_mock_function($name, $mock);
    }

    /**
     * Mock a PHP function with uopz.
     *
     * @param string  $name Function name
     * @param Closure $mock Replacement code for the function
     *
     * @return void
     */
    private function uopz_mock_function(string $name, Closure $mock): void
    {
        if (!extension_loaded('uopz'))
        {
            $this->markTestSkipped('The uopz extension is not available.');
        }

        uopz_set_return($name, $mock, TRUE);
    }

    /**
     * Unmock a PHP function.
     *
     * @param string $name Function name
     *
     * @return void
     */
    protected function unmock_function(string $name): void
    {
        $this->uopz_unmock_function($name);
    }

    /**
     * Unmock a PHP function with uopz.
     *
     * @param string $name Function name
     *
     * @return void
     */
    private function uopz_unmock_function(string $name): void
    {
        if (!extension_loaded('uopz'))
        {
            $this->markTestSkipped('The uopz extension is not available.');
        }

        uopz_unset_return($name);
    }

    /**
     * Mock a method.
     *
     * Replace the code of a function of a specific class
     *
     * @param CallableMethod $method     Method defined in an array form
     * @param Closure        $mock       Replacement code for the method
     * @param string         $visibility Visibility of the redefined method
     * @param string         $args       Comma-delimited list of arguments for the redefined method
     *
     * @return void
     */
    protected function mock_method(array $method, Closure $mock, string $visibility = 'public', string $args = ''): void
    {
        //UOPZ does not support changing the visibility with the currently used function
        $this->uopz_mock_method($method, $mock, $args);
    }

    /**
     * Mock a method with uopz.
     *
     * Replace the code of a function of a specific class
     *
     * @param CallableMethod $method Method defined in an array form
     * @param Closure        $mock   Replacement code for the method
     * @param string         $args   Comma-delimited list of arguments for the redefined method
     *
     * @return void
     */
    private function uopz_mock_method(array $method, Closure $mock, string $args = ''): void
    {
        if (!extension_loaded('uopz'))
        {
            $this->markTestSkipped('The uopz extension is not available.');
        }

        $className  = is_object($method[0]) ? get_class($method[0]) : $method[0];
        $methodName = $method[1];

        try
        {
            uopz_set_return($className, $methodName, $mock, TRUE);
        }
        catch (RuntimeException $e)
        {
            $pos = strpos($e->getMessage(), 'the method is defined in');

            if ($pos === FALSE)
            {
                throw $e;
            }

            $parentClassName = substr($e->getMessage(), $pos + 25);

            uopz_set_return($parentClassName, $methodName, $mock, TRUE);

            $this->mockRemap[$className][$methodName] = $parentClassName;
        }
    }

    /**
     * Unmock a method.
     *
     * @param CallableMethod $method Method defined in an array form
     *
     * @return void
     */
    protected function unmock_method(array $method): void
    {
        $this->uopz_unmock_method($method);
    }

    /**
     * Unmock a method with uopz.
     *
     * @param CallableMethod $method Method defined in an array form
     *
     * @return void
     */
    private function uopz_unmock_method(array $method): void
    {
        if (!extension_loaded('uopz'))
        {
            $this->markTestSkipped('The uopz extension is not available.');
        }

        $className  = is_object($method[0]) ? get_class($method[0]) : $method[0];
        $methodName = $method[1];

        if (array_key_exists($className, $this->mockRemap) && array_key_exists($methodName, $this->mockRemap[$className]))
        {
            uopz_unset_return($this->mockRemap[$className][$methodName], $methodName);
        }
        else
        {
            uopz_unset_return($className, $methodName);
        }
    }

    /**
     * Redefine a constant with uopz
     *
     * @param string $constant The constant
     * @param mixed  $value    New value
     *
     * @return void
     */
    protected function constant_redefine(string $constant, $value): void
    {
        if (!extension_loaded('uopz'))
        {
            $this->markTestSkipped('The uopz extension is not available.');
        }

        $constant = explode('::', $constant);

        if (isset($constant[1]))
        {
            uopz_redefine($constant[0], $constant[1], $value);
        }
        else
        {
            uopz_redefine($constant[0], $value);
        }
    }

    /**
     * Undefine a constant with uopz
     *
     * @param string $constant The constant
     *
     * @return void
     */
    protected function constant_undefine(string $constant): void
    {
        if (!extension_loaded('uopz'))
        {
            $this->markTestSkipped('The uopz extension is not available.');
        }

        $constant = explode('::', $constant);

        if (isset($constant[1]))
        {
            uopz_undefine($constant[0], $constant[1]);
        }
        else
        {
            uopz_undefine($constant[0]);
        }
    }

    /**
     * Assert that a property value equals the expected value.
     *
     * @param string $property Property name
     * @param mixed  $expected Expected value of the property
     *
     * @return void
     */
    protected function assertPropertyEquals(string $property, $expected): void
    {
        $this->assertEquals($expected, $this->get_reflection_property_value($property));
    }

    /**
     * Assert that a property value equals the expected value.
     *
     * @param string $property Property name
     * @param mixed  $expected Expected value of the property
     *
     * @return void
     */
    protected function assertPropertySame(string $property, $expected): void
    {
        $this->assertSame($expected, $this->get_reflection_property_value($property));
    }

    /**
     * Assert that a property value is empty.
     *
     * @param string $property Property name
     *
     * @return void
     */
    protected function assertPropertyEmpty(string $property): void
    {
        $this->assertEmpty($this->get_reflection_property_value($property));
    }

    /**
     * Assert that a property value was unset.
     *
     * @param string $name Property name
     *
     * @return void
     */
    protected function assertPropertyUnset(string $name): void
    {
        $this->assertTrue(property_exists($this->class, $name));

        $property = $this->get_reflection_property($name);

        $this->assertFalse($property->isInitialized($this->class));
    }

    /**
     * Assert that an array is empty.
     *
     * @param mixed $value The value to test.
     *
     * @return void
     */
    protected function assertArrayEmpty($value): void
    {
        $this->assertIsArray($value);
        $this->assertEmpty($value);
    }

    /**
     * Assert that an array is not empty.
     *
     * @param mixed $value The value to test.
     *
     * @return void
     */
    protected function assertArrayNotEmpty($value): void
    {
        $this->assertIsArray($value);
        $this->assertNotEmpty($value);
    }

    /**
     * Expect that the output generating by the tested method matches the content of the given file.
     *
     * @param string $file Path to file to match against
     *
     * @return void
     */
    protected function expectOutputMatchesFile(string $file): void
    {
        $content = file_get_contents($file);
        if ($content === FALSE)
        {
            throw new RuntimeException("File \"$file\" could not be read!");
        }

        $this->expectOutputString($content);
    }

    /**
     * Expect an E_USER_NOTICE error.
     *
     * @param string $message The error message to expect
     *
     * @return void
     */
    public function expectUserNotice(string $message): void
    {
        if (!$this->isUserNoticeHandlerSet)
        {
            set_error_handler(
                function (int $errno, string $errstr): bool {
                    echo "NOTICE: $errstr\n";
                    return TRUE;
                },
                E_USER_NOTICE,
            );
            $this->isUserNoticeHandlerSet = TRUE;
        }

        $this->outputStrings[] = "NOTICE: $message\n";

        $this->expectOutputString(implode("\n", $this->outputStrings));
    }

    /**
     * Expect an E_USER_WARNING error.
     *
     * @param string $message The error message to expect
     *
     * @return void
     */
    public function expectUserWarning(string $message): void
    {
        if (!$this->isUserWarningHandlerSet)
        {
            set_error_handler(
                function (int $errno, string $errstr): bool {
                    echo "WARNING: $errstr\n";
                    return TRUE;
                },
                E_USER_WARNING,
            );
            $this->isUserWarningHandlerSet = TRUE;
        }

        $this->outputStrings[] = "WARNING: $message\n";

        $this->expectOutputString(implode("\n", $this->outputStrings));
    }

    /**
     * Expect an E_USER_ERROR error.
     *
     * @param string $message The error message to expect
     *
     * @return void
     */
    public function expectUserError(string $message): void
    {
        if (!$this->isUserErrorHandlerSet)
        {
            set_error_handler(
                function (int $errno, string $errstr): bool {
                    echo "ERROR: $errstr\n";
                    return TRUE;
                },
                E_USER_ERROR,
            );
            $this->isUserErrorHandlerSet = TRUE;
        }

        $this->outputStrings[] = "ERROR: $message\n";

        $this->expectOutputString(implode("\n", $this->outputStrings));
    }

    /**
     * Expect an E_USER_DEPRECATED error.
     *
     * @param string $message The error message to expect
     *
     * @return void
     */
    public function expectUserDeprecated(string $message): void
    {
        if (!$this->isUserDeprecatedHandlerSet)
        {
            set_error_handler(
                function (int $errno, string $errstr): bool {
                    echo "DEPRECATED: $errstr\n";
                    return TRUE;
                },
                E_USER_DEPRECATED,
            );
            $this->isUserDeprecatedHandlerSet = TRUE;
        }

        $this->outputStrings[] = "DEPRECATED: $message\n";

        $this->expectOutputString(implode("\n", $this->outputStrings));
    }

    /**
     * Expect an output string.
     *
     * Override the parent method so it plays well together with the user
     * error handlers.
     *
     * @param string $expectedString The output string to expect
     *
     * @return void
     */
    public function expectCustomOutputString(string $expectedString): void
    {
        $this->outputStrings[] = $expectedString;

        $this->expectOutputString(implode("\n", $this->outputStrings));
    }

}

?>
