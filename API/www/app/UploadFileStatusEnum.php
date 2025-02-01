<?php

namespace App;

enum UploadFileStatusEnum: string
{
    case PROCESSING = 'PROCESSANDO';
    case PROCESSED = 'PROCESSADO';
    case FAILED = 'FALHOU';

    public function message(): string
    {
        return match ($this) {
            $this::PROCESSING => 'O arquivo está sendo processado no momento.',
            $this::PROCESSED => 'O arquivo foi processado com sucesso.',
            $this::FAILED => 'Ocorreu uma falha no processamento do arquivo.',
        };
    }
}
