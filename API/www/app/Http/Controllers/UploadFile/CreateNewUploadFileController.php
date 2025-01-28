<?php

namespace App\Http\Controllers\UploadFile;

use App\Http\Controllers\Controller;
use App\Services\UploadFile\CreateNewUploadFileService;
use Illuminate\Http\Request;

class CreateNewUploadFileController extends Controller
{
    public function __construct(
        private CreateNewUploadFileService $service
    ) {
    }

    public function handle(Request $request)
    {
        return $this->service->execute($request->all());
    }
}
