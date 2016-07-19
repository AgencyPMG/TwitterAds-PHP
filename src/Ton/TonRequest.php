<?php
/*
 * This file is part of pmg/twitterads
 *
 * (c) PMG <https://www.pmg.com>. All rights reserved.
 */

namespace PMG\TwitterAds\Ton;

use PMG\TwitterAds\Request;
use PMG\TwitterAds\HttpMethods;

/**
 * Request object for uploading files via the Twitter TON API
 *
 * @since 2016-07-15
 */
class TonRequest extends Request
{
    const BASE_URL = 'https://ton.twitter.com/1.1/';
    const API_VERSION = '1.1';
    const ADS_BUCKET = 'ta_partner';

    /**
     * {@inheritdoc}
     */
    protected function getRoutes()
    {
        return [
            'ton/bucket/:bucket'                => HttpMethods::POST,
            'ton/bucket/:bucket?resumable=true' => HttpMethods::POST,
            ':location'                         => HttpMethods::PUT,
        ];
    }

    /**
     * {@inheritdoc}
     */
    protected function getBaseUrl()
    {
        return self::BASE_URL;
    }
}
