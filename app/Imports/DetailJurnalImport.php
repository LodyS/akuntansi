<?php

namespace App\Imports;
use App\Models\Perkiraan;
use App\Models\Unit;
use App\DetailJurnal;
use App\jurnal;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;

class DetailJurnalImport implements ToModel, WithHeadingRow, WithValidation
{
    public function model(array $row)
    {
        $jurnal = jurnal::orderBy('id', 'desc')->first();
        $id_perkiraan = Perkiraan::select('id')->where('kode_rekening', $row['kode_rekening'])->first();
        $id_unit = Unit::select('id')->where('code_cost_centre', $row['cost_centre'])->first();

        return new DetailJurnal([
            'id_jurnal'=>$jurnal->id,
            'id_perkiraan'=>$id_perkiraan->id ?? NULL,
            'debet'=>$row['debet'],
            'kredit'=>$row['kredit'],
            'id_unit'=>$id_unit->id ?? NULL,
            'tanggal'=>\PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($row['tanggal']),
            //'tanggal'=>$row['tanggal'],
            'keterangan'=>$row['keterangan']
        ]);
    }

    public function rules(): array
    {
        return [
            'kode_rekening'=>'required',
            'cost_centre'=>'',
            'debet'=>'',
            'kredit'=>'',
            'tanggal'=>'',
            'keterangan'=>''
        ];
    }
}
