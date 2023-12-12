<?php

namespace App\Http\Requests;
use App\JenisTransaksi;
use Illuminate\Foundation\Http\FormRequest;

class SettingCashFlowRequest extends FormRequest
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
            'id_transaksi_jurnal'=>'required|unique:jenis_transaksi,id_transaksi_jurnal',
            'kode'=>'required|unique:jenis_transaksi,kode'
        ];
    }
}
