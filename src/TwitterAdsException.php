<?php

namespace PMG\TwitterAds;

class TwitterAdsException extends \Exception
{
    public function __construct($msg='', $code=0, \Exception $prev=null)
    {
        parent::__construct($msg, $code, $prev);
    }
}
