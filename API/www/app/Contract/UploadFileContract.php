<?php 
namespace App\Contract;

interface UploadFileContract
{
    public function create(array $data): object;
    public function findByName(string $name): ?object;
    public function findByLikedName(string $name): ?object;
    public function findById(int $id): ?object;
    public function update(array $data): bool;
}