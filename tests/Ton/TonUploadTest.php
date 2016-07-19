<?php
/*
 * This file is part of pmg/twitterads
 *
 * (c) PMG <https://www.pmg.com>. All rights reserved.
 */

namespace PMG\TwitterAds\Ton;

class TonUploadTest extends \PMG\TwitterAds\UnitTestCase
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
