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
    ) {}

    /**
     * Realizar o Upload do Arquivo
     *
     * <p>Este Endpoint Realiza o <strong>Upload e Processamento do Arquivo</strong>. É Necessário para um valor chamado <strong>arquivo</strong> no body da requisição, nos formatos CSV ou XLSX</p>.
     *
     * @authenticated
     * @header Authorization Bearer {ACCESS_TOKEN}
     * @response 201 { 
     *     "message": "O Arquivo nome_arquivo Está Sendo Processado!", 
     * }
     * @response 409 { 
     *      "message": "O Arquivo Enviado Já Consta em Nosso Sistema"
     * }
     * @response 401 {
     *      "message": "Unauthenticated."
     * }
     */

    public function handle(UploadFileRequest $request): JsonResponse
    {
        $response = $this->service->execute($request->all());
        return response()->json($response, 201);
    }
}
