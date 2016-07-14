<?php

namespace PMG\TwitterAds;

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

    protected function assertSuccessfulResponse($resp)
    {
        $this->assertInstanceOf(Response::Class, $resp);
        $this->assertThat($resp->getResponseCode(), $this->logicalAnd(
            $this->greaterThanOrEqual(200),
            $this->lessThan(300)
        ), 'Non Successful Response Status:'.PHP_EOL.json_encode($resp->toArray()));
    }
}
