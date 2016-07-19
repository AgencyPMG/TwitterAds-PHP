<?php
/*
 * This file is part of pmg/twitterads
 *
 * (c) PMG <https://www.pmg.com>. All rights reserved.
 */

$loader = require __DIR__.'/../vendor/autoload.php';
$loader->addPsr4('PMG\\TwitterAds\\', __DIR__);

//Only load env file if it exists. 
if (file_exists(__DIR__.'/.env')) {
    $dotenv = new Dotenv\Dotenv(__DIR__);
    $dotenv->load();
}
