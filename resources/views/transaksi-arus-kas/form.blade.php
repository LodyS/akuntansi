@extends('layouts.app')

@section('content')

<style>
.form-control{
border-radius:10px;
}

</style>

<div class="page-header">
    <h1 class="page-title">Transaksi Arus Kas</h1>
</div>
@include('layouts.inc.breadcrumb')
<div class="page-content">
    <div class="panel">
        <div class="panel-body">
		@include('flash-message')
            <h3 align="center">Tambah Transaksi Arus Kas</h3><br/>
	            <form action="{{ url('/simpan-transaksi-arus-kas')  }}" method="post">{{ @csrf_field() }}

                    <div class="form-group row">
			            <label class="col-md-3">Tipe</label>
				            <div class="col-md-7">
				                <select id="tipe" name="tipe" class="form-control" required>
            		            <option value="">Pilih Jenis Pembayaran Kas</option>
            		            <option value="1">Penambah</option>
                                <option value="-1">Pengurang</option>
          		            </select>
			            </div>
		            </div>

                    <div class="form-group row">
		                <label class="col-md-3">Kode</label>
			                <div class="col-md-7">
			                <input type="text" name="kode" class="form-control" id="kode" readonly>
		                </div>
	                </div>

                    <div class="form-group row">
			            <label class="col-md-3">Jenis Penerimaan Kas</label>
				            <div class="col-md-7">
				                <select id="id_pembayaran" class="form-control select">
            		            <option value="">Pilih Jenis Pembayaran Kas</option>
					            @foreach ($arusKas as $id =>$kas)
            		            <option value="{{ $id}}">{{ $kas }}</option>
            		            @endforeach
          		            </select>
			            </div>
		            </div>

		            <div class="form-group row">
			            <label class="col-md-3">Sub Jenis Penerimaan Kas</label>
				            <div class="col-md-7">
					        <select id="id_arus_kas" name="id_arus_kas" class="form-control select">
					            <option value="">Pilih Sub Jenis Penerimaan Kas</option>
                            </select>
			            </div>
		            </div>

                    <div class="form-group row">
			            <label class="col-md-3">Tanggal</label>
				            <div class="col-md-7">
				            <input type="date" name="tanggal" value="{{ date('Y-m-d') }}" class="form-control">
			            </div>
		            </div>

                    <div class="form-group row">
                        <label class="col-md-3">Keterangan</label>
                            <div class="col-md-7">
                            <textarea class="form-control" id="keterangan" name="keterangan_awal" rows="3"></textarea>
                        </div>
                    </div>

                    <div class="form-group row">
			            <label class="col-md-3">Penerimaan</label>
				            <div class="col-md-7">
				            <select name="id_kas_bank" id="id_bank" class="form-control select" style="border-radius:10px">
            	                <option value="">Pilih Bank</option>
				                @foreach ($kasBank as $id=>$bank)
				                <option value="{{ $id }}">{{ $bank }}</option>
				                @endforeach
          		            </select>
			            </div>
		            </div>

                    <div class="form-group row">
                        <label class="col-md-3">Total Nominal</label>
                            <div class="col-md-7">
                            <input type="text" id="total_nominal" name="total_nominal" class="form-control">
                        </div>
                    </div>

                <button type="submit" align="right" class="btn btn-primary"><i class="icon glyphicon glyphicon-floppy-saved"></i></i>Simpan</button>
            </form>
        </div>
    </div>
</div>
@endsection

@push('js')
<script type="text/javascript">

$('#total_nominal').on('change click keyup input paste',(function (event) {
    $(this).val(function (index, value) {
        return value.replace(/(?!\,)\D/g, "").replace(/(?<=\,,*)\./g, "").replace(/(?<=\.\d\d).*/g, "").replace(/\B(?=(\d{3})+(?!\d))/g, ".");
    });
}));

$('#id_bank').select2({
    width: '100%'
});

$('#id_arus_kas').select2({
    width: '100%'
});

$('#id_pembayaran').select2({
    width: '100%'
});

$(document).ready(function(){

$('#tipe').change(function(){
    var tipe = $(this).val();
    var url = '{{ route("isiKodeBkm", ":tipe") }}';
    url = url.replace(':tipe', tipe);

    $.ajax({
        url: url,
        type: 'get',
        dataType: 'json',
        success: function(response){
        if(response != null){
            $('#kode').val(response.kode);
        }}
    });
});

$('#id_pembayaran').change(function(){
   	var id_pembayaran = $(this).val();
	$('#id_arus_kas').find('option').not(':first').remove();

$.ajax({
	url: 'id_pembayaran/'+id_pembayaran,
	type: 'get',
	dataType: 'json',
	success: function(response){

	   	var len = 0;
	   	if(response['data'] != null){
			len = response['data'].length;
	   	}

	   	if(len > 0){

			for(var i=0; i<len; i++){

		   		var id  = response['data'][i].id;
		   		var nama = response['data'][i].nama;
		   		var option = "<option value='"+id+"'>"+nama+"</option>";
		   		$("#id_arus_kas").append(option);
		 	}}}
  		});
	});
});

</script>
@endpush
