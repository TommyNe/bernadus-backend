<?php

namespace App\Http\Controllers;

use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;

class OpenApiDocumentationController extends Controller
{
    public function json(): JsonResponse
    {
        /** @var array<string, mixed> $spec */
        $spec = json_decode(file_get_contents(base_path('openapi.json')), true, 512, JSON_THROW_ON_ERROR);

        return response()->json($spec);
    }

    public function index(): View
    {
        return view('docs.openapi');
    }
}
