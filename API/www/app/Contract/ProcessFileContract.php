<?php 
namespace App\Contract;

interface ProcessFileContract
{
    public function processItem(object $data, int $upload_file_id): array;
}