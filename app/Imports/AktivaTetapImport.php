<?php

namespace App\Imports;
use App\Models\Unit;
use App\Models\KelompokAktiva;
use App\AktivaTetap;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class AktivaTetapImport implements ToModel, WithHeadingRow, WithValidation
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        $kelompok_aktiva = KelompokAktiva::select('id')->where('kode', $row['kelompok_aktiva_tetap'])->first();
        $unit = Unit::select('id')->where('code_cost_centre', $row['cost_centre'])->first();

        return new AktivaTetap([
            'kode'=>$row['kode'] ?? NULL,
            'nama'=>$row['nama'] ?? NULL,
            'id_kelompok_aktiva'=>$kelompok_aktiva->id ?? NULL,
            'id_unit'=>$unit->id ?? NULL,
            'id_metode_penyusutan'=>'1',
            'lokasi'=>$row['lokasi'] ?? NULL,
            'no_seri'=>$row['no_seri'] ?? NULL,
            'tanggal_pembelian'=>\PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($row['tanggal_pembelian']),
            'nilai_residu'=>$row['nilai_residu'] ?? NULL,
            'umur_ekonomis'=>$row['umur_ekonomis'] ?? NULL,
            'harga_perolehan'=>$row['harga_perolehan'] ?? NULL,
            'tarif'=>$row['tarif_penyusutan'] ?? NULL
        ]);
    }

    public function rules(): array
    {
        return [
            'kode'=>'',
            'nama'=>'required',
            'kelompok_aktiva_tetap'=>'required',
            'cost_centre'=>'required',
            'lokasi'=>'required',
            'no_seri'=>'required',
            'tanggal_pembelian'=>'',
            'nilai_residu'=>'required',
            'umur_ekonomis'=>'required',
            'harga_perolehan'=>'required',
            'tarif_penyusutan'=>'required'
        ];
    }
}
