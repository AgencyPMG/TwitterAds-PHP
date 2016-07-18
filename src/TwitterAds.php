<?php

namespace Blackburn29\TwitterAds;

use GuzzleHttp\Client;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Request as GuzzleRequest;

use Blackburn29\TwitterAds\Exception\UnsupportedHttpMethod;

class TwitterAds
{
    /**
     * @var Auth
     */
    private $auth;

    /**
     * @var GuzzleHttp\Client
     */
    private $client;

    public function __construct($consumerKey, $consumerSecret, $token, $secret)
    {
        $this->auth = new Auth($consumerKey, $consumerSecret, $token, $secret);

        $stack = HandlerStack::create();
        $stack->push($this->auth->toGuzzleOAuth());
        $this->client = new Client([
            'handler' => $stack,
            'auth'    => 'oauth',
        ]);
    }

    /**
     * Sends off an oAuth 1.0 authenticated request
     *
     * @return Response
     */
    public function send(Request $request)
    {
        list($url, $params) = $request->getParsedUrlAndParams();


        $params['debug'] = true;
        $params['headers'] = $request->getHeaders();

        // If there is a body, generate the required hash for it.
        if (isset($params['body'])) {
            $params['headers']['oauth_body_hash'] = self::generateHash($params['body']);
        }

        return Response::fromGuzzleResponse(
            call_user_func(
                [$this->client, strtolower($request->getMethod())],
                $url,
                $params
        ));
    }

    /**
     * Returns the http client
     *
     * @return string
     */
    public function getHttpClient()
    {
        return $this->client;
    }

    private static function generateHash($content)
    {
        return base64_encode(sha1($content));
    }

}
