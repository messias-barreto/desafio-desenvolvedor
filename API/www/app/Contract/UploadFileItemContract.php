<?php 
namespace App\Contract;

interface UploadFileItemContract
{
    public function create(array $data): object;
}