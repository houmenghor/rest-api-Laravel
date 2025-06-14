<?php

// This file is the entry point for Vercel's serverless function.
// It loads the Laravel application.

// Autoload Composer dependencies
require __DIR__ . '/../vendor/autoload.php';

// Create the Laravel application instance
$app = require_once __DIR__ . '/../bootstrap/app.php';

// Handle the incoming request
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);

$response = $kernel->handle(
    $request = Illuminate\Http\Request::capture()
);

$response->send();

$kernel->terminate($request, $response);
