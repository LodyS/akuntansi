<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ValidasiTanggal extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'tanggal_mulai'=>'required|date',
            //'tanggal_selesai'=>'required|date|after:tanggal_mulai',
            'tanggal_selesai'=>'required|date|after_or_equal:tanggal_mulai',
        ];
    }
}
