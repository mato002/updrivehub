<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;

class DeployController extends Controller
{
    public function migrate(Request $request): JsonResponse
    {
        if ($response = $this->authorizeDeploy($request)) {
            return $response;
        }

        $this->ensureStorageDirectoriesExist();

        Artisan::call('migrate', ['--force' => true]);
        $output = trim(Artisan::output());

        Artisan::call('optimize:clear');
        $output .= "\n\n".trim(Artisan::output());

        return response()->json([
            'status' => 'ok',
            'output' => trim($output),
        ]);
    }

    public function setup(Request $request): JsonResponse
    {
        if ($response = $this->authorizeDeploy($request)) {
            return $response;
        }

        $this->ensureStorageDirectoriesExist();

        Artisan::call('optimize:clear');
        $output = trim(Artisan::output());

        return response()->json([
            'status' => 'ok',
            'output' => $output,
        ]);
    }

    private function authorizeDeploy(Request $request): ?JsonResponse
    {
        $secret = config('app.deploy_secret');

        if ($secret === null || $secret === '') {
            return response()->json(['error' => 'Deploy hook is disabled.'], 403);
        }

        if (! hash_equals($secret, (string) $request->header('X-Deploy-Secret', ''))) {
            return response()->json(['error' => 'Unauthorized.'], 401);
        }

        return null;
    }

    private function ensureStorageDirectoriesExist(): void
    {
        foreach ([
            storage_path('framework/cache/data'),
            storage_path('framework/sessions'),
            storage_path('framework/views'),
            storage_path('logs'),
        ] as $directory) {
            if (! is_dir($directory)) {
                mkdir($directory, 0775, true);
            }
        }
    }
}
