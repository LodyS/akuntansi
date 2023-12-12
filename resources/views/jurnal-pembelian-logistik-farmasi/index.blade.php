@extends('layouts.app')

@section('content')

<div class="page-header">
    <h1 class="page-title">Jurnal Pembelian Logistik Farmasi</h1>
</div>
@include('layouts.inc.breadcrumb')
<div class="page-content">
    <div class="panel">
        <div class="panel-body">

            <h3 align="center">Informasi</h3>
            <div class="form-group row">
		  <label class="col-md-3">Pajak Pembelian</label>
			  <div class="col-md-7">
              <input value="{{isset($pembelian_jenis->pajak) ? $pembelian_jenis->pajak : 'Data Kosong' }}" class="form-control btn-round" readonly>
			</div>
		</div>

        <div class="form-group row">
		  <label class="col-md-3">Biaya Materai</label>
			  <div class="col-md-7">
              <input value="{{ isset($pembelian_jenis->materai) ? $pembelian_jenis->materai : 'Data Kosong' }}" class="form-control btn-round" readonly>
			</div>
		</div>

        <div class="form-group row">
		  <label class="col-md-3">Diskon Pembelian</label>
			  <div class="col-md-7">
              <input value="{{ isset ($pembelian_jenis->diskon) ? $pembelian_jenis->diskon : 'Data Kosong' }}" class="form-control btn-round" readonly>
			</div>
		</div>

        <hr/>
        <h3 align="center">Form Pencarian</h3>
        <form action="{{ url('jurnal-pembelian-logistik-farmasi/rekapitulasi')}}" method="post">{{ @csrf_field() }}

	<div class="form-group row">
		<label class="col-md-3">Tanggal</label>
			<div class="col-md-7">
			<input type="date" name="tanggal" class="form-control btn-round" value="{{date('Y-m-d')}}">
		</div>
	</div>

    <div class="form-group row">
		<label class="col-md-3">Jenis Pembelian</label>
			<div class="col-md-7">
			<select class="form-control btn-round" name="jenis_pembelian" required>
			<option value="">Pilih Jenis Pembelian</option>
			@foreach($jenisPembelian as $jenis)
			<option value="{{$jenis->id}}">{{$jenis->nama}}</option>
			@endforeach
			</select>
		</div>
	</div>

	<div class="form-group row">
		<label class="col-md-3">Jenis Pembayaran</label>
			<div class="col-md-7">
			<select class="form-control btn-round" name="status" required>
				<option value="">Pilih Jenis Pembayaran</option>
				<option value="1">Kredit</option>
				<option value="2">Tunai</option>
			</select>
		</div>
	</div>

        <button type="submit" align="right" class="btn btn-primary btn-round"><i class="icon glyphicon glyphicon-search" aria-hidden="true"></i>Cari</button><br/><br/>

            </form>



            <table class="table table-hover">
                <tr>
                    <th>No</th>
                    <th>Bukti Pembelian</th>
                    <th>Supplier</th>
                    <th>Total Pembelian</th>
                    <th>Diskon</th>
                    <th>Materai</th>
                    <th>PPN</th>
                    <th>Total Tagihan</th>
                    <th>Cara Bayar</th>
                    <th>Perkiraan</th>
                    <th>Aksi</th>
                </tr>

                    @foreach ($rekapitulasi as $key => $rekap)
                    <tr>
                        <td>{{ $key + $rekapitulasi->firstItem() }}</td>
                        <td>{{ $rekap->bukti_pembelian }}</td>
                        <td>{{ $rekap->supplier }}</td>
                        <td>Rp. {{ number_format($rekap->total_pembelian) }}</td>
                        <td>Rp. {{ number_format($rekap->diskon) }}</td>
                        <td>Rp. {{ number_format($rekap->materai) }}</td>
                        <td>Rp. {{ number_format($rekap->ppn) }}</td>
                        <td>Rp. {{ number_format($rekap->total_yang_harus_dibayar) }}</td>
                        <td>{{ $rekap->cara_pembayaran }}</td>
                        <td>{{ $rekap->perkiraan }}</td>
                        <td><a href="jurnal/{{$rekap->id_pembelian}}/{{ $status }}/{{$tanggal}}" class="btn btn-success">Buat Jurnal</a></td>
                    </tr>
                    @endforeach
                </table>
            {{ $rekapitulasi->appends(request()->toArray())->links() }}
        </div>
    </div>
</div>
@endsection
