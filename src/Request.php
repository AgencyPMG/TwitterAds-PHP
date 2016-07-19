<?php
/*
 * This file is part of pmg/twitterads
 *
 * (c) PMG <https://www.pmg.com>. All rights reserved.
 */

namespace PMG\TwitterAds;

use PMG\TwitterAds\Exception\UndefinedRoute;

abstract class Request
{
    const BASE_URL = 'https://ads-api.twitter.com/1/';

    private $method;
    private $url;
    private $parameters;
    private $headers;

    /**
     * Returns an array of accepted routes for this request
     *
     * @return array
     */
    abstract protected function getRoutes();

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
     * Gets the HTTP Method type
     * 
     * @return string
     */
    public function getMethod()
    {
        return $this->method;
    }

    /**
     * Gets the url to send the request to. (This should be fully qualified)
     *
     * @return string
     */
    public function getUrl()
    {
        return $this->url;
    }

    /**
     * Gets the parameters associated with the request, including any query parameters.
     *
     * @return associative array
     */
    public function getParameters()
    {
        return $this->parameters;
    }

    /**
     * Gets all headers assoicated with the request. 
     *
     * @return associative array
     */
    public function getHeaders()
    {
        return $this->headers;
    }

    /**
     * Transforms a incomplete url using the parameters and returns 
     * the url and modified parameters
     *
     * @return list($url, $params)
     */
    public function getParsedUrlAndParams()
    {
        $url = $this->getUrl();

        $params = [];
        foreach($this->getParameters() as $key => $parameter) {
            $spec = ':'.$key;

            if (false !== strpos($url, $spec)) {
                $url = str_replace($spec, $parameter, $url);
            } else {
                $params[$key] = $parameter;
            }
        }

        return [$this->getBaseUrl().$url, $params];
    }

    protected function assureUrl($url)
    {
        $routes = $this->getRoutes();

        if (!isset($routes[$url])) {
            throw new UndefinedRoute(
                sprintf('"%s" is not a valid route for %s', $url, get_class($this))
            );
        }

        return [$url, $routes[$url]];
    }

    protected function getBaseUrl()
    {
        return self::BASE_URL;
    }
}
