<?php

namespace App\Services\UploadFile\Adapter;

use App\Contract\ProcessFileContract;
use App\Contract\UploadFileItemContract;
use App\Exceptions\BadRequestException;
use Exception;
use PhpOffice\PhpSpreadsheet\IOFactory;

class ProcessXlsFileAdapter implements ProcessFileContract
{
    public function __construct(
        private readonly UploadFileItemContract $repository
    ) {}

    public function processItem(string $data, int $upload_file_id): void
    {
        dd('asasa');
        $path = $data->getRealPath();
        $reader = IOFactory::createReader('Xls');
        $reader->setReadDataOnly(true); // Lê apenas os dados, não o formato
        $spreadsheet = $reader->load($path);
        
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
        
        try {
            $chunks = array_chunk($data, 10000); // Divide em lotes de 1000 registros
            foreach ($chunks as $chunk) {
                $fileBatch = $this->repository->insertBatch($chunk);
                if(!$fileBatch) {
                    throw new BadRequestException('Os Arquivo Não foi Processado no Banco de Dados');
                }
            }
        }catch(Exception $e) {
            dd($e->getMessage());
        }
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
