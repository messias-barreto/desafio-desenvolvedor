<?php

namespace App\Http\Controllers\UploadFile\UploadFileItem;

use App\Http\Controllers\Controller;
use App\Services\UploadFile\UploadFileItem\FindItemsByUploadFileNameService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class FindItemsByUploadFileNameController extends Controller
{
    public function __construct(
        private readonly FindItemsByUploadFileNameService $service
    ) {}

    public function handle(Request $request, string $name): JsonResponse
    {
        $filters = $request->query();
        $response = $this->service->execute($name, $filters);
        return response()->json($response, 200);
    }
}
