<?php

namespace App\Services\UploadFile;

use App\Contract\UploadFileContract;
use App\Contract\UploadFileItemContract;
use App\Exceptions\ConflictException;
use App\Services\UploadFile\Adapter\ProcessCsvFileAdapter;
use Exception;
use Illuminate\Support\Facades\DB;
use League\Csv\Reader;

class CreateNewUploadFileService
{
    public function __construct(
        private readonly UploadFileContract $repository,
        private readonly ProcessCsvFileAdapter $processCsvFileAdapter,
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
            if($fileExtencion === 'csv') {
                $this->processCsvFileAdapter->processItem($data['arquivo'], $uploadFile->id);
            }
            DB::commit();
            
            return [
                'status' => 201,
                'message' => "O Arquivo {$fileName} foi Inserido no Sistema com Sucesso",
            ];

        }catch(Exception $e) {
            dd($e->getMessage());
        }
    }
}
