<?php
/*
 * This file is part of pmg/twitterads
 *
 * Copyright (c) PMG <https://www.pmg.com>
 *
 * For full copyright information see the LICENSE file distributed
 * with this source code.
 *
 * @license     https://opensource.org/licenses/MIT MIT
 */

namespace PMG\TwitterAds\Fixtures;

use PMG\TwitterAds\Request;

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

    protected function getRoutes()
    {
        return [];
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
