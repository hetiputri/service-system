<?php

namespace App\Http\Requests\PejabatStruktural;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class GetPejabatStrukturalRequest extends FormRequest
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
            'id_status_jabatan' => 'required|integer|between:0,4',
            'id_jenis_jabatan' => 'nullable|integer|between:0,11',
            'kode_skpd' => 'required|string|max:10',
            'id_agama' => 'nullable|integer|between:1,7',
            'id_jenis_kelamin' => 'nullable|integer|in:1,2',
            'tambah_query' => 'nullable|string|max:256',
            'urutan' => 'nullable|integer|in:0,1',
        ];
    }
}
