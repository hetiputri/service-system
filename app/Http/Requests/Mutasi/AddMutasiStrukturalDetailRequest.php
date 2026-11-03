<?php

namespace App\Http\Requests\Mutasi;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class AddMutasiStrukturalDetailRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    // public function authorize(): bool
    // {
    //     return false;
    // }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'id_pegawai' => 'required|string|max:5',
            'dari_id_jabatan' => 'required|string|max:5',
            'ke_id_jabatan' => 'required|string|max:5',
        ];
    }
}
