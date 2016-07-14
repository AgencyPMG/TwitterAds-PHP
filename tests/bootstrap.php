<?php

$loader = require __DIR__.'/../vendor/autoload.php';
$loader->addPsr4('PMG\\TwitterAds\\', __DIR__);

$dotenv = new Dotenv\Dotenv(__DIR__);
$dotenv->load();
