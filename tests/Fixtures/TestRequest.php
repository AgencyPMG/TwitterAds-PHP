<?php

namespace Blackburn29\TwitterAds\Fixtures;

use Blackburn29\TwitterAds\Request;

class TestRequest extends Request
{
    private $url, $parameters, $headers;

    public function __construct($method, $url, $parameters=[], $headers=[])
    {
        $this->method = $method;
        $this->url = $url;
        $this->parameters = $parameters;
        $this->headers = $headers;
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
