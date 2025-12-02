<?php

/**
 * This file contains the PsrLoggerTestTrait.
 *
 * SPDX-FileCopyrightText: Copyright 2013 M2mobi B.V., Amsterdam, The Netherlands
 * SPDX-FileCopyrightText: Copyright 2022 Move Agency Group B.V., Zwolle, The Netherlands
 * SPDX-License-Identifier: MIT
 */

namespace Lunr\Halo\PropertyTraits;

/**
 * This trait contains test methods to verify a PSR-3 compliant logger was passed correctly.
 */
trait PsrLoggerTestTrait
{

    /**
     * Default property name for the logger
     * @var string
     */
    private string $propertyNameLogger = 'logger';

    /**
     * Test that the Logger class is passed correctly.
     */
    public function testLoggerIsSetCorrectly(): void
    {
        $property = $this->getReflectionPropertyValue($this->propertyNameLogger);

        $this->assertSame($property, $this->logger);
        $this->assertInstanceOf('Psr\Log\LoggerInterface', $property);
    }

}

?>
