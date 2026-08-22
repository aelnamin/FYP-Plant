<?php

declare(strict_types=1);

/*
|--------------------------------------------------------------------------
| Vercel serverless entry point
|--------------------------------------------------------------------------
|
| Vercel Functions can only write to /tmp. Configure Laravel's generated
| files to use that directory before the framework is bootstrapped. Values
| explicitly configured in Vercel remain authoritative.
|
*/

$isVercel = getenv('VERCEL') !== false;

if ($isVercel) {
    $tmp = '/tmp/laravel';

    foreach ([
        $tmp,
        "$tmp/framework/cache/data",
        "$tmp/framework/sessions",
        "$tmp/framework/views",
    ] as $directory) {
        if (! is_dir($directory)) {
            if (! mkdir($directory, 0755, true) && ! is_dir($directory)) {
                throw new RuntimeException("Unable to create Laravel runtime directory: $directory");
            }
        }
    }

    $defaults = [
        'APP_CONFIG_CACHE' => "$tmp/config.php",
        'APP_EVENTS_CACHE' => "$tmp/events.php",
        'APP_PACKAGES_CACHE' => "$tmp/packages.php",
        'APP_ROUTES_CACHE' => "$tmp/routes.php",
        'APP_SERVICES_CACHE' => "$tmp/services.php",
        'CACHE_DRIVER' => 'array',
        'LOG_CHANNEL' => 'stderr',
        'SESSION_DRIVER' => 'cookie',
        'VIEW_COMPILED_PATH' => "$tmp/framework/views",
    ];

    foreach ($defaults as $name => $value) {
        if (getenv($name) === false) {
            putenv("$name=$value");
            $_ENV[$name] = $value;
            $_SERVER[$name] = $value;
        }
    }
}

// Make Laravel generate URLs and resolve the front controller correctly.
$_SERVER['SCRIPT_FILENAME'] = dirname(__DIR__).'/public/index.php';
$_SERVER['SCRIPT_NAME'] = '/index.php';

require dirname(__DIR__).'/public/index.php';
