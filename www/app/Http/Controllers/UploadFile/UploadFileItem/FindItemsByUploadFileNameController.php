<?php

namespace App\Http\Controllers\UploadFile\UploadFileItem;

use App\Http\Controllers\Controller;
use App\Services\UploadFile\UploadFileItem\FindItemsByUploadFileNameService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class FindItemsByUploadFileNameController extends Controller
{
    public function __construct(
        private readonly FindItemsByUploadFileNameService $service
    ) {}

    /**
     * Buscar Informações do Arquivo
     *
     * <p>Este Endpoint Realiza a Busca, e retorno das informações, referente aos itens(linhas) do arquivos, podendo passar os filtros de busca pelo params</p>.
     * @authenticated
     * @urlParam name required string Example: arquivo_teste.csv
     * @urlParam params string 
     * Pode Passar, como parametro da requisição, qualquer valor relacionado ao Arquivo para realizar o filtro das informações.
     * Example: TckrSymb=AMZO34
     * @header Authorization Bearer {ACCESS_TOKEN}
     * @response 200 {
     *     "message": "Itens Foram Encontrados com Sucesso",
     *     "arquivo": "InstrumentsConsolidatedFile_20250128_1.csv",
     *     "data": {
     *         "current_page": 1,
     *          "data": [
     *          {
     *           "id": 2204372,
     *           "RptDt": "2025-01-28",
     *           "TckrSymb": "003H11",
     *           "Asst": "003H",
     *           "AsstDesc": "003H",
     *           "SgmtNm": "CASH",
     *           "MktNm": "EQUITY-CASH",
     *           "SctyCtgyNm": "FUNDS",
     *           "XprtnDt": "",
     *           "XprtnCd": "",
     *           "TradgStartDt": "9999-12-31",
     *           "TradgEndDt": "9999-12-31",
     *           "BaseCd": "",
     *           "ConvsCritNm": "",
     *           "MtrtyDtTrgtPt": "",
     *           "ReqrdConvsInd": "",
     *           "ISIN": "BR003HCTF006",
     *           "CFICd": "CICGRY",
     *           "DlvryNtceStartDt": "",
     *           "DlvryNtceEndDt": "",
     *           "OptnTp": "",
     *           "CtrctMltplr": "",
     *           "AsstQtnQty": "",
     *           "AllcnRndLot": "1",
     *           "TradgCcy": "BRL",
     *           "DlvryTpNm": "",
     *           "WdrwlDays": "",
     *           "WrkgDays": "",
     *           "ClnrDays": "",
     *           "RlvrBasePricNm": "",
     *           "OpngFutrPosDay": "",
     *           "SdTpCd1": "",
     *           "UndrlygTckrSymb1": "",
     *           "SdTpCd2": "",
     *           "UndrlygTckrSymb2": "",
     *           "PureGoldWght": "",
     *           "ExrcPric": "",
     *           "OptnStyle": "",
     *           "ValTpNm": "",
     *           "PrmUpfrntInd": "",
     *           "OpngPosLmtDt": "",
     *           "DstrbtnId": "100",
     *           "PricFctr": "1",
     *           "DaysToSttlm": "2",
     *           "SrsTpNm": "",
     *           "PrtcnFlg": "",
     *           "AutomtcExrcInd": "",
     *           "SpcfctnCd": "CI",
     *           "CrpnNm": "KINEA CO-INVESTIMENTO FDO INV IMOB",
     *           "CorpActnStartDt": "9999-12-31",
     *           "CtdyTrtmntTpNm": "FUNGIBLE",
     *           "MktCptlstn": "15000",
     *           "CorpGovnLvlNm": "",
     *           "upload_file_id": 122,
     *           "created_at": null,
     *           "updated_at": null
     *       },
     *       ...
     *   ],
     *   "first_page_url": "http://127.0.0.1:8084/api/upload-file/item/InstrumentsConsolidatedFile_20250128_1.csv?page=1",
     *   "from": 1,
     *   "last_page": 1533,
     *   "last_page_url": "http://127.0.0.1:8084/api/upload-file/item/InstrumentsConsolidatedFile_20250128_1.csv?page=1533",
     *   "links": [
     *       {
     *           "url": null,
     *           "label": "&laquo; Previous",
     *           "active": false
     *       },
     *       {
     *           "url": "http://127.0.0.1:8084/api/upload-file/item/InstrumentsConsolidatedFile_20250128_1.csv?page=1",
     *           "label": "1",
     *           "active": true
     *       },
     *       {
     *           "url": "http://127.0.0.1:8084/api/upload-file/item/InstrumentsConsolidatedFile_20250128_1.csv?page=2",
     *           "label": "2",
     *           "active": false
     *       },
     *       {
     *           "url": "http://127.0.0.1:8084/api/upload-file/item/InstrumentsConsolidatedFile_20250128_1.csv?page=3",
     *           "label": "3",
     *           "active": false
     *       },
     *       {
     *           "url": "http://127.0.0.1:8084/api/upload-file/item/InstrumentsConsolidatedFile_20250128_1.csv?page=4",
     *           "label": "4",
     *           "active": false
     *       },
     *       {
     *           "url": "http://127.0.0.1:8084/api/upload-file/item/InstrumentsConsolidatedFile_20250128_1.csv?page=5",
     *           "label": "5",
     *           "active": false
     *       },
     *       {
     *           "url": "http://127.0.0.1:8084/api/upload-file/item/InstrumentsConsolidatedFile_20250128_1.csv?page=6",
     *           "label": "6",
     *           "active": false
     *       },
     *       {
     *           "url": "http://127.0.0.1:8084/api/upload-file/item/InstrumentsConsolidatedFile_20250128_1.csv?page=7",
     *           "label": "7",
     *           "active": false
     *       },
     *       {
     *           "url": "http://127.0.0.1:8084/api/upload-file/item/InstrumentsConsolidatedFile_20250128_1.csv?page=8",
     *           "label": "8",
     *           "active": false
     *       },
     *       {
     *           "url": "http://127.0.0.1:8084/api/upload-file/item/InstrumentsConsolidatedFile_20250128_1.csv?page=9",
     *           "label": "9",
     *           "active": false
     *       },
     *       {
     *           "url": "http://127.0.0.1:8084/api/upload-file/item/InstrumentsConsolidatedFile_20250128_1.csv?page=10",
     *           "label": "10",
     *           "active": false
     *       },
     *       {
     *           "url": null,
     *           "label": "...",
     *           "active": false
     *       },
     *       {
     *           "url": "http://127.0.0.1:8084/api/upload-file/item/InstrumentsConsolidatedFile_20250128_1.csv?page=1532",
     *           "label": "1532",
     *           "active": false
     *       },
     *       {
     *           "url": "http://127.0.0.1:8084/api/upload-file/item/InstrumentsConsolidatedFile_20250128_1.csv?page=1533",
     *           "label": "1533",
     *           "active": false
     *       },
     *       {
     *           "url": "http://127.0.0.1:8084/api/upload-file/item/InstrumentsConsolidatedFile_20250128_1.csv?page=2",
     *           "label": "Next &raquo;",
     *           "active": false
     *       }
     *   ],
     *   "next_page_url": "http://127.0.0.1:8084/api/upload-file/item/InstrumentsConsolidatedFile_20250128_1.csv?page=2",
     *   "path": "http://127.0.0.1:8084/api/upload-file/item/InstrumentsConsolidatedFile_20250128_1.csv",
     *   "per_page": 50,
     *   "prev_page_url": null,
     *   "to": 50,
     *   "total": 76643
     * }
     *}
     * @response 401 {
     *      "message": "Unauthenticated."
     * }
     */

    public function handle(Request $request, string $name): JsonResponse
    {
        $filters = $request->query();
        $response = $this->service->execute($name, $filters);
        return response()->json($response, 200);
    }
}
