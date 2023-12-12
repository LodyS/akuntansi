<tr>
    <td colspan="5"></td>
    <td><b>Total</b></td>

    <td><b>Rp. {{ number_format($subDebet = $rekapitulasi->where('kode_jurnal', $jurnal_kode)->sum('debet'),2, ",", ".") }}</td>
    <td><b>Rp. {{ number_format($subKredit = $rekapitulasi->where('kode_jurnal', $jurnal_kode)->sum('kredit'),2, ",", ".") }}</b></td>

    <td><b>Balance : Rp. {{ number_format($subDebet - $subKredit,2, ",", ".") }}</b></td>
    <td></td>
</tr>
<tr>
    <td colspan="5"></td>
</tr>
