<?php

namespace Blackburn29\TwitterAds\Accounts;

use Blackburn29\TwitterAds\Accounts\AccountRequest;
use Blackburn29\TwitterAds\HttpMethods;

class AccountRequestTest extends \Blackburn29\TwitterAds\UnitTestCase
{
    private $twitter;

    public function testAccountsCanBeFetchedViaTheApiSuccessfully()
    {
        $this->assertNotNull(
            $this->getAccountFromTwitter()
        );
    }

    public function testUserMetaDataForAccountAssociationCanBeAuthenticatedAndRetrievedFromTheApi()
    {
        $account = $this->getAccountFromTwitter();

        $request = new AccountRequest('accounts/:account_id/authenticated_user_access', [
            'account_id' => $account['id'],
        ]);

        $response = $this->twitter->send($request);
        $this->assertSuccessfulResponse($response);

        $data = $response->getData();

        $this->assertArrayHasKey('user_id', $data);
        $this->assertArrayHasKey('permissions', $data);
    }

    public function testMetadataForAnAccountCanBeFetchedViaTheApiSuccessfully()
    {
        $account = $this->getAccountFromTwitter();

        $request = new AccountRequest('accounts/:account_id', [
            'account_id' => $account['id'],
        ]);

        $response = $this->twitter->send($request);
        $this->assertSuccessfulResponse($response);
    }

    /**
     * @group applist
     */
    public function testAccountListsCanBeFetchedAndModifiedFromTheApi()
    {
        $account = $this->getAccountFromTwitter();

        $request = new AccountRequest('accounts/:account_id/app_lists', [
            'account_id' => $account['id'],
            'form_params' => [
                'name' => 'test list',
                'app_store_identifiers' => 'com.twitter.android'
            ],
        ]);

        $response = $this->twitter->send($request);
        $this->assertSuccessfulResponse($response);
        $this->assertArrayHasKey('id', $response->getData());
        $listId = $response->getData()['id'];

        $request = new AccountRequest(
            'accounts/:account_id/app_lists',
            ['account_id' => $account['id']],
            [],
            HttpMethods::GET
        );

        $response = $this->twitter->send($request);
        $this->assertSuccessfulResponse($response);

        $data = $response->getData();

        $found = false;
        foreach($data as $list) {
            if ($list['id'] === $listId) {
                $found = true;
                break;
            }
        }

        $this->assertTrue($found);
    }

    public function testAccountFeaturesCanBeFetchedFromTheApi()
    {
        $account = $this->getAccountFromTwitter();

        $request = new AccountRequest('accounts/:account_id/features', [
            'account_id' => $account['id'],
        ]);

        $response = $this->twitter->send($request);
        $this->assertSuccessfulResponse($response);
    }

    protected function setUp()
    {
        $this->twitter = $this->getTwitterAds();
    }
}
