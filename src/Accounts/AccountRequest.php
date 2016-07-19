<?php

namespace Blackburn29\TwitterAds\Accounts;

use Blackburn29\TwitterAds\HttpMethods;
use Blackburn29\TwitterAds\Request;

/**
 * Request object for account related actions
 *
 * @since 2016-07-13
 */
class AccountRequest extends Request
{
    /**
     * {@inheritdoc}
     */
    protected function getRoutes()
    {
        return [
            'accounts'                                       => HttpMethods::GET,
            'accounts/:account_id'                           => HttpMethods::GET,
            'accounts/:account_id/features'                  => HttpMethods::GET,
            'accounts/:account_id/tweet'                     => HttpMethods::POST,
            'accounts/:account_id/app_lists'                 => HttpMethods::POST,
            'accounts/:account_id/authenticated_user_access' => HttpMethods::GET,
            'accounts/:account_id/funding_instruments'       => HttpMethods::GET,
            'accounts/:account_id/funding_instruments/:id'   => HttpMethods::GET,
            'accounts/:account_id/line_items'                => HttpMethods::GET,
            'accounts/:account_id/line_items/:list_item_id'  => HttpMethods::GET,
            'accounts/:account_id/targeting_criteria'        => HttpMethods::GET,
            'accounts/:account_id/targeting_criteria/:id'    => HttpMethods::GET,
            'accounts/:account_id/promotable_users'          => HttpMethods::GET,
            'accounts/:account_id/campaigns'                 => HttpMethods::GET,
            'accounts/:account_id/campaigns/:campaign_id'    => HttpMethods::GET,
            'accounts/:preroll_call_to_actions/campaigns'    => HttpMethods::GET,
            'accounts/:account_id/preroll_call_to_actions'   => HttpMethods::GET,
            'accounts/:account_id/preroll_call_to_actions/'
                .':preroll_call_to_action_id'                => HttpMethods::DELETE,
            'accounts/:account_id/line_item_apps'            => HttpMethods::GET,
            'accounts/:account_id/line_item_apps/'
                .':line_item_app_id'                         => HttpMethods::GET,
        ];
    }
}
