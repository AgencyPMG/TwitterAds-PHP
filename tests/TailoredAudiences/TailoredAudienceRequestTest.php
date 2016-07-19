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

namespace PMG\TwitterAds\TailoredAudiences;

use PMG\TwitterAds\HttpMethods;

class TailoredAudienceRequestTest extends \PMG\TwitterAds\UnitTestCase
{
    private $twitter, $account;

    public function testAudiencesCanBeFetchedForAnAccountFromTheApi()
    {
        $id = $this->account['id'];

        $request = new TailoredAudienceRequest('accounts/:account_id/tailored_audiences', [
            'account_id' => $id,
        ]);

        $response = $this->twitter->send($request);

        $data = $response->getData();
        $this->assertGreaterThan(0, count($data));
    }

    public function testAudiencesCanBeFetchedByIdFromTheApi()
    {
        $id = $this->account['id'];

        $request = new TailoredAudienceRequest('accounts/:account_id/tailored_audiences', [
            'account_id' => $id,
        ]);

        $response = $this->twitter->send($request);
        $this->assertSuccessfulResponse($response);

        $data = $response->getData();
        $this->assertGreaterThan(0, count($data));

        $audience = $data[0]['id'];

        $request = new TailoredAudienceRequest('accounts/:account_id/tailored_audiences/:id', [
            'account_id' => $id,
            'id'         => $audience,
        ]);

        $response = $this->twitter->send($request);
        $this->assertSuccessfulResponse($response);
    }

    /**
     * @group create-audience
     */
    public function testAudiencesCanBeCreatedFromTheApi()
    {
        $id = $this->account['id'];

        $request = new TailoredAudienceRequest('accounts/:account_id/tailored_audiences', [
            'account_id' => $id,
            'form_params' => [
                'name'  => 'TwitterAds Audience',
                'list_type' => 'EMAIL',
            ],
        ], [], HttpMethods::POST);

        $response = $this->twitter->send($request);
        $this->assertSuccessfulResponse($response);
    }

    protected function setUp()
    {
        $this->twitter = $this->getTwitterAds();
        $this->account = $this->getAccountFromTwitter();
    }
}
