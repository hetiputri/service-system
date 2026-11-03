<?php

namespace App\Http\Requests\Mutasi;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class UpdateMutasiStrukturalRequest extends FormRequest
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
            'nama_konsep' => 'required|string|max:200',
            'tanggal_konsep' => 'required|date',
            'keterangan' => 'nullable|string',
        ];
    }
}
