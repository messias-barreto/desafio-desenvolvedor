<?php 
namespace App\Repository;

use App\Contract\UploadFileItemContract;
use App\Models\UploadFile;
use App\Models\UploadFileItem;
use Exception;
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

    public function insertBatch(array $data)
    {
        return $this->repository->insert($data);
    }

    public function getByUploadFileid(int $uploadFileId): object
    {
        return $this->repository->where('upload_file_id', $uploadFileId)->paginate(50);
    }

    public function getByUploadFileAndFilter(int $uploadFileId, array $filter): ?object
    {
        $uploadFileItem = $this->repository->where('upload_file_id', $uploadFileId);
        foreach($filter as $param => $value) {
            $uploadFileItem->where($param, $value);
        }

        return $uploadFileItem->get();
    }
}