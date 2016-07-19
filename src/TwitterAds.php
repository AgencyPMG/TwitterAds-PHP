<?php

namespace Blackburn29\TwitterAds;

use GuzzleHttp\Client;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Exception\BadResponseException;
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

    /**
     * @var boolean
     */
    private $debug;

    public function __construct($consumerKey, $consumerSecret, $token, $secret, $debug=false)
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

        $params['debug'] = $this->debug;
        $params['headers'] = $request->getHeaders();

        try {
            return Response::fromResponseInterface(
                call_user_func(
                    [$this->client, strtolower($request->getMethod())],
                    $url,
                    $params
            ));
        } catch (BadResponseException $e) {
            throw new TwitterAdsException('Failed to make request.', $e->getCode(), $e);
        }
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
}
