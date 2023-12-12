@extends('layouts.app')

@section('content')

<div class="page-header">
    <h1 class="page-title">Pencarian Pengeluaran Kas </h1>
        <div class="page-header-actions">
    </div>
</div>
   
   <div class="page-content">
        <div class="panel">
            <header class="panel-heading">
                <div class="form-group col-md-12">
                    <div class="form-group">
                </div>
            </div>
       </header>
       
        <div class="panel-body">  
            <table class="table table-hover">
                <tr>
                <th>No</th>
                <th>Kode</th>
                <th>Tanggal</th>  
                <th>Pemasukan</th> 
                <th>Cara Pembayaran</th>    
                <th>Nominal</th>  
                <th>Keterangan</th>
            </tr>
                @php ($i=1)
                <tr>
               
                @foreach ($cari as $key => $data)
                    <td>{{ $key + $cari->firstItem() }}</td>
                    <td>{{ $data->kode }}</td>
                    <td>{{ date('d-m-Y', strtotime($data->tanggal)) }}</td>
                    <td>{{ $data->perkiraan }}</td>
                    <td>{{ $data->kas_bank }}</td>
                    <td>Rp. {{ number_format($data->nominal,2) }}</td>
                    <td>{{ $data->keterangan }}</td>
                </tr> 
                @endforeach
                </thead>
            </table>
            {{ $cari->appends(request()->toArray())->links() }}
            </div>
        </div>
    </form>
</div>
@endsection