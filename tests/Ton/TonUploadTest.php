<?php

namespace Blackburn29\TwitterAds\Ton;

class TonUploadTest extends \Blackburn29\TwitterAds\UnitTestCase
{
    /**
     * @group ton-upload
     */
    public function testTonUploadWillUploadLargeFilesToTwitterViaTheApiSuccessfully()
    {
        $file = new \SplFileObject(__DIR__.'/../emails_large.csv');
        $ton = new TonUpload($this->twitter, $file, 'text/csv');

        $response = $ton->upload();

        $this->assertResponseCodeIsExactly(201, $response);
        $this->assertArrayHasKey('location', $response->getHeaders());
    }

    /**
     * @group ton-upload
     */
    public function testTonUploadWillUploadSmallFilesToTwitterViaTheApiSuccessfully()
    {
        $file = new \SplFileObject(__DIR__.'/../emails_small.csv');
        $ton = new TonUpload($this->twitter, $file, 'text/csv');

        $response = $ton->upload();

        $this->assertResponseCodeIsExactly(201, $response);
        $this->assertArrayHasKey('location', $response->getHeaders());
    }

    protected function setUp()
    {
        $this->twitter = $this->getTwitterAds();
    }
}
