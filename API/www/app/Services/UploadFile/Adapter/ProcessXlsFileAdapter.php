<?php

namespace App\Services\UploadFile\Adapter;

use App\Contract\ProcessFileContract;
use App\Contract\UploadFileItemContract;
use Exception;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use PhpOffice\PhpSpreadsheet\IOFactory;

class ProcessXlsFileAdapter implements ProcessFileContract
{
    public function __construct(
        private readonly UploadFileItemContract $repository
    ) {}

    public function processItem(string $filePath, int $upload_file_id): void
    {
        $fileLock = Cache::lock('processing-file-' . $upload_file_id, 600);
        $reader = IOFactory::createReader('Xls');
        $reader->setReadDataOnly(true);
        $spreadsheet = $reader->load($filePath);
        $sheet = $spreadsheet->getActiveSheet();
        $data = $sheet->toArray();
        $header = $data[0];
        array_push($header, 'upload_file_id');
        array_shift($data);
        $limitChunk = 500;
        $count = 0;
        foreach (array_chunk($data, $limitChunk) as $chunk) {
            DB::beginTransaction();
            try {
                $chunk = array_map(function ($row) use ($upload_file_id, $header) {
                    $row['upload_file_id'] = $upload_file_id;
                    return array_combine($header, $row);
                }, $chunk);

                $count++;
                $this->repository->insertBatch($chunk);
                Log::info('BATCH ' . $count . ' ADICIONADA!');
                DB::commit();
            } catch (Exception $e) {
                DB::rollBack();

                Log::error('Erro ao Processar o Arquivo', [
                    'message_error' => $e->getMessage()
                ]);
                $fileLock->release();
            }
        }

        Log::info('Arquivo foi Processado com Sucesso!');
        $fileLock->release();
    }
}
