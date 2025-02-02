<?php

namespace App\Services\UploadFile;

use App\Contract\UploadFileContract;
use App\Contract\UploadFileItemContract;
use App\Exceptions\BadRequestException;
use App\Exceptions\ConflictException;
use App\Jobs\FileUploadJob;
use App\Services\UploadFile\Adapter\ProcessCsvFileAdapter;
use App\Services\UploadFile\Adapter\ProcessXlsFileAdapter;
use Exception;
use Illuminate\Support\Facades\DB;

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
        if ($uploadFileAlreadyExists) {
            throw new ConflictException('O Arquivo Enviado Já Consta em Nosso Sistema');
        }

        try {
            DB::beginTransaction();
            $uploadFile = $this->repository->create([
                'name' => $fileName,
                'upload_file_status_id' => 1
            ]);

            $fileExtencion = $file->getClientOriginalExtension();
            $filePath = $file->storeAs('uploads', $fileName, 'local');
            $fullPath = storage_path("app/$filePath");

            if($fileExtencion === 'csv') {
                $fileContent = file($fullPath); // Lê o arquivo linha por linha, retornando um array
                array_shift($fileContent);
                file_put_contents($fullPath, implode('', $fileContent));
            }
            
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
        } catch (Exception $e) {
            DB::rollBack();
            throw new BadRequestException('Não foi Possível Processar o Arquivo Enviado, Porfavor Tente Novamente');
        }
    }

    public function processFile(array $data, string $type): void
    {
        $data = match ($type) {
            'csv' => $this->processCsvFileAdapter->processItem($data['fileName'], $data['uploadFileId']),
            'xlsx' => $this->processXlsFileAdapter->processItem($data['fileName'], $data['uploadFileId'])
        };
    }
}
