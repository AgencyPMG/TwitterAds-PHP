<?php

namespace PMG\TwitterAds\Accounts;

use PMG\TwitterAds\HttpMethods;
use PMG\TwitterAds\Request;

class AccountRequest extends Request
{
    const ROUTES = [
        'accounts'                                       => HttpMethods::GET,
        'accounts/:account_id'                           => HttpMethods::GET,
        'accounts/:account_id/features'                  => HttpMethods::GET,
        'accounts/:account_id/app_lists'                 => HttpMethods::POST,
        'accounts/:account_id/authenticated_user_access' => HttpMethods::GET,
    ];

    private $method;
    private $url;
    private $parameters;
    private $headers;

    /**
     * Initiates a network request
     *
     * @param $url string - the url to request
     * @param $params array - route and body parameters
     * @param $headers array - headers to send
     * @param $method string|null - an optional override for the default http method
     */
    public function __construct($url, $params=[], $headers=[], $method=null)
    {
        list($this->url, $this->method) = $this->assureUrl($url);
        $this->parameters = $params;
        $this->headers = $headers;

        if ($method) {
            $this->method = $method;
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getMethod()
    {
        return $this->method;
    }

    /**
     * {@inheritdoc}
     */
    public function getUrl()
    {
        return $this->url;
    }

    /**
     * {@inheritdoc}
     */
    public function getParameters()
    {
        return $this->parameters;
    }

    /**
     * {@inheritdoc}
     */
    public function getHeaders()
    {
        return $this->headers;
    }
}
