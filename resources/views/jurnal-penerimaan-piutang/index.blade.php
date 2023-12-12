@extends('layouts.app')

@section('content')   

<div class="page-header">
    <h1 class="page-title">Jurnal Penerimaan Piutang</h1>
</div>
  @include('layouts.inc.breadcrumb')
<div class="page-content">
    <div class="panel">
        <div class="panel-body">
        
	<form action="{{ url('jurnal-penerimaan-piutang/rekapitulasi-jurnal-penerimaan-piutang')}}" method="post">{{ @csrf_field() }} 

    <div class="form-group row">
		<label class="col-md-3">Tipe Pasien</label>
			<div class="col-md-7">
                <select class="form-control" name="tipe_pasien" required>
                <option value="">Pilih</option>
                <option value="1">Perusahaan Langganan</option>
                <option value="2">Antar Unit</option>
            </select>
		</div>
	</div>
   
    <div class="form-group row">
		<label class="col-md-3">Jenis Pembayaran</label>
			<div class="col-md-7">
                <select class="form-control" name="id_bank" required>
                <option value="">Pilih Bank</option>
                @foreach ($KasBank as $bank)
                <option value="{{ $bank->id}}">{{ $bank->nama}}</option>
                @endforeach
            </select>
		</div>
	</div>

    <div id="tampil" style="display:none" class="none">
        <div class="form-group row">
		    <label class="col-md-3">Asuransi</label>
			    <div class="col-md-7">
                <select class="form-control" name="id_asuransi">
                    <option value="">Pilih Asuransi</option>
                    @foreach ($ProdukAsuransi as $asuransi)
                    <option value="{{ $asuransi->id}}">{{ $asuransi->nama}}</option>
                    @endforeach
                </select>
			</div>
		</div>
    </div>

    <div class="form-group row">
		<label class="col-md-3">Tanggal</label>
			<div class="col-md-7">
			<input type="date" name="tanggal" class="form-control" value="{{ date('Y-m-d') }}" required>
		</div>
	</div>

        <button type="submit" align="right" class="btn btn-primary">Cari</button>
                
            </form>
        </div>
    </div>
</div>
<div class=" modal fade" id="formModal" aria-hidden="true" aria-labelledby="formModalLabel" role="dialog" tabindex="-1">
</div>
@endsection

@push('js')
<script type="text/javascript">

function showForm (){
	if (document.getElementById('perusahaan_langganan').checked){
		document.getElementById('tampil').style.display = 'block';
	} 
	else {
		document.getElementById('tampil').style.display = 'none'; 
	}
}

$(document).ready(function(){

});

</script>
@endpush