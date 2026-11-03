<?php

namespace App\Http\Requests\DaftarPegawai;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class GetDaftarPegawaiRequest extends FormRequest
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
            'kode_skpd' => 'required|string|max:10',
            'nama' => 'nullable|string|max:256',
            'nip' => 'nullable|string|max:18',
            'id_pangkat' => 'nullable|integer|between:1,17',
            'id_eselon' => 'nullable|integer|between:1,10',
            'id_tipe_jabatan' => 'nullable|integer|between:1,3',
        ];
    }
}
