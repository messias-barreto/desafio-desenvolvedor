<?php

namespace App\Http\Controllers\UploadFile\UploadFileItem;

use App\Http\Controllers\Controller;
use App\Services\UploadFile\UploadFileItem\FindItemsByUploadFileNameService;
use Illuminate\Http\JsonResponse;

class FindItemsByUploadFileNameController extends Controller
{
    public function __construct(
        private readonly FindItemsByUploadFileNameService $service
    ) {}

    public function handle(string $name): JsonResponse
    {
        $response = $this->service->execute($name);
        return response()->json($response, 200);
    }
}
