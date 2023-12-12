<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SalesReportDetail extends Model
{
    protected $table = 'sales_report_detail';
    protected $fillable = ['id_sales_report', 'tanggal', 'persentase_billed', 'dispute', 'persentase_dispute'];
}
