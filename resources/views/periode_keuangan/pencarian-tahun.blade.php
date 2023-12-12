@extends('layouts.app')

@section('content')

<div class="page-header">
    <h1 class="page-title">Pencarian Tahun Periode Keuangan</h1>
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
                <th>Tanggal Awal</th>
                <th>Tanggal Akhir</th>  
                <th>Bulan</th> 
                <th>Tahun</th>    
                <th>Status Aktif</th>  
            </tr>

                <tr>
                    @foreach ($cari as $key => $data)
                    <td>{{ $key + $cari->firstItem() }}</td>
                    <td>{{ date('d-m-Y', strtotime($data->tanggal_awal)) }}</td>
                    <td>{{ date('d-m-Y', strtotime($data->tanggal_akhir)) }}</td>
                    <td>{{ $data->bulan }}</td>
                    <td>{{ $data->tahun }}</td>
                    <td>{{ $data->status_aktif == 'N' ? 'Tidak Aktif' : 'Aktif' }}</td>
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