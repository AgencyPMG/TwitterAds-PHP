<?php

namespace Blackburn29\TwitterAds\Ton;

use Blackburn29\TwitterAds\Ton\Exception;

class TonUpload
{
    const MAX_SINGLE_CHUNK_SIZE = 1024 * 1024 * 8;

    private $file;
    private $contentType;

    public function __construct(\SplFileObject $file, $contentType)
    {
        $this->file = $file;
        $this->contentType = $contentType;
    }

    public function getSize()
    {
        return $this->file->getSize();
    }

    public function getContentType()
    {
        return $this->contentType;
    }

    public function upload()
    {
        if ($this->getSize() < self::MAX_SINGLE_CHUNK_SIZE) {
            return $this->uploadSingle();
        } else {
            list($location, $chunkSize) = $this->initalizeMultiChunkUpload();
            return $this->uploadChunked($location, $chunkSize);
        }
    }

    private function uploadSingle()
    {
        $content = $this->file->fread($this->getSize());

        $request = new TonRequest('ton/bucket/:bucket', [
            'bucket' => TonRequest::ADS_BUCKET,
            'body' => $content,
        ], [
            'Content-Type'   => 'text/csv',
            'X-TON-Expires'  => $this->getExpiration(),
        ]);

        $response = $this->twitter->send($request);

        if (201 !== $response->getStatusCode()) {
            throw new TonUploadFailed(sprintf(
                'Invalid response code when uploading file. %s',
                json_encode($response->getBody())
            ));
        }

        return $response;
    }

    private function uploadChunked($location, $chunkSize)
    {
        $resp = null;
        $read = 0;
        while ($contents = $file->fread($chunkSize)) {
            $start = $read;
            $read += strlen($contents);
            $resp = $this->uploadChunk($location, $contents, $start, $read);
        }

        return $resp;
    }

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

        if (201 !== $code = $response->getStatusCode()) {
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

        $code = $response->getStatusCode();

        if (201 !== $code || 308 !== $code) {
            throw new Exception\TonUploadFailed(sprintf(
                'Invalid response code when uploading chunk. %s',
                json_encode($response->getBody())
            ));
        }

        return $response;
    }
}
