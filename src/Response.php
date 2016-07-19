<?php
/*
 * This file is part of pmg/twitterads
 *
 * (c) PMG <https://www.pmg.com>. All rights reserved.
 */

namespace PMG\TwitterAds;

use \Psr\Http\Message\ResponseInterface;

/**
 * A response object used for wrapping an http response
 *
 * @since 2016-07-13
 */
class Response implements Arrayable, \IteratorAggregate
{
    private $code;
    private $headers;
    private $body;

    public function __construct($code, array $headers, $body)
    {
        $this->code = $code;
        $this->headers = $this->parseHeaders($headers);
        $this->body = $body;
    }

    /**
     * Generates a response from the PSR7 response interface
     *
     * @return Response
     */
    public static function fromResponseInterface(ResponseInterface $req)
    {
        return new self(
            $req->getStatusCode(),
            $req->getHeaders(),
            !empty($req->getBody()) ? json_decode($req->getBody(), true) : []
        );
    }

    /**
     * {@inheritdoc}
     */
    public function getIterator()
    {
        return new ArrayIterator($this->body);
    }

    /**
     * Returns the response code associated with the request
     *
     * @return int
     */
    public function getResponseCode()
    {
        return $this->code;
    }

    /**
     * Returns the head associated with the response
     *
     * @return array
     */
    public function getHeaders()
    {
        return $this->headers;
    }

    /**
     * Returns the body of the response
     *
     * @return array
     */
    public function getBody()
    {
        return $this->body;
    }

    /**
     * Returns data attached to this request
     *
     * @return array
     */
    public function getData()
    {
        return isset($this->body['data']) ? $this->body['data'] : [];
    }

    /**
     * {@inheritdoc}
     */
    public function toArray()
    {
        return [
            'response_code' => $this->code,
            'headers' => $this->headers,
            'body'    => $this->body,
        ];
    }

    /**
     * Formats headers into a more readable output
     *
     * @param $headers array
     *
     * @return array
     */
    private function parseHeaders($headers)
    {
        $parsed = [];

        foreach ($headers as $key => $header) {
            if (is_array($header) && 1 === count($header)) {
                $parsed[$key] = $header[0];
            } else {
                $parsed[$key] = $header;
            }
        }

        return $parsed;
    }
}
