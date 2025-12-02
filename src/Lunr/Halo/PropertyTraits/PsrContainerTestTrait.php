<?php

/**
 * This file contains the PsrContainerTestTrait.
 *
 * SPDX-FileCopyrightText: Copyright 2023 Move Agency Group B.V., Zwolle, The Netherlands
 * SPDX-License-Identifier: MIT
 */

namespace Lunr\Halo\PropertyTraits;

/**
 * This trait contains test methods to verify a PSR-11 compliant container was passed correctly.
 */
trait PsrContainerTestTrait
{

    /**
     * Default property name for the container
     * @var string
     */
    private string $propertyNameContainer = 'container';

    /**
     * Test that the Container class is passed correctly.
     */
    public function testContainerIsSetCorrectly(): void
    {
        $property = $this->getReflectionPropertyValue($this->propertyNameContainer);

        $this->assertSame($property, $this->container);
        $this->assertInstanceOf('Psr\Container\ContainerInterface', $property);
    }

}

?>
