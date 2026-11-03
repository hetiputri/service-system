<?php

namespace App\Http\Requests\Jabatan;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class SearchJabatanWithFiltersRequest extends FormRequest
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
            'nama_jabatan' => 'nullable|string|max:200',
            'status_jabatan' => 'nullable|in:0,1',
            'kode_skpd' => 'nullable|string|max:10',
        ];
    }
}
