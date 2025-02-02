<?php

namespace App\Services\UploadFile;

use App\Contract\UploadFileContract;
use App\Exceptions\ConflictException;
use App\Exceptions\NotFoundException;
use App\Exceptions\ServerErrorException;

class DeleteUploadItemService
{
    public function __construct(
        private readonly UploadFileContract $repository
    ) {}

    public function execute(string $fileName): array 
    {
        $uploadFileAlreadyExists = $this->repository->findByName($fileName);
        if (!$uploadFileAlreadyExists) {
            throw new NotFoundException('O Arquivo Não foi Encontrado em Nossa Base de Dados');
        }

        if($uploadFileAlreadyExists->upload_file_status_id === 1) {
            throw new ConflictException('Não foi possível realizar a exclusão do Aquivo. O Arquivo ainda Está sendo Processado.');
        }

        $deletedUploadFile = $this->repository->destroy($uploadFileAlreadyExists->id);
        if(empty($deletedUploadFile)) {
            throw new ServerErrorException('Não foi Possíve Deletar o Arquivo Solicitado, Por Favor tente novamente.');
        }

        return [
            'message' => 'Arquivo foi Excluido com Sucesso!'
        ];
    }
}
