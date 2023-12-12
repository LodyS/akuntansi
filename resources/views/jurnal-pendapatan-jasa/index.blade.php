@extends('layouts.app')

@section('content')

<div class="page-header">
    <h1 class="page-title">Jurnal Pendapatan Jasa</h1>
</div>

<div class="page-content">
    <div class="panel">
        <div class="panel-body">				
			
			<form action="{{ url('jurnal-pendapatan-jasa/rekapitulasi-pendapatan-jasa') }}" method="post" id="jurnal">{{ @csrf_field() }} 

			<div class="form-group row">
				<label class="col-md-3">Tipe Pembayaran</label>
				  <div class="col-md-7" id="tipe_pembayaran">
					<input type="radio" name="tipe_pembayaran" id="Tunai"  value="Tunai" onClick="javascript:showForm()"><label>Tunai</label>
					<input type="radio" name="tipe_pembayaran" id="Kredit" value="Kredit" onClick="javascript:showForm()"><label>Kredit</label>
				</div>
			</div>

      		<div id="tampil" style="display:none" class="none">
				<div class="form-group row">
					<label class="col-md-3">Cara Pembayaran</label>
						<div class="col-md-7">
						<select name="id_bank" id="bank" class="form-control">
            				<option value="">Pilih</option>
							@foreach ($bank as $b)
							<option value="{{ $b->id }}">{{ $b->nama }}</option>
							@endforeach
						</select>
					</div>
				</div>
			</div>
				   
				<div class="form-group row">
					<label class="col-md-3">Tipe Pasien</label>
						<div class="col-md-7">
						<select name="tipe_pasien" id="tipe_pasien" class="form-control">
							<option value="">Pilih</option>
					        @foreach ($tipe as $t)
                            <option value="{{ $t->id }}">{{ $t->tipe_pasien }}</option>
							@endforeach
                     	</select>
					</div>
				</div>
				
				<div class="form-group row">
					<label class="col-md-3">Jenis Pasien</label>
						<div class="col-md-7">
						<select name="jenis_pasien" id="jenis_pasien" class="form-control">
							<option value="">Pilih</option>
					        <option value="RJ">Rawat Jalan</option>
							<option value="RI">Rawat Inap</option>
                     	</select>
					</div>
				</div>
					 
				 <div class="form-group row">
					<label class="col-md-3">Tanggal</label>
						<div class="col-md-7">
						<input type="date" name="tanggal" value="{{ date('Y-m-d')}}" class="form-control">
					</div>
				</div>
	
				        <button type="submit" align="right" id="cari" class="btn btn-primary">Cari</button>
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
	if (document.getElementById('Tunai').checked){
		document.getElementById('tampil').style.display = 'block';
	} 
	else {
		document.getElementById('tampil').style.display = 'none'; 
	}
}

$(document).ready(function(){
	$("#bank").select2({
		width : '100%'
	})
});

$('#jurnal').formValidation({
	  framework: "bootstrap4",
	  button: {
	    selector: "#cari",
	    disabled: "disabled"
	  },
	  icon: null,
	  fields: {

			tipe_pembayaran : { 
            validators : {
			notEmpty : {
			message : 'Kolom Tipe Pembayaran tidak boleh kosong'
						}
					}
                },

			tipe_pasien : { 
            validators : {
			notEmpty : {
			message : 'Kolom Tipe Pasien tidak boleh kosong'
						}
					}
                },

	        jenis_pasien : { 
            validators : {
			notEmpty : {
			message : 'Kolom Jenis Pasien tidak boleh kosong'
						}
					}
                }, 

			kelas : { 
            validators : {
			notEmpty : {
			message : 'Kolom Kelas tidak boleh kosong'
						}
					}
                }
	  		},
        
        err: {
	    clazz: 'invalid-feedback'
    },
    control: {
	    valid: 'is-valid',  // The CSS class for valid control
	    invalid: 'is-invalid' 	// The CSS class for invalid control
    },

        row: {
	        invalid: 'has-danger'
        }
    });

</script>
@endpush