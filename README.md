[![Coverage Status](https://coveralls.io/repos/github/PMG/TwitterAds-PHP/badge.svg?branch=master)](https://coveralls.io/github/PMG/TwitterAds-PHP?branch=master)
[![Build Status](https://travis-ci.org/PMG/TwitterAds-PHP.svg?branch=master)](https://travis-ci.org/PMG/TwitterAds-PHP)

# TwitterAds-PHP
A simple Twitter Ads SDK for PHP, powered by Guzzle.

## Initializing the client
Initializing the client is simple. Just supply your keys and go!
```php
$twitter = new \PMG\TwitterAds\TwitterAds(CONSUMER_KEY, CONSUMER_SECRET, ACCESS_TOKEN, TOKEN_SECRET);
//We will assume for the rest of the examples that $twitter is defined.
```

## Requests
For each entity in the Twitter Ads API, there is an available request object.
I attempt to keep the endpoints exactly as they appear in the API docs, however there are a few things to keep in mind.

- Route parameters are fulfilled in an array after the requested API endpoint (See example below)
- Each endpoint has a default `HttpMethod` assigned to it, however this can be overridden in the constructor for each `Request` type
- Requests have the following constructor signature: `__construct($url, $params=[], $headers=[], $method=null)` where `$url` is the API endpoint, `$params` is the body/route parameters, `$headers` is an associative array of headers to send with the request, and `$method` is used to override the default `HttpMethod`

## Responses
All requests, if successful, will return a `PMG\TwitterAds\Response` which is a simple wrapper around the Guzzle response that was 
received back after making the request. The request also implements `Arrayable` which allows converting the request into an array.

Example array response:
```php
object(PMG\TwitterAds\Response)#228 (3) {
  ["code":"PMG\TwitterAds\Response":private]=>
  int(200)
  ["headers":"PMG\TwitterAds\Response":private]=>
  array(8) {
    ["content-length"]=>
    string(4) "3749"
    ["content-type"]=>
    string(30) "application/json;charset=utf-8"
    ["date"]=>
    string(29) "Tue, 19 Jul 2016 14:55:21 GMT"
    ["expires"]=>
    string(29) "Tue, 31 Mar 1981 05:00:00 GMT"
    ["status"]=>
    string(6) "200 OK"
    ["x-rate-limit-limit"]=>
    string(2) "15"
    ["x-rate-limit-remaining"]=>
    string(2) "13"
    ["x-rate-limit-reset"]=>
    string(10) "1468940943"
  }
  ["body":"PMG\TwitterAds\Response":private]=>
  array(2) {
    ["id"]=>
    int(2222)
    ["id_str"]=>
    string(9) "2222"
  }
}
```

An example of making a request:
```php
use PMG\TwitterAds\TailoredAudiences\TailoredAudienceRequest;

$request = new TailoredAudienceRequest('accounts/:account_id/tailored_audiences', ['account_id' => ACCOUNT]);
$response = $twitter->send($request);

//Returns PMG\TwitterAds\Response
```

## Using the TON API
I provide 2 methods of using the TON API.

- `PMG\TwitterAds\Ton\TonRequest`
- `PMG\TwitterAds\Ton\TonUpload`

I strongly recommend using the latter of the 2 options because `TonUpload` was designed to handle any batched uploads that may
need to happen and will handle all requests required to fulfil the upload.

`TonRequest` works like any other request in this SDK. 

And example of `TonUpload`
```php
$file = new \SplFileObject('somefile.txt');
$tonFile = new TonUpload($twitter, $file, 'text/plain');

$response = $tonFile->upload();

//Returns PMG\TwitterAds\Response
```
