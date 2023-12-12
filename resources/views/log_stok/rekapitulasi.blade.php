@extends('layouts.app')

@section('content')
<div class="page-header">
    <h1 class="page-title">Rekapitulasi Persediaan</h1>
</div>

<div class="page-content">
    <div class="panel">
        <div class="panel-body">
		
<div class="form-group row">
    </div>
				
        <table class="table table-hover">
        
            <tr>
                <th>No</th>
                <th>Kategori Persediaan</th>
				<th>Sub Kategori Persediaan</th>
                <th>Persediaan</th>
				<th>Departemen</th>
                <th>Barcode</th>
                <th>Satuan</th>
                <th>HNA</th>
                <th>HPP</th>
                <th>Stok</th>
            </tr>
            
            @php ($i=1)
            @foreach ($data as $rekap)
            <tr>
                <td> {{ $i}}</td>
                <td> {{ $rekap->kategori_barang }}</td>
                <td> {{ $rekap->sub_kategori_barang }}</td>
                <td> {{ $rekap->persediaan}}
                <td> {{ $rekap->departemen}}</td>
				<td> {{ $rekap->barcode }}</td>
                <td> {{ $rekap->satuan }}</td>
                <td> {{ $rekap->hpp }}</td>
                <td>Rp. {{ number_format($rekap->hna,2) }}</td>
                <td> {{ $rekap->jumlah_stok }}</td>
                           
            </tr>
            @php($i++)
            @endforeach
                          
                    </table>
			</form>
        </div>
    </div>
</div>
@endsection