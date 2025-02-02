<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UploadFileRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules()
    {
        return [
            'arquivo' => 'required|file|mimes:csv,txt,xlsx|max:81920',
        ];
    }

    public function messages()
    {
        return [
            'arquivo.required' => 'O envio do arquivo é obrigatório.',
            'arquivo.file' => 'O arquivo enviado é inválido.',
            'arquivo.mimes' => 'O arquivo deve estar no formato CSV ou XLSX.',
            'arquivo.max' => 'O arquivo não pode ter mais de 80MB.',
        ];
    }
}
