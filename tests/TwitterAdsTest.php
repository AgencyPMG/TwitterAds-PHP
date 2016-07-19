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

namespace PMG\TwitterAds;

use PMG\TwitterAds\Fixtures\TestRequest;
use PMG\TwitterAds\Util\VerifyCredentialsRequest;

class TwitterAdsTest extends UnitTestCase
{
    private $twitter;

    public function testSuppliedCredentialsAreValid()
    {
        $request = new VerifyCredentialsRequest();
        $response = $this->twitter->send($request);
        $this->assertSuccessfulResponse($response);
        $body = $response->getBody();
        $this->assertArrayHasKey('name', $body);
        echo "\nAuthenticated As: ".$body['name']." :) \n";
    }

    /**
     * @expectedException PMG\TwitterAds\TwitterAdsException
     */
    public function testGuzzleWillThrowCorrectExceptionOnFailure()
    {
        $request = new TestRequest('POST', 'http://httpbin.com/get');

        $this->twitter->send($request);
    }

    protected function setUp()
    {
        $this->twitter = $this->getTwitterAds();
    }
}
