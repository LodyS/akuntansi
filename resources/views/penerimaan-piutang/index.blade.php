@extends('layouts.app')

@section('content')
<div class="page-header">
    <h1 class="page-title">Penerimaan Piutang</h1>
    @include('layouts.inc.breadcrumb')

<div class="page-header-actions">

    </div>
</div>

   <div class="page-content">
     <!-- Panel Table Tools -->
<div class="panel">
    <header class="panel-heading">
        <div class="form-group col-md-12">
        <div class="form-group">
        </div>
    </div>
</header>

    <div class="panel-body">

  <form action="{{ url('penerimaan-piutang/laporan-penerimaan-piutang')}}" method="post">{{ @csrf_field() }}

    <div class="form-group row">
		<label class="col-md-3">Jenis Pasien</label>
			<div class="col-md-7">
			    <select name="jenis_pasien" class="form-control" required>
                <option value="">Pilih Jenis Pasien</option>
                <option value="RJ">Rawat Jalan</option>
                <option value="RI">Rawat Inap</option>
            </select>
		</div>
	</div>

    <input type="hidden" name="cek_kode_bkm" value="{{ isset($cek_kode_bkm) ? $cek_kode_bkm->id : '' }}">
    
    <div class="form-group row">
		<label class="col-md-3">Tanggal Awal</label>
			<div class="col-md-7">
            <input type="date" name="tanggal_awal"  class="form-control" value="{{ date('Y-m-d')}}" required>
		</div>
	</div>

<b align="center">S/D</b><br/>

    <div class="form-group row">
		<label class="col-md-3">Tanggal Akhir</label>
			<div class="col-md-7">
            <input type="date" name="tanggal_akhir"  class="form-control" value="{{ date('Y-m-d')}}" required>
		</div>
	</div>

    <div class="form-group row">
		<label class="col-md-3">Tipe Pasien</label>
			<div class="col-md-7">
			<input type="radio" name="tipe_pasien" id="AntarUnit"  value="2" onClick="javascript:showForm()" required><label>Antar Unit</label>
			<input type="radio" name="tipe_pasien" id="PerusahaanLangganan" value="1" onClick="javascript:showForm()"><label>Perusahaan Langganan</label>
		</div>
	</div>

    <div id="tampil" style="display:none" class="none">
        <div class="form-group row">
			<label class="col-md-3">No Pasien</label>
			<div class="col-md-7">
			<input type="text" id="id" name="id_pasien" class="form-control">
		</div>
	</div>

    <div class="form-group row">
		<label class="col-md-3">Pasien</label>
			<div class="col-md-7">
			<input type="text" id="nama_pasien" name="nama_pasien" class="form-control" readonly>
			</div>
		</div>
	</div>

    <div id="asuransi" style="display:none" class="none">
        <div class="form-group row">
			<label class="col-md-3">Asuransi</label>
				<div class="col-md-7">
					<select name="id_asuransi" class="form-control">
                    <option value="">Pilih Asuransi</option>
                    @foreach ($ProdukAsuransi as $asuransi)
                    <option value="{{ $asuransi->id }}">{{ $asuransi->nama }}</option>
                    @endforeach
                </select>
			</div>
		</div>
    </div>

        <button type="submit" id="submit" align="right" class="btn btn-primary">Cari</button>
    </form>

        </div>
    </div>
 </div>


@endsection

@push('js')
<script type="text/javascript">

function showForm (){
	if (document.getElementById('AntarUnit').checked){
		document.getElementById('tampil').style.display = 'block';
        document.getElementById('asuransi').style.display = 'none';
	}
   else if (document.getElementById('PerusahaanLangganan').checked){
		document.getElementById('asuransi').style.display = 'block';
        document.getElementById('tampil').style.display = 'none';
	}
}

$('#id').change(function(){
    var id = $(this).val();
    var url = '{{ route("cariPasien", ":id") }}';
    url = url.replace(':id', id);

    $.ajax({
        url: url,
        type: 'get',
        dataType: 'json',
        success: function(response){
            if(response != null){
            $('#nama_pasien').val(response.nama);
        }}
    });
});
</script>
@endpush
