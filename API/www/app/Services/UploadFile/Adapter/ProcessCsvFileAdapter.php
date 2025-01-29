<?php

namespace App\Services\UploadFile\Adapter {

    use App\Contract\ProcessFileContract;
    use App\Contract\UploadFileItemContract;
    use League\Csv\Reader;

    class ProcessCsvFileAdapter implements ProcessFileContract
    {
        public function __construct(
            private UploadFileItemContract $repository
        ) {}
        
        public function processItem(object $data, int $upload_file_id): array
        {
            $csv = Reader::createFromPath($data->getRealPath(), 'r');
            $csv->setHeaderOffset(0);
            $qtdUploadFileItem = 0;

            foreach ($csv as $row) {
                $row['upload_file_id'] = $upload_file_id;
                $uploadFileItem = $this->repository->create($row);
                if($uploadFileItem) {
                    $qtdUploadFileItem++;
                }
            }

            return array(
                'message' => "{$qtdUploadFileItem} Items Foram Inclídos no Sistema",
                'status' => 201
            );
        }
    }
}
