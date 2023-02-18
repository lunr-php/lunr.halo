<?php

/**
 * This file contains the CallbackMock.
 *
 * SPDX-FileCopyrightText: Copyright 2015 M2mobi B.V., Amsterdam, The Netherlands
 * SPDX-FileCopyrightText: Copyright 2022 Move Agency Group B.V., Zwolle, The Netherlands
 * SPDX-License-Identifier: MIT
 */

namespace Lunr\Halo;

/**
 * This mock class can be used when one want to mock callback functionality.
 */
class CallbackMock
{

    /**
     * Constructor.
     */
    public function __construct()
    {
        // no-op
    }

    /**
     * Destructor.
     */
    public function __destruct()
    {
        // no-op
    }

    /**
     * A test function to be overridden by phpunit's mock functionality.
     *
     * @return mixed|void
     */
    public function test()
    {
        // no-op
    }

}

?>
