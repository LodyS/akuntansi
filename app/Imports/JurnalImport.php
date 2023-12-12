<?php

namespace App\Imports;
use App\jurnal;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;

class JurnalImport implements ToModel, WithHeadingRow, WithValidation
{
    public function model(array $row)
    {
        return new jurnal([
            'kode_jurnal'=>$row['kode_jurnal'],
            'tanggal_posting'=>\PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($row['tanggal']),
            'keterangan'=>$row['keterangan'],
            'id_tipe_jurnal'=>'5'
        ]);
    }

    public function rules(): array
    {
        return [
            'kode_jurnal'=>'required',
            'tanggal'=>'',
            'keterangan'=>'required'
        ];
    }
}
