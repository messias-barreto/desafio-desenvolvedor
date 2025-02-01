<?php 
namespace App\Repository;

use App\Contract\UploadFileStatusContract;
use App\Models\UploadFileStatus;
use Illuminate\Database\Eloquent\Model;

class UploadFileStatusRepository implements UploadFileStatusContract
{
    private Model $repository;
    public function __construct()
    {
        $this->repository = app(UploadFileStatus::class);
    }

    public function getAll(): object
    {
        return $this->repository->all();
    }
}