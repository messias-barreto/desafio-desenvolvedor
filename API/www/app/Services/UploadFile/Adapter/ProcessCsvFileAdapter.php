<?php

namespace App\Services\UploadFile\Adapter {

    use App\Contract\ProcessFileContract;
    use App\Contract\UploadFileItemContract;
    use Exception;
    use Illuminate\Support\Facades\Cache;
    use Illuminate\Support\Facades\Log;
    use League\Csv\Reader;

    class ProcessCsvFileAdapter implements ProcessFileContract
    {
        public function __construct(
            private UploadFileItemContract $repository
        ) {}
        
        public function processItem(string $fileName, int $upload_file_id): void
        {
            $fileLock = Cache::lock('processing-file-' . $upload_file_id, 600);
            $csv = Reader::createFromPath($fileName, 'r');
            $csv->setHeaderOffset(0);
            $limitChunk = 1000;
            $uploadFileItemChunck = [];
            $count = 0;

            try {
                foreach ($csv as $row) {
                    $row['upload_file_id'] = $upload_file_id;
                    $uploadFileItemChunck[] = $row;
                    
                    if(count($uploadFileItemChunck) >= $limitChunk) {
                        $count++;
                        $this->repository->insertBatch($uploadFileItemChunck);
                        $uploadFileItemChunck = [];
                        Log::info('Chunk ' . $count . ' FOI INCLUIDO');
                    } 
                }

                if(!empty($uploadFileItemChunck)) {
                    $this->repository->insertBatch($uploadFileItemChunck);
                }

                Log::info("Os Itens Foram Inclídos no Sistema");
                $fileLock->release();
            }catch(Exception $e) {
                Log::info("Erro Gerado No Processamento do Arquivo!", [
                    'error_message' => $e->getMessage()
                ]);
                
                $fileLock->release();
            }

        }
    }
}
