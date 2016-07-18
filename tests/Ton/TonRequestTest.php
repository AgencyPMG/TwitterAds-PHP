<?php

namespace Blackburn29\TwitterAds\Ton;

class TonRequestTest extends \Blackburn29\TwitterAds\UnitTestCase
{
    /**
     * @group ton
     */
    public function testSingleFilesCanBeUploadedViaTheApi()
    {
        $file = new \SplFileObject(__DIR__.'/../test.csv');

        $content = $file->fread($file->getSize());

        $request = new TonRequest('ton/bucket/:bucket', [
            'bucket' => TonRequest::ADS_BUCKET,
            'body' => $content,
        ], [
            'Content-Type'   => 'text/csv',
            'X-TON-Expires'  => $this->getExpiration(),
        ]);

        $response = $this->twitter->send($request);
        $this->assertSuccessfulResponse($response);
        $this->assertEquals(201, $response->getResponseCode());
        $this->assertArrayHasKey('location', $response->getHeaders());
        $this->assertNotNull($response->getHeaders()['location']);
    }

    /**
     * @group ton-multi
     */
    public function testMultiChunkFilesCanBeUploadedViaTheApi()
    {
        $this->markTestSkipped();
        $file = new \SplFileObject(__DIR__.'/../emails_large.csv');

        list($location, $chunkSize) = $this->initalizeMultiChunkUpload($file);

        $read = 0;
        while ($contents = $file->fread($chunkSize)) {
            $start = $read;
            $read += strlen($contents);
            $this->uploadChunk($location, $contents, $start, $read, $file->getSize());
        }
    }

    private function initalizeMultiChunkUpload($file)
    {
        $request = new TonRequest('ton/bucket/:bucket?resumable=true', [
            'bucket' => TonRequest::ADS_BUCKET,
        ], [
            'Content-Type'   => 'text/comma-separated-values',
            'X-TON-Content-Type' => 'text/comma-separated-values',
            'X-TON-Expires'  => $this->getExpiration(),
            'X-TON-Content-Length' => $file->getSize(),
        ]);

        $response = $this->twitter->send($request);
        $this->assertSuccessfulResponse($response);
        $this->assertEquals(201, $response->getResponseCode());
        $this->assertArrayHasKey('location', $response->getHeaders());
        $this->assertArrayHasKey('x-ton-max-chunk-size', $response->getHeaders());
        $this->assertNotNull($response->getHeaders()['location']);

        $location = $response->getHeaders()['location'];
        $chunk = intval($response->getHeaders()['x-ton-min-chunk-size']);

        return [$location, $chunk];
    }

    private function uploadChunk($location, $contents, $start, $read, $total)
    {
        preg_match_all('/[^\d.\d]+([\d]+)\/([^?]+)[^&]+&[^\d]+([\d]+)/', $location, $matches);
        $this->assertCount(4, $matches);

        $request = new TonRequest('ton/bucket/:bucket/:id/:file?resumable=true&resumeId=:resume_id', [
            'bucket' => TonRequest::ADS_BUCKET,
            'id'     => $matches[1][0],
            'file'   => $matches[2][0],
            'resume_id' => $matches[3][0],
            'body'  => $contents,
        ], [
            'Content-Type' => 'text/comma-separated-values',
            'Content-Range' => sprintf('bytes %s-%s/%s', $start, ($read-1), $total),
        ]);

        $response = $this->twitter->send($request);
        $this->assertSuccessfulResponse($response);
    }

    protected function setUp()
    {
        $this->twitter = $this->getTwitterAds();
        $this->account = $this->getAccountFromTwitter();
    }

    private function getExpiration()
    {
        return (new \DateTime())->modify('+3 day')->format('D, d M Y H:i:s \G\M\T');
    }
}
