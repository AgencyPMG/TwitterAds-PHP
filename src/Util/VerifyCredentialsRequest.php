<?php

namespace PMG\TwitterAds\Util;

use PMG\TwitterAds\HttpMethods;
use PMG\TwitterAds\Request;

final class VerifyCredentialsRequest extends Request
{
    const BASE_URL = 'https://api.twitter.com/1.1/';

    /**
     * {@inheritdoc}
     */
    public function getMethod()
    {
        return HttpMethods::GET;
    }

    /**
     * {@inheritdoc}
     */
    public function getUrl()
    {
        return self::BASE_URL.'account/verify_credentials.json';
    }

    /**
     * {@inheritdoc}
     */
    public function getParameters()
    {
        return [];
    }

    /**
     * {@inheritdoc}
     */
    public function getHeaders()
    {
        return [];
    }
}
