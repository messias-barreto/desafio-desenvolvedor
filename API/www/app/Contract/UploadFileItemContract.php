<?php 
namespace App\Contract;

interface UploadFileItemContract
{
    public function create(array $data): object;
    public function insertBatch(array $data);
    public function getByUploadFileid(int $uploadFileId): object;
}