<?php

namespace PMG\TwitterAds;

use PMG\TwitterAds\Exception\UndefinedRoute;

abstract class Request
{
    const BASE_URL = 'https://ads-api.twitter.com/1/';

    /**
     * Gets the HTTP Method type
     * 
     * @return string
     */
    abstract public function getMethod();

    /**
     * Gets the url to send the request to. (This should be fully qualified)
     *
     * @return string
     */
    abstract public function getUrl();

    /**
     * Gets the parameters associated with the request, including any query parameters.
     *
     * @return associative array
     */
    abstract public function getParameters();

    /**
     * Gets all headers assoicated with the request. 
     *
     * @return associative array
     */
    abstract public function getHeaders();

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

            if (strpos($url, $spec)) {
                $url = str_replace($spec, $parameter, $url);
            } else {
                $params[$key] = $parameter;
            }
        }

        return [$this->getBaseUrl().$url, $params];
    }

    protected function assureUrl($url)
    {
        if (!isset(static::ROUTES[$url])) {
            throw new UndefinedRoute(
                sprintf('"%s" is not a valid route for %s', $url, get_class($this))
            );
        }

        return [$url, static::ROUTES[$url]];
    }

    private function getBaseUrl()
    {
        return defined('static::BASE_URL') ? static::BASE_URL : self::BASE_URL;
    }
}
