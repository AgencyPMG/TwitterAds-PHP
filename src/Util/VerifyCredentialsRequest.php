<?php

namespace PMG\TwitterAds\Util;

use PMG\TwitterAds\HttpMethods;
use PMG\TwitterAds\Request;

final class VerifyCredentialsRequest extends Request
{
    const BASE_URL = 'https://api.twitter.com/1.1/';

    public function getMethod()
    {
        return HttpMethods::GET;
    }

    public function getUrl()
    {
        return self::BASE_URL.'account/verify_credentials.json';
    }

    public function getParameters()
    {
        return [];
    }

    public function getHeaders()
    {
        return [];
    }
}
