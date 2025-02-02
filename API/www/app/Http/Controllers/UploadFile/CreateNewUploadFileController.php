<?php

namespace App\Http\Controllers\UploadFile;

use App\Http\Controllers\Controller;
use App\Http\Requests\UploadFileRequest;
use App\Services\UploadFile\CreateNewUploadFileService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CreateNewUploadFileController extends Controller
{
    public function __construct(
        private CreateNewUploadFileService $service
    ) {
    }

    public function handle(UploadFileRequest $request): JsonResponse
    {
        $response = $this->service->execute($request->all());
        return response()->json($response, 201);
    }
}
