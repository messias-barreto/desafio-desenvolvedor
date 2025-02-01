<?php

namespace App\Services\UploadFile;

use App\Contract\UploadFileContract;
use App\Contract\UploadFileItemContract;
use App\Exceptions\ConflictException;
use App\Jobs\FileUploadJob;
use App\Services\UploadFile\Adapter\ProcessCsvFileAdapter;
use App\Services\UploadFile\Adapter\ProcessXlsFileAdapter;
use Exception;
use Illuminate\Support\Facades\DB;
use League\Csv\Reader;

class CreateNewUploadFileService
{
    public function __construct(
        private readonly UploadFileContract $repository,
        private readonly ProcessCsvFileAdapter $processCsvFileAdapter,
        private readonly ProcessXlsFileAdapter $processXlsFileAdapter,
        private readonly UploadFileItemContract $itemRepository
    ) {}

    public function execute(array $data): array 
    {
        $file = $data['arquivo'];
        $fileName = $file->getClientOriginalName();
        $uploadFileAlreadyExists = $this->repository->findByName($fileName);
        if($uploadFileAlreadyExists) {
            throw new ConflictException('O Arquivo Enviado Já Consta em Nosso Sistema');
        }
        
        try {
            DB::beginTransaction();
            $uploadFile = $this->repository->create(['name' => $fileName]);
            $fileExtencion = $file->getClientOriginalExtension();

            // Salvar o arquivo na storage local
            $filePath = $file->storeAs('uploads', $fileName, 'local');

            // Obter o caminho completo do arquivo salvo
            $fullPath = storage_path("app/$filePath");

            $fileData = [
                'fileName' => $fullPath,
                'uploadFileId' => $uploadFile->id
            ];

            #$this->processFile($fileData, $fileExtencion);  

            FileUploadJob::dispatch($fileData, $fileExtencion);
            DB::commit();
            
            return [
                'status' => 201,
                'message' => "O Arquivo {$fileName} Está Sendo Processado!",
            ];

        }catch(Exception $e) {
            dd($e->getMessage());
        }
    }

    public function processFile(array $data, string $type): void 
    {
        $data = match ($type) {
            'csv' => $this->processCsvFileAdapter->processItem($data['fileName'], $data['uploadFileId']),
            'xls' => $this->processXlsFileAdapter->processItem($data['fileName'], $data['uploadFileId'])
        };
    }
}
