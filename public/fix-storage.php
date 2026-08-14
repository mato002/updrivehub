<?php

declare(strict_types=1);

header('Content-Type: text/plain; charset=utf-8');

$base = dirname(__DIR__);
$secret = (string) ($_SERVER['HTTP_X_DEPLOY_SECRET'] ?? '');

$envPath = $base.'/.env';
$expectedSecret = null;

if (is_readable($envPath)) {
    foreach (file($envPath, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES) ?: [] as $line) {
        $line = trim($line);

        if ($line === '' || str_starts_with($line, '#')) {
            continue;
        }

        if (str_starts_with($line, 'DEPLOY_SECRET=')) {
            $value = substr($line, strlen('DEPLOY_SECRET='));
            $expectedSecret = trim($value, " \t\n\r\0\x0B\"'");
            break;
        }
    }
}

if ($expectedSecret === null || $expectedSecret === '' || ! hash_equals($expectedSecret, $secret)) {
    http_response_code(401);
    exit("Unauthorized\n");
}

$directories = [
    $base.'/storage/app/public',
    $base.'/storage/framework/cache/data',
    $base.'/storage/framework/sessions',
    $base.'/storage/framework/views',
    $base.'/storage/logs',
    $base.'/bootstrap/cache',
];

foreach ($directories as $directory) {
    if (! is_dir($directory) && ! mkdir($directory, 0775, true) && ! is_dir($directory)) {
        echo "Failed to create: {$directory}\n";
        continue;
    }

    @chmod($directory, 0775);
    echo (is_writable($directory) ? 'OK' : 'NOT writable').": {$directory}\n";
}

echo "\nDone.\n";
