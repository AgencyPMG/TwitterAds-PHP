<?php
/*
 * This file is part of pmg/twitterads
 *
 * Copyright (c) PMG <https://www.pmg.com>
 *
 * For full copyright information see the LICENSE file distributed
 * with this source code.
 *
 * @license     https://opensource.org/licenses/MIT MIT
 */

namespace PMG\TwitterAds;

/**
 * A marker interface used to define if a class can be converted to an array
 *
 * @since 2016-07-13
 */
interface Arrayable
{
    /**
     * Returns an object as an array
     *
     * @return array
     */
    public function toArray();
}
