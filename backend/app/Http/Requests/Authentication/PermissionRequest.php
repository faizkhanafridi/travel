<?php

namespace App\Http\Requests\Authentication;

use Illuminate\Foundation\Http\FormRequest;

class PermissionRequest extends FormRequest
{
    public function authorize(): bool { return true; }

    public function rules(): array
    {
        $isUpdate = $this->isMethod('PUT') || $this->isMethod('PATCH');

        return [
            'name'         => [$isUpdate ? 'sometimes' : 'required', 'string', 'max:150'],
            'module'       => ['nullable', 'string', 'max:100'],
            'display_name' => ['nullable', 'string', 'max:150'],
            'description'  => ['nullable', 'string'],
        ];
    }
}