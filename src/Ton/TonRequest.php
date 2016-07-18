<?php

namespace Blackburn29\TwitterAds\Ton;

use Blackburn29\TwitterAds\Request;
use Blackburn29\TwitterAds\HttpMethods;

class TonRequest extends Request
{
    const BASE_URL = 'https://ton.twitter.com/1.1/';
    const ADS_BUCKET = 'ta_partner';

    const ROUTES = [
        'ton/bucket/:bucket'                                    => HttpMethods::POST,
        'ton/bucket/:bucket?resumable=true'                     => HttpMethods::POST,
        'ton/bucket/:bucket/:id/:file?resumable=true&resumeId=:resume_id' => HttpMethods::PUT,
    ];
}
