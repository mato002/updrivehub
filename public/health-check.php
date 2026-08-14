<?php

declare(strict_types=1);

header('Content-Type: text/plain; charset=utf-8');

$base = dirname(__DIR__);

echo "UpDriveHub health check\n";
echo 'PHP: '.PHP_VERSION."\n";
echo 'Time: '.date('c')."\n\n";

$checks = [
    'vendor/autoload.php' => $base.'/vendor/autoload.php',
    'carbon IntervalStep trait' => $base.'/vendor/nesbot/carbon/src/Carbon/Traits/IntervalStep.php',
    '.env' => $base.'/.env',
    'bootstrap/app.php' => $base.'/bootstrap/app.php',
    'storage' => $base.'/storage',
    'storage/logs' => $base.'/storage/logs',
    'storage/framework' => $base.'/storage/framework',
    'storage/framework/cache' => $base.'/storage/framework/cache',
    'storage/framework/sessions' => $base.'/storage/framework/sessions',
    'storage/framework/views' => $base.'/storage/framework/views',
    'bootstrap/cache' => $base.'/bootstrap/cache',
];

foreach ($checks as $label => $path) {
    if (is_file($path)) {
        echo "[OK] file exists: {$label}\n";
        continue;
    }

    if (is_dir($path)) {
        $writable = is_writable($path) ? 'writable' : 'NOT writable';
        echo "[OK] dir exists ({$writable}): {$label}\n";
        continue;
    }

    echo "[MISSING] {$label}\n";
}

echo "\nBootstrapping Laravel...\n";

try {
    require $base.'/vendor/autoload.php';
    $app = require $base.'/bootstrap/app.php';
    $kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
    $kernel->bootstrap();

    echo "Laravel boot: OK\n";
    echo 'APP_ENV: '.config('app.env')."\n";
    echo 'APP_DEBUG: '.(config('app.debug') ? 'true' : 'false')."\n";

    Illuminate\Support\Facades\DB::connection()->getPdo();
    echo "Database: OK\n";
} catch (Throwable $e) {
    http_response_code(500);
    echo "Laravel boot: FAILED\n";
    echo $e::class.': '.$e->getMessage()."\n";
    echo 'File: '.$e->getFile().':'.$e->getLine()."\n";
}
