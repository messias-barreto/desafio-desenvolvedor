<?php

namespace App\Http\Controllers\UploadFile;

use App\Http\Controllers\Controller;
use App\Services\UploadFile\FindUploadFileByCreatedDateService;
use Illuminate\Http\JsonResponse;

class FindUploadFileByCreatedDateController extends Controller
{
    public function __construct(
        private readonly FindUploadFileByCreatedDateService $service
    ) {}

    public function handle(string $createdDate): JsonResponse
    {
        $response = $this->service->execute($createdDate);
        return response()->json($response, 200);
    }
}
