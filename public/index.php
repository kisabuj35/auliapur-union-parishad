<?php

define('LARAVEL_START', microtime(true));

// Laravel configuration is not included in this lightweight deployment.
if (!file_exists(__DIR__.'/../config/app.php')) {
    require_once __DIR__.'/../routes/web_direct.php';
    exit;
}

// অটোলোডার চেক
if (file_exists(__DIR__.'/../vendor/autoload.php')) {
    require __DIR__.'/../vendor/autoload.php';
} else {
    // যদি ভেন্ডর ডাউনলোড না থাকে, তবুও সাইট ক্র্যাশ না করে সরাসরি ভিউ লোড করবে
    require_once __DIR__.'/../routes/web_direct.php';
    exit;
}

$app = require_once __DIR__.'/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);

$response = $kernel->handle(
    $request = Illuminate\Http\Request::capture()
)->send();

$kernel->terminate($request, $response);
