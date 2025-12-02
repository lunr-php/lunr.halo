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
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use ReflectionClass;
use ReflectionMethod;
use ReflectionProperty;
use RuntimeException;

/**
 * This class contains helper code for the Lunr unit tests.
 *
 * @phpstan-type CallableMethod array{0:object|string,1:string}
 */
abstract class LunrBaseTestCase extends TestCase
{

    /**
     * Instance of the tested class.
     * @var object
     */
    private object $class;

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
     * Whether we have an error handler set for E_WARNING.
     * @var bool
     */
    private bool $isWarningHandlerSet = FALSE;

    /**
     * Whether we have an error handler set for E_USER_ERROR.
     * @var bool
     */
    private bool $isUserErrorHandlerSet = FALSE;

    /**
     * Whether we have an error handler set for E_ERROR.
     * @var bool
     */
    private bool $isErrorHandlerSet = FALSE;

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
     * Testcase Constructor.
     *
     * @param object $class Instance of the tested class
     *
     * @return void
     */
    public function baseSetUp(object $class): void
    {
        $this->class = $class;

        if ($class instanceof MockObject)
        {
            /**
             * MockObject *must* have a parent, so this can't return FALSE.
             * @var class-string<object> $instance
             */
            $instance = get_parent_class($class);

            $this->reflection = new ReflectionClass($instance);
        }
        else
        {
            $this->reflection = new ReflectionClass($class::class);
        }
    }

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

        if ($this->isWarningHandlerSet)
        {
            restore_error_handler();
            $this->isWarningHandlerSet = FALSE;
        }

        if ($this->isUserErrorHandlerSet)
        {
            restore_error_handler();
            $this->isUserErrorHandlerSet = FALSE;
        }

        if ($this->isErrorHandlerSet)
        {
            restore_error_handler();
            $this->isErrorHandlerSet = FALSE;
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
     * Get a ReflectionMethod.
     *
     * @param string $method Method name
     *
     * @return ReflectionMethod The ReflectionMethod instance
     */
    protected function getReflectionMethod(string $method): ReflectionMethod
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
    protected function setReflectionPropertyValue(string $property, $value): void
    {
        $this->getReflectionProperty($property)
             ->setValue($this->class, $value);
    }

    /**
     * Get a ReflectionProperty.
     *
     * @param string $property Property name
     *
     * @return ReflectionProperty The ReflectionProperty instance
     */
    protected function getReflectionProperty(string $property): ReflectionProperty
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
    protected function getReflectionPropertyValue(string $property): mixed
    {
        return $this->getReflectionProperty($property)
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
    protected function mockFunction(string $name, Closure $mock): void
    {
        $this->uopzMockFunction($name, $mock);
    }

    /**
     * Mock a PHP function with uopz.
     *
     * @param string  $name Function name
     * @param Closure $mock Replacement code for the function
     *
     * @return void
     */
    private function uopzMockFunction(string $name, Closure $mock): void
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
    protected function unmockFunction(string $name): void
    {
        $this->uopzUnmockFunction($name);
    }

    /**
     * Unmock a PHP function with uopz.
     *
     * @param string $name Function name
     *
     * @return void
     */
    private function uopzUnmockFunction(string $name): void
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
    protected function mockMethod(array $method, Closure $mock, string $visibility = 'public', string $args = ''): void
    {
        //UOPZ does not support changing the visibility with the currently used function
        $this->uopzMockMethod($method, $mock, $args);
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
    private function uopzMockMethod(array $method, Closure $mock, string $args = ''): void
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
    protected function unmockMethod(array $method): void
    {
        $this->uopzUnmockMethod($method);
    }

    /**
     * Unmock a method with uopz.
     *
     * @param CallableMethod $method Method defined in an array form
     *
     * @return void
     */
    private function uopzUnmockMethod(array $method): void
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
    protected function redefineConstant(string $constant, $value): void
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
    protected function undefineConstant(string $constant): void
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
        $this->assertEquals($expected, $this->getReflectionPropertyValue($property));
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
        $this->assertSame($expected, $this->getReflectionPropertyValue($property));
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
        $this->assertEmpty($this->getReflectionPropertyValue($property));
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

        $property = $this->getReflectionProperty($name);

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
    public function expectUserNotice(string $message = ''): void
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
    public function expectUserWarning(string $message = ''): void
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
     * Expect an E_WARNING error.
     *
     * @param string $message The error message to expect
     *
     * @return void
     */
    public function expectWarning(string $message = ''): void
    {
        if (!$this->isUserWarningHandlerSet)
        {
            set_error_handler(
                function (int $errno, string $errstr): bool {
                    echo "WARNING: $errstr\n";
                    return TRUE;
                },
                E_WARNING,
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
    public function expectUserError(string $message = ''): void
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
     * Expect an E_ERROR error.
     *
     * @param string $message The error message to expect
     *
     * @return void
     */
    public function expectError(string $message = ''): void
    {
        if (!$this->isErrorHandlerSet)
        {
            set_error_handler(
                function (int $errno, string $errstr): bool {
                    echo "ERROR: $errstr\n";
                    return TRUE;
                },
                E_ERROR,
            );
            $this->isErrorHandlerSet = TRUE;
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
    public function expectUserDeprecated(string $message = ''): void
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
