@extends('layouts.app')

@section('content')

<div class="page-header">
    <h1 class="page-title">Pencarian </h1>
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
       
       <form action="{{ url('/update-discharge')}}" method="post">{{ @csrf_field() }} 

        <div class="panel-body">  
            <table class="table table-hover">
                <tr>
                <th>No Kunjungan</th>
                <th>Tanggal</th>  
                <th>Pasien</th> 
                <th>Tagihan</th>     
                <th>Discharge</th> 
            </tr>

                <tr>
                    @foreach ($data as $key => $cari)
                    <input type="hidden" name="id_pendapatan_jasa[]" value="{{ $cari->id}}">
                    <input type="hidden" name="no_kunjungan[]" value="{{ $cari->no_kunjungan}}">
                    <td>{{ $cari->no_kunjungan }}</td>
                    <td>{{ date('d-m-Y', strtotime($cari->tanggal)) }}</td>
                    <td>{{ $cari->nama }}</td>
                    <td>Rp. {{ number_format($cari->total_tagihan) }}</td>
                    <td><input type="checkbox" name="discharge" value="Y"></td>
                </tr> 
                   
                @endforeach
                </thead>
            </table>
            
        <button type="submit" align="right" class="btn btn-primary">Discharge</button>
            </div>
        </div>
    </form>
</div>
@endsection