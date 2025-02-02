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

    /**
     * Buscar Arquivos Por Nome
     *
     * <p>Este Endpoint Realiza a Busca, e retorno das informações, referente aos arquivos, pelo nome do Arquivo</p>.
     *
     * @authenticated
     * @header Authorization Bearer {ACCESS_TOKEN}
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
     * @response 200 {
     *       "message": ""message": "Não foi Encontrado Nenhum Arquivo com Esse Nome",
     *   }
     * @response 401 {
     *      "message": "Unauthenticated."
     * }
     */

    public function handle(string $name): JsonResponse
    {
        $response = $this->service->execute($name);
        return response()->json($response, 200);
    }
}
