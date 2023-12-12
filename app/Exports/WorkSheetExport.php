<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;

class WorkSheetExport implements FromCollection
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        //
    }
}
