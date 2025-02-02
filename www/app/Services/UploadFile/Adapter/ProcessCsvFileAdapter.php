<?php

namespace App\Services\UploadFile\Adapter {

    use App\Contract\ProcessFileContract;
    use App\Contract\UploadFileContract;
    use App\Contract\UploadFileItemContract;
    use Exception;
    use Illuminate\Support\Facades\Cache;
    use Illuminate\Support\Facades\DB;
    use Illuminate\Support\Facades\Log;
    use League\Csv\Reader;

    class ProcessCsvFileAdapter implements ProcessFileContract
    {
        public function __construct(
            private UploadFileItemContract $repository,
            private readonly UploadFileContract $uploadFileRepository
        ) {}

        public function processItem(string $filePath, int $upload_file_id): void
        {
            $fileLock = Cache::lock('processing-file-' . $upload_file_id, 600);
            $csv = Reader::createFromPath($filePath, 'r');
            $csv->setDelimiter(';');
            $csv->setHeaderOffset(0);
            $limitChunk = 1000;
            $uploadFileItemChunck = [];

            try {
                foreach ($csv as $row) {
                    array_walk_recursive($row, function (&$value) {
                        if (is_string($value)) {
                            $value = mb_convert_encoding($value, 'UTF-8', 'auto');
                        }
                    });

                    $row['upload_file_id'] = $upload_file_id;
                    $uploadFileItemChunck[] = $row;

                    if (count($uploadFileItemChunck) >= $limitChunk) {
                        DB::beginTransaction();
                        $this->repository->insertBatch($uploadFileItemChunck);
                        $uploadFileItemChunck = [];
                        DB::commit();
                    }
                }

                if (!empty($uploadFileItemChunck)) {
                    DB::beginTransaction();
                    $this->repository->insertBatch($uploadFileItemChunck);
                    DB::commit();
                }

                $this->saveStatusUploadFile(2, $upload_file_id);
                Log::info("Os Itens Foram Inclídos no Sistema");

                $fileLock->release();
                $this->removeFileStorage($filePath);
            } catch (Exception $e) {
                DB::rollBack();

                $this->saveStatusUploadFile(3, $upload_file_id);
                Log::error("Erro Gerado No Processamento do Arquivo!", [
                    'error_message' => $e->getMessage()
                ]);

                $fileLock->release();
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
}
