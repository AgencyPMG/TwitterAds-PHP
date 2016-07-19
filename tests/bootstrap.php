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

$loader = require __DIR__.'/../vendor/autoload.php';
$loader->addPsr4('PMG\\TwitterAds\\', __DIR__);

//Only load env file if it exists. 
if (file_exists(__DIR__.'/.env')) {
    $dotenv = new Dotenv\Dotenv(__DIR__);
    $dotenv->load();
}
