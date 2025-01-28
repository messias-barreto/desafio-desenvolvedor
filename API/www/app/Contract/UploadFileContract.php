<?php 
namespace App\Contract;

interface UploadFileContract
{
    public function create(array $data): object;
    public function findByName(string $name): object;
}