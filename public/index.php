<?php

use Illuminate\Http\Request;

define('LARAVEL_START', microtime(true));

// মেইনটেন্যান্স মোড চেক
if (file_exists($maintenance = __DIR__.'/../storage/framework/maintenance.php')) {
    require $maintenance;
}

// অটোলোডার রেজিস্টার
require __DIR__.'/../vendor/autoload.php';

// অ্যাপ্লিকেশন বুটস্ট্র্যাপ
$app = require_once __DIR__.'/../bootstrap/app.php';

$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);

$response = $kernel->handle(
    $request = Request::capture()
)->send();

$kernel->terminate($request, $response);
