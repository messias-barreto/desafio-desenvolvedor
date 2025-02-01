<?php 
namespace App\Repository;

use App\Contract\UploadFileContract;
use App\Models\UploadFile;
use Illuminate\Database\Eloquent\Model;

class UploadFileRepository implements UploadFileContract
{
    private Model $repository;
    public function __construct()
    {
        $this->repository = app(UploadFile::class);
    }

    public function create(array $data): object
    {
        return $this->repository->create($data);
    }

    public function findById(int $id): ?object
    {
        return $this->repository->find($id);
    }

    public function findByName(string $name): ?object
    {
        return $this->repository->where('name', $name)->first();
    }

    public function findByLikedName(string $name): ?object
    {
        return $this->repository->where('name', 'like', '%' . $name . '%')->get();
    }

    public function update(array $data): bool
    {
        return $this->repository->update($data);
    }
}