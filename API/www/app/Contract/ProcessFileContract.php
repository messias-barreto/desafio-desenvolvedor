<?php 
namespace App\Contract;

interface ProcessFileContract
{
    public function processItem(string $data, int $upload_file_id): void;
}