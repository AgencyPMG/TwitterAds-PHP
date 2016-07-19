<?php
/*
 * This file is part of pmg/twitterads
 *
 * (c) PMG <https://www.pmg.com>. All rights reserved.
 */

namespace PMG\TwitterAds;

use PMG\TwitterAds\Accounts\AccountRequest;

class UnitTestCase extends \PHPUnit_Framework_TestCase
{
    protected function getTwitterAds()
    {
        return new TwitterAds(
            getenv('CONSUMER_KEY'),
            getenv('CONSUMER_SECRET'),
            getenv('ACCESS_TOKEN'),
            getenv('ACCESS_TOKEN_SECRET')
        );
    }

    protected function getAccountFromTwitter()
    {
        $request = new AccountRequest('accounts');

        $response = $this->getTwitterAds()->send($request);

        $this->assertSuccessfulResponse($response);
        $this->assertGreaterThan(0, count($response->getData()));
        return $response->getData()[0];
    }

    protected function assertSuccessfulResponse($resp)
    {
        $this->assertInstanceOf(Response::Class, $resp);
        $this->assertThat($resp->getResponseCode(), $this->logicalAnd(
            $this->greaterThanOrEqual(200),
            $this->lessThan(300)
        ), 'Non Successful Response Status:'.PHP_EOL.json_encode($resp->toArray()));
    }

    protected function assertResponseCodeIsExactly($code, $resp)
    {
        $this->assertInstanceOf(Response::Class, $resp);
        $this->assertEquals(
            $code,
            $resp->getResponseCode(),
            "Expected response code $code, got ".$resp->getResponseCode().PHP_EOL.json_encode($resp->toArray())
        );
    }
}
