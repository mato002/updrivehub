<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;

class DeployController extends Controller
{
    public function migrate(Request $request): JsonResponse
    {
        $secret = config('app.deploy_secret');

        if ($secret === null || $secret === '') {
            return response()->json(['error' => 'Deploy hook is disabled.'], 403);
        }

        if (! hash_equals($secret, (string) $request->header('X-Deploy-Secret', ''))) {
            return response()->json(['error' => 'Unauthorized.'], 401);
        }

        Artisan::call('migrate', ['--force' => true]);

        return response()->json([
            'status' => 'ok',
            'output' => trim(Artisan::output()),
        ]);
    }
}
