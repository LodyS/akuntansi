<?php

namespace App\Exports;
use DB;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromCollection;
//use Maatwebsite\Excel\Concerns\FromQuery;
use Illuminate\Http\Request;

class NeracaSaldo implements FromCollection
{

    use Exportable;

    protected $request;

    //dd($request);

    public function __construct($request)
    {
        $this->request = $request;
    }

    public function collection()
    {
        $aktiva = DB::select("SELECT B.id, B.nama, A.saldo, sum(C.total) as total, '' as passiva, '0' as saldo_passiva, '0' total_passiva FROM

        (SELECT sn.id AS id, sn.nama , ((SUM(dj.debet) - SUM(dj.kredit)) * sn.jenis) AS saldo FROM jurnal j JOIN detail_jurnal dj
        ON dj.id_jurnal = j.id
        JOIN perkiraan pk ON pk.id=dj.id_perkiraan
        JOIN set_neraca_detail sd ON sd.id_perkiraan=dj.id_perkiraan
        LEFT JOIN set_neraca sn ON sn.id=sd.id_set_neraca
        WHERE jenis_neraca='Aktiva' AND MONTH(tanggal_posting) = '$this->request->bulan' AND YEAR(tanggal_posting) = '$this->request->tahun' and j.status=2
        GROUP BY sn.id) A
        RIGHT JOIN

        (SELECT sn.id AS id, kode, sn.nama  AS nama FROM set_neraca sn WHERE jenis_neraca='Aktiva') B ON B.id=A.id
        LEFT JOIN (SELECT   r.id_rumus, r.id_set_neraca AS id, (((SUM(dj.debet))-(SUM(dj.kredit)))* n.jenis)  AS TOTAL FROM jurnal j
        JOIN detail_jurnal dj ON dj.id_jurnal=j.id
        JOIN set_neraca_detail d ON d.id_perkiraan=dj.id_perkiraan
        JOIN set_neraca_rumus r ON d.id_set_neraca=r.id_rumus
        JOIN set_neraca n ON r.id_rumus=n.id
        WHERE jenis_neraca='Aktiva' AND MONTH(tanggal_posting) = '$this->request->bulan' AND YEAR(tanggal_posting) = '$this->request->tahun' and j.status=2
        GROUP BY  r.id_rumus ,  r.id_set_neraca) C ON C.id=B.id
        GROUP BY nama
        ORDER BY kode");

        $passiva = DB::select("SELECT B.id, B.nama as passiva, A.saldo as saldo_passiva, C.TOTAL as total_passiva, '' as nama, '0' as saldo, '0' as total
        FROM
        (SELECT sn.id AS id, sn.nama , ( SUM(dj.debet) - SUM(dj.kredit) ) * jenis AS saldo FROM jurnal j JOIN detail_jurnal dj
        ON dj.id_jurnal = j.id
        JOIN perkiraan pk ON pk.id=dj.id_perkiraan
        JOIN set_neraca_detail sd ON sd.id_perkiraan=pk.id
        LEFT JOIN set_neraca sn ON sn.id=sd.id_set_neraca
        WHERE jenis_neraca='Passiva'
        AND MONTH(j.tanggal_posting)='$this->request->bulan' AND YEAR(j.tanggal_posting)='$this->request->tahun' and j.status=2
        GROUP BY sn.id) A
        RIGHT JOIN
        (SELECT sn.id AS id, kode, sn.nama  AS nama FROM set_neraca sn WHERE jenis_neraca='Passiva') B
        ON B.id=A.id

        LEFT JOIN (
        SELECT n.id AS id, (SUM(debet) - SUM(kredit))* jenis  AS TOTAL FROM
        jurnal j
        JOIN detail_jurnal dj ON dj.id_jurnal=j.id
        JOIN set_neraca_detail d ON d.id_perkiraan=dj.id_perkiraan
        JOIN set_neraca_rumus r ON d.id_set_neraca=r.id_rumus
        JOIN set_neraca n ON r.id_set_neraca=n.id
        WHERE jenis_neraca='Passiva'
        AND MONTH(j.tanggal_posting)='$this->request->bulan' AND YEAR(j.tanggal_posting)='$this->request->tahun' and j.status=2
        GROUP BY n.id) C
        ON C.id=B.id");

        $arrays = [$aktiva, $passiva];

        return $array;
    }
}
