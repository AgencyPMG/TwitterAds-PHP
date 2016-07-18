<?php

namespace Blackburn29\TwitterAds;

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

    public function getToken()
    {
        return $this->accessToken;
    }

    public function getSecret()
    {
        return $this->tokenSecret;
    }

    public function toGuzzleOAuth()
    {
        return new Oauth1([
            'consumer_key'      => $this->consumerKey,
            'consumer_secret'   => $this->consumerSecret,
            'token'             => $this->accessToken,
            'token_secret'      => $this->tokenSecret,
        ]);
    }

    public function getAuthenticationHeaders(Request $request)
    {
        $method = $request->getMethod();
        $baseHeaders = $this->getBaseHeaders();
        $signature = $this->getOauthSignature(
            $method,
            self::getBaseUrl($request->getUrl()),
            $request->getParameters(),
            $baseHeaders
        );

        foreach($baseHeaders as $key => $header) {
            $baseHeaders[$key] = urlencode($header);
        }

        $baseHeaders['oauth_signature'] = $signature;
        ksort($baseHeaders);

        return $baseHeaders;
    }

    private function getBaseHeaders()
    {
        return [
            'oauth_consumer_key'        => $this->consumerKey,
            'oauth_nonce'               => self::generateNonce(),
            'oauth_signature_method'    => 'HMAC-SHA1',
            'oauth_timestamp'           => (new \DateTime())->getTimeStamp(),
            'oauth_token'               => $this->accessToken,
            'oauth_version'             => '1.0',
        ];
    }

    public function getOauthSignature($method, $baseUrl, $parameters, $headers)
    {
        $parameters = implode('&', $parameters);

        foreach($headers as $key => $header) {
            $headers[$key] = urlencode($header);
        }

        return base64_encode(hash_hmac(
            'sha1',
            $method.'&'.urlencode($baseUrl).
            implode('&', $headers).
            $parameters,
            $this->consumerSecret.'&'.$this->tokenSecret,
            true
        ));
    }

    private static function getBaseUrl($url)
    {
        $split = parse_url($url);

        return $split['scheme'].'://'.strtolower($split['host']).$split['path'];
    }

    private static function generateNonce()
    {
        return md5(microtime().mt_rand());
    }
}
