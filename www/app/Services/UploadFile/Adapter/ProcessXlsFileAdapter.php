<?php

namespace App\Services\UploadFile\Adapter;

use App\Contract\ProcessFileContract;
use App\Contract\UploadFileContract;
use App\Contract\UploadFileItemContract;
use Exception;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use PhpOffice\PhpSpreadsheet\IOFactory;

class ProcessXlsFileAdapter implements ProcessFileContract
{
    public function __construct(
        private readonly UploadFileItemContract $repository,
        private readonly UploadFileContract $uploadFileRepository
    ) {}

    public function processItem(string $filePath, int $upload_file_id): void
    {
        $fileLock = Cache::lock('processing-file-' . $upload_file_id, 600);
        $reader = IOFactory::createReader('Xlsx');
        $reader->setReadDataOnly(false);
        $spreadsheet = $reader->load($filePath);
        $sheet = $spreadsheet->getActiveSheet();
        $data = $sheet->toArray();
        $header = $data[1];

        array_push($header, 'upload_file_id');
        $header = array_filter($header, function ($value) {
            return !is_null($value);
        });

        $header = array_values($header);

        array_shift($data);
        array_shift($data);
        
        $limitChunk = 500;
        $count = 0;
        $failedProcessFile = false;
        foreach (array_chunk($data, $limitChunk) as $chunk) {
            DB::beginTransaction();
            try {
                $chunk = array_map(function ($row) use ($upload_file_id, $header) {
                    $repairChunkArray = array_slice($row, 0, 52);
                    $repairChunkArray['upload_file_id'] = $upload_file_id;
                    return array_combine($header, $repairChunkArray);
                }, $chunk);

                $count++;
                $this->repository->insertBatch($chunk);
                DB::commit();

                Log::info('BATCH ' . $count . ' ADICIONADA!');
            } catch (Exception $e) {
                DB::rollBack();
                $failedProcessFile = true;
                
                $this->saveStatusUploadFile(3, $upload_file_id);
                Log::error('Erro ao Processar o Arquivo', [
                    'message_error' => $e->getMessage()
                ]);
            }
        }

        $fileLock->release();
        if (!$failedProcessFile) {
            Log::info('Arquivo foi Processado com Sucesso!');
            $this->saveStatusUploadFile(2, $upload_file_id);
            $this->removeFileStorage($filePath);
        }
    }

    public function saveStatusUploadFile($status, $uploadFileId): void
    {
        $uploadFileAlreadyExists = $this->uploadFileRepository->findById($uploadFileId);
        $uploadFileAlreadyExists->upload_file_status_id = $status;
        $uploadFileAlreadyExists->save();
    }

    public function removeFileStorage($filePath): void
    {
        if (file_exists($filePath)) {
            unlink($filePath);
        }
    }
}
