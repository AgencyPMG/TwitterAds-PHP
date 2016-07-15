<?php

namespace PMG\TwitterAds\Accounts;

use PMG\TwitterAds\HttpMethods;
use PMG\TwitterAds\Request;

class AccountRequest extends Request
{
    /**
     * A list of accepted API endpoints and their default http method. You can 
     * override the http method via the constructor.
     */
    const ROUTES = [
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

    private $method;
    private $url;
    private $parameters;
    private $headers;

    /**
     * Initiates a network request
     *
     * @param $url string - the url to request
     * @param $params array - route and body parameters
     * @param $headers array - headers to send
     * @param $method string|null - an optional override for the default http method
     */
    public function __construct($url, $params=[], $headers=[], $method=null)
    {
        list($this->url, $this->method) = $this->assureUrl($url);
        $this->parameters = $params;
        $this->headers = $headers;

        if ($method) {
            $this->method = $method;
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getMethod()
    {
        return $this->method;
    }

    /**
     * {@inheritdoc}
     */
    public function getUrl()
    {
        return $this->url;
    }

    /**
     * {@inheritdoc}
     */
    public function getParameters()
    {
        return $this->parameters;
    }

    /**
     * {@inheritdoc}
     */
    public function getHeaders()
    {
        return $this->headers;
    }
}
