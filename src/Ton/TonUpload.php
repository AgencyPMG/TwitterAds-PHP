<?php

namespace Blackburn29\TwitterAds\Ton;

use Blackburn29\TwitterAds\TwitterAds;
use Blackburn29\TwitterAds\Ton\Exception;

/**
 * A helper class for upload files to the TON API.
 * This class will choose the correct upload method automatically
 *
 * @since 2016-07-18
 */
class TonUpload
{
    const MAX_SINGLE_CHUNK_SIZE = 1024 * 1024 * 8;

    private $file;
    private $contentType;

    public function __construct(TwitterAds $twitter, \SplFileObject $file, $contentType)
    {
        $this->twitter = $twitter;
        $this->file = $file;
        $this->contentType = $contentType;
    }

    /**
     * Returns the size of the file in bytes
     *
     * @return int
     */
    public function getSize()
    {
        return $this->file->getSize();
    }

    /**
     * @return string
     */
    public function getContentType()
    {
        return $this->contentType;
    }

    /**
     * Uploads the file via the TON API
     *
     * @return Response
     */
    public function upload()
    {
        if ($this->getSize() < self::MAX_SINGLE_CHUNK_SIZE) {
            return $this->uploadSingle();
        } else {
            list($location, $chunkSize) = $this->initalizeMultiChunkUpload();
            return $this->uploadChunked($location, $chunkSize);
        }
    }

    /**
     * Performs a single chunk upload
     *
     * @return Request
     */
    private function uploadSingle()
    {
        $content = $this->file->fread($this->getSize());

        $request = new TonRequest('ton/bucket/:bucket', [
            'bucket' => TonRequest::ADS_BUCKET,
            'body' => $content,
        ], [
            'Content-Type'   => 'text/csv',
            'X-TON-Expires'  => self::getExpiration(),
        ]);

        $response = $this->twitter->send($request);

        if (201 !== $response->getResponseCode()) {
            throw new TonUploadFailed(sprintf(
                'Invalid response code when uploading file. %s',
                json_encode($response->getBody())
            ));
        }

        return $response;
    }

    /**
     * Reads a file by chunksize and uploads it
     *
     * @param $location string - the location url from the TON init call
     * @param @chunkSize int - the size of the chunk to read
     *
     * @return Response
     */
    private function uploadChunked($location, $chunkSize)
    {
        $resp = null;
        $read = 0;
        while ($contents = $this->file->fread($chunkSize)) {
            $start = $read;
            $read += strlen($contents);
            $resp = $this->uploadChunk($location, $contents, $start, $read);
        }

        return $resp;
    }

    /**
     * Initializes a multi chunk upload
     *
     * @throws Blackburn29\TwitterAds\Ton\Exception\TonInitializeFailed
     *
     * @return list - location url and chunk size
     */
    private function initalizeMultiChunkUpload()
    {
        $request = new TonRequest('ton/bucket/:bucket?resumable=true', [
            'bucket' => TonRequest::ADS_BUCKET,
        ], [
            'Content-Type'   => $this->contentType,
            'X-TON-Content-Type' => $this->contentType,
            'X-TON-Expires'  => $this->getExpiration(),
            'X-TON-Content-Length' => $this->getSize(),
        ]);

        $response = $this->twitter->send($request);

        if (201 !== $code = $response->getResponseCode()) {
            throw new Exception\TonInitializeFailed(sprintf(
                'Expected 201 response code. Got %s', $code
            ));
        }

        if (!isset($response->getHeaders()['location'])) {
            throw new Exception\TonInitializeFailed('Location header missing from response!');
        }
        if (!isset($response->getHeaders()['x-ton-max-chunk-size'])) {
            throw new Exception\TonInitializeFailed('x-ton-max-chunk-size header missing from response!');
        }

        $location = $response->getHeaders()['location'];
        $chunk = intval($response->getHeaders()['x-ton-max-chunk-size']) / 2;

        return [$location, $chunk];
    }

    /**
     * Uploads a chunk via the TON API
     *
     * @param $location string - the location url
     * @param $contents string - the file contents to send
     * @param $start int - the beginning index of bytes being sent
     * @param $read int - the total number of bytes read thus far
     *
     * @throws Blackburn29\TwitterAds\Ton\Exception\TonUploadFailed
     * @return Response
     */
    private function uploadChunk($location, $contents, $start, $read)
    {
        $request = new TonRequest(':location', [
            'location' => substr($location,strlen(TonRequest::API_VERSION)+2),
            'body'  => $contents,
        ], [
            'Content-Type' => $this->contentType,
            'Content-Range' => sprintf('bytes %s-%s/%s', $start, ($read-1), $this->getSize()),
        ]);

        $response = $this->twitter->send($request);

        $code = $response->getResponseCode();

        if (201 !== $code && 308 !== $code) {
            throw new Exception\TonUploadFailed(sprintf(
                'Invalid response code (%s) when uploading chunk. Response: %s',
                $code,
                json_encode($response->getBody())
            ));
        }

        return $response;
    }

    /**
     * Gets a HTML formatted expiration date 3 days into the future.
     *
     * @return DateTime
     */
    private static function getExpiration()
    {
        return (new \DateTime())->modify('+3 day')->format('D, d M Y H:i:s \G\M\T');
    }
}
