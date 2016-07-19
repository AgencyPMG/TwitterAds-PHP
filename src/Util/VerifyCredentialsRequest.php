<?php

namespace Blackburn29\TwitterAds\Util;

use Blackburn29\TwitterAds\HttpMethods;
use Blackburn29\TwitterAds\Request;

/**
 * A request that verifies the Twitter oAuth credentials supplied
 *
 * @since 2016-07-13
 */
final class VerifyCredentialsRequest extends Request
{
    const BASE_URL = 'https://api.twitter.com/1.1/';

    public function __construct()
    {
        //noop
    }

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
        return 'account/verify_credentials.json';
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

    /**
     * {@inheritdoc}
     */
    protected function getBaseUrl()
    {
        return self::BASE_URL;
    }

    /**
     * {@inheritdoc}
     */
    protected function getRoutes()
    {
        return ['account/verify_credentials.json' => HttpMethods::GET];
    }
}
