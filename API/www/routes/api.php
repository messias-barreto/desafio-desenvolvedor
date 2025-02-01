<?php

use App\Http\Controllers\UploadFile\CreateNewUploadFileController;
use App\Http\Controllers\UploadFile\FindUploadFileByNameController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:api');

Route::post('upload-file', [CreateNewUploadFileController::class, 'handle']);
Route::get('upload-file/{name}', [FindUploadFileByNameController::class, 'handle']);
