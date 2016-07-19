<?php
/*
 * This file is part of pmg/twitterads
 *
 * (c) PMG <https://www.pmg.com>. All rights reserved.
 */

namespace PMG\TwitterAds;

use GuzzleHttp\Subscriber\Oauth\Oauth1;

/**
 * Represents the oAuth credentials needs to authenticate
 *
 * @since 2016-07-13
 */
final class Auth
{
    private $consumerKey;
    private $consumerSecret;
    private $accessToken;
    private $tokenSecret;

    public function __construct($consumerKey, $consumerSecret, $accessToken, $tokenSecret)
    {
        $this->consumerKey = $consumerKey;
        $this->consumerSecret = $consumerSecret;
        $this->accessToken = $accessToken;
        $this->tokenSecret = $tokenSecret;
    }

    /**
     * @return string
     */
    public function getToken()
    {
        return $this->accessToken;
    }

    /**
     * @return string
     */
    public function getSecret()
    {
        return $this->tokenSecret;
    }

    /**
     * Transforms this Auth object to the correct oAuth object for the Guzzle subscriber.
     *
     * @return GuzzleHttp\Subscriber\Oauth\Oauth1
     */
    public function toGuzzleOAuth()
    {
        return new Oauth1([
            'consumer_key'      => $this->consumerKey,
            'consumer_secret'   => $this->consumerSecret,
            'token'             => $this->accessToken,
            'token_secret'      => $this->tokenSecret,
        ]);
    }
}
