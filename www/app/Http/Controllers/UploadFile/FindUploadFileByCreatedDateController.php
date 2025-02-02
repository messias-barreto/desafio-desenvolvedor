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

    /**
     * Buscar Arquivos Pela Data de Criação
     *
     * <p>Este Endpoint Realiza a Busca, e retorno das informações, referente aos arquivos, pela sua data de criação</p>.
     *
     * @authenticated
     * @header Authorization Bearer {ACCESS_TOKEN}
     * @urlParam createdDate string required Example: 2025-01-01.
     * @response 200 {
     *       "message": "Documento(s) foram Encontrado(s)",
     *       "data": [
     *           {
     *               "nome": "InstrumentsConsolidatedFile_20250128_1.csv",
     *               "status": "PROCESSADO",
     *               "data_criacao": "02-02-2025 16:47:49"
     *           }
     *       ]
     *   }
     * @response 400 {
     *       "message": "A Data Passada Não é Válida, ou Não está no formato Solicitado Y-m-d",
     *       "data": []
     *   }
     * @response 401 {
     *      "message": "Unauthenticated."
     * }
     * @response 404 { 
     *      "message": "Não foi Encontrado Nenhum Documento Adicionando no Dia Solicitado"
     * }
     */
    public function handle(string $createdDate): JsonResponse
    {
        $response = $this->service->execute($createdDate);
        return response()->json($response, 200);
    }
}
