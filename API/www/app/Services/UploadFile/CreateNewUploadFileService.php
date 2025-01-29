<?php

namespace App\Services\UploadFile;

use App\Contract\UploadFileContract;
use App\Contract\UploadFileItemContract;
use App\Exceptions\ConflictException;
use App\Models\UploadFileItem;
use Exception;
use Illuminate\Support\Facades\DB;
use League\Csv\Reader;

class CreateNewUploadFileService
{
    public function __construct(
        private readonly UploadFileContract $repository,
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
            $uploadFile = $this->repository->create([
                'name' => $fileName
            ]);
            $this->createNewUpdateFileItem($data['arquivo'], $uploadFile->id);
            DB::commit();
        }catch(Exception $e) {
            dd($e->getMessage());
        }

        return [];
    }

    public function createNewUpdateFileItem(object $data, int $upload_file_id): array
    {
        $csv = Reader::createFromPath($data->getRealPath(), 'r');
        $csv->setHeaderOffset(0); // Usar a primeira linha como cabeçalho
        
        foreach ($csv as $row) {
            $row['upload_file_id'] = $upload_file_id;
            $this->itemRepository->create($row);    
        }

        return [];
    }
}
