<?php

namespace Blackburn29\TwitterAds;

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
