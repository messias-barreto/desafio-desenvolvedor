<?php 
namespace App\Repository;

use App\Contract\UploadFileItemContract;
use App\Models\UploadFile;
use App\Models\UploadFileItem;
use Illuminate\Database\Eloquent\Model;

class UploadFileItemRepository implements UploadFileItemContract
{
    private Model $repository;
    public function __construct()
    {
        $this->repository = app(UploadFileItem::class);
    }

    public function create(array $data): object
    {
        return $this->repository->create($data);
    }
}