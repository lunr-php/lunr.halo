<?php

/**
 * This file contains the FluidInterfaceMock.
 *
 * SPDX-FileCopyrightText: Copyright 2014 M2mobi B.V., Amsterdam, The Netherlands
 * SPDX-FileCopyrightText: Copyright 2022 Move Agency Group B.V., Zwolle, The Netherlands
 * SPDX-License-Identifier: MIT
 */

namespace Lunr\Halo;

/**
 * This mock class can be used to more efficiently mock fluid interface calls.
 */
class FluidInterfaceMock
{

    /**
     * Array of mocked return values
     * @var array<string, mixed>
     */
    protected array $return;

    /**
     * Constructor.
     */
    public function __construct()
    {
        $this->return = [];
    }

    /**
     * Destructor.
     */
    public function __destruct()
    {
        unset($this->return);
    }

    /**
     * Handle fluid interface calls.
     *
     * @param string  $name      Method name
     * @param mixed[] $arguments Method arguments
     *
     * @return mixed $return Stored return value or a self reference
     */
    public function __call(string $name, array $arguments)
    {
        if (array_key_exists($name, $this->return))
        {
            return array_pop($this->return[$name]);
        }
        else
        {
            return $this;
        }
    }

    /**
     * Specifically mock a certain method call.
     *
     * @param string $name  Method name
     * @param mixed  $value Mocked return value
     *
     * @return void
     */
    public function mock(string $name, $value): void
    {
        if (!array_key_exists($name, $this->return))
        {
            $this->return[$name] = [];
        }

        array_push($this->return[$name], $value);
    }

}

?>
