<?php

if (getenv('VERCEL')) {
    $tmpRoot = '/tmp/laravel';
    $tmpStorage = $tmpRoot.'/storage';
    $tmpCache = $tmpRoot.'/bootstrap/cache';

    $setDefault = static function (string $key, string $value): void {
        $current = getenv($key);
        if ($current !== false && $current !== '') {
            return;
        }

        putenv($key.'='.$value);
        $_ENV[$key] = $value;
        $_SERVER[$key] = $value;
    };

    foreach ([
        $tmpStorage.'/framework/cache/data',
        $tmpStorage.'/framework/sessions',
        $tmpStorage.'/framework/views',
        $tmpStorage.'/logs',
        $tmpCache,
    ] as $path) {
        if (!is_dir($path)) {
            mkdir($path, 0777, true);
        }
    }

    $setDefault('APP_STORAGE_PATH', $tmpStorage);
    $setDefault('VIEW_COMPILED_PATH', $tmpStorage.'/framework/views');
    $setDefault('APP_SERVICES_CACHE', $tmpCache.'/services.php');
    $setDefault('APP_PACKAGES_CACHE', $tmpCache.'/packages.php');
    $setDefault('APP_CONFIG_CACHE', $tmpCache.'/config.php');
    $setDefault('APP_ROUTES_CACHE', $tmpCache.'/routes-v7.php');
    $setDefault('APP_EVENTS_CACHE', $tmpCache.'/events.php');
    $setDefault('LOG_CHANNEL', 'stderr');
}

require __DIR__ . '/../public/index.php';
