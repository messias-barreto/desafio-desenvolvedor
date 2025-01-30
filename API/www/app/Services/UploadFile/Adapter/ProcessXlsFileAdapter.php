<?php

namespace App\Services\UploadFile\Adapter;

use App\Contract\ProcessFileContract;
use App\Contract\UploadFileItemContract;
use PhpOffice\PhpSpreadsheet\IOFactory;

class ProcessXlsFileAdapter implements ProcessFileContract
{
    public function __construct(
        private readonly UploadFileItemContract $repository
    ) {}

    public function processItem(object $data, int $upload_file_id): array
    {
        $path = $data->getRealPath();
        $spreadsheet = IOFactory::load($path);
        
        $sheet = $spreadsheet->getActiveSheet();
        $data = [];
        $header = null;
        foreach ($sheet->getRowIterator() as $index => $row) {
            if ($index == 1) {
                $header = $this->getRowData($row);
                $header[] = 'upload_file_id';
                continue;
            }
           
            $formatedUploadRowItem = $this->getRowData($row);
            $formatedUploadRowItem[] = $upload_file_id;
            $data[] = array_combine($header, $formatedUploadRowItem);
        }
        
        $this->repository->insertBatch($data);
        return [];
    }

    public function getRowData(object $row)
    {
        $rowData = [];
        $cellIterator = $row->getCellIterator();
        $cellIterator->setIterateOnlyExistingCells(false);
        foreach ($cellIterator as $cell) {
            $rowData[] = $cell->getValue();
        }

        return $rowData;
    }
}
