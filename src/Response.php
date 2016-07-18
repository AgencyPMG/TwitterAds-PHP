<?php

namespace Blackburn29\TwitterAds;

use GuzzleHttp\Psr7\Response as GuzzleResponse;

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

    public static function fromGuzzleResponse(GuzzleResponse $req)
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
