<?php

use App\Http\Controllers\UploadFile\CreateNewUploadFileController;
use App\Http\Controllers\UploadFile\FindUploadFileByCreatedDateController;
use App\Http\Controllers\UploadFile\FindUploadFileByNameController;
use App\Http\Controllers\UploadFile\UploadFileItem\FindItemsByUploadFileNameController;
use Illuminate\Support\Facades\Route;

Route::middleware('client')->group(function () {
    Route::post('upload-file', [CreateNewUploadFileController::class, 'handle']);
    Route::get('upload-file/{name}', [FindUploadFileByNameController::class, 'handle']);
    Route::get('upload-file/item/{name}', [FindItemsByUploadFileNameController::class, 'handle']);
    Route::get('upload-file-date/{createdDate}', [FindUploadFileByCreatedDateController::class, 'handle']);
});