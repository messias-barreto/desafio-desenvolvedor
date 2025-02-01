<?php

namespace App\Http\Controllers\UploadFile;

use App\Http\Controllers\Controller;
use App\Services\UploadFile\FindUploadFileByNameService;
use Illuminate\Http\JsonResponse;

class FindUploadFileByNameController extends Controller
{
    public function __construct(
        private readonly FindUploadFileByNameService $service
    ) {}

    public function handle(string $name): JsonResponse
    {
        $response = $this->service->execute($name);
        return response()->json($response, 200);
    }
}
