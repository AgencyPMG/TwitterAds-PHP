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
use PMG\TwitterAds\Request;

/**
 * Request object for tailored audience related actions
 *
 * @since 2016-07-14
 */
class TailoredAudienceRequest extends Request
{
    /**
     * {@inheritdoc}
     */
    protected function getRoutes()
    {
        return [
            'accounts/:account_id/tailored_audience_memberships'         => HttpMethods::POST,
            'accounts/:account_id/tailored_audiences'                    => HttpMethods::GET,
            'accounts/:account_id/tailored_audiences/:id'                => HttpMethods::GET,
            'accounts/:account_id/tailored_audiences/:id/permissions'    => HttpMethods::GET,
            'accounts/:account_id/tailored_audiences/:id/reach_estimate' => HttpMethods::GET,
            'accounts/:account_id/tailored_audience_changes'            => HttpMethods::GET,
            'accounts/:account_id/tailored_audiences/global_opt_out'     => HttpMethods::PUT,
        ];
    }
}
