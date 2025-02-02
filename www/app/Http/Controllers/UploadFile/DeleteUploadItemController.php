<?php

namespace App\Http\Controllers\UploadFile;

use App\Http\Controllers\Controller;
use App\Services\UploadFile\DeleteUploadItemService;
use Illuminate\Http\JsonResponse;

class DeleteUploadItemController extends Controller
{
    public function __construct(
        private readonly DeleteUploadItemService $service
    ) {}

    /**
     * Deletar Arquivo
     *
     * <p>Este Endpoint Realiza a <strong>Remoção das Informaçõe do Arquivo no Banco de Dados</strong>. Para que Seja realizado a Remoção das Informações o Arquivo não pode Estar com o Status 1 (Processando)</p>.
     *
     * @authenticated
     * @header Authorization Bearer {ACCESS_TOKEN}
     * @urlParam name string required Example: arquivotest.csv
     * @response 200 { 
     *     "message": "message": "Arquivo foi Excluido com Sucesso!", 
     * }
     * @response 401 {
     *      "message": "Unauthenticated."
     * }
     * @response 404 { 
     *      "message": "O Arquivo Não foi Encontrado em Nossa Base de Dados"
     * }
     */

    public function handle(string $name): JsonResponse
    {
        $response = $this->service->execute($name);
        return response()->json($response, 200);
    }
}
