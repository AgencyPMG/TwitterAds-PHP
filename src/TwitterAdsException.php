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
 * A base exception for Twitter ads errors
 *
 * @since 2016-07-13
 */
class TwitterAdsException extends \Exception
{
    public function __construct($msg='', $code=0, \Exception $prev=null)
    {
        parent::__construct($msg, $code, $prev);
    }
}
