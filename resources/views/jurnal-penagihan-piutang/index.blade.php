@extends('layouts.app')

@section('content')   

<div class="page-header">
    <h1 class="page-title">Penagihan Piutang</h1>
</div>
  @include('layouts.inc.breadcrumb')
<div class="page-content">
    <div class="panel">
        <div class="panel-body">
          
				
	<form action="{{ url('jurnal-penagihan-piutang/rekapitulasi-penagihan-piutang') }}" method="post" id="index">{{ @csrf_field() }} 

		<div class="form-group row">
			<label class="col-md-3">Tipe Pasien</label>
				<div class="col-md-7">
					<select name="tipe_pasien" id="tipe_pasien" class="form-control">  
					<option value="">Pilih Tipe Pasien</option>    
                    <option value="1">Perusahaan Langganan</option>
					<option value="2">Antar Unit</option>
                </select>
			</div>
		</div>

		<div class="form-group row">
			<label class="col-md-3">Jenis Pasien</label>
				<div class="col-md-7">
					<select name="jenis" id="jenis" class="form-control">  
					<option value="">Pilih Jenis Pasien</option>    
                    <option value="RI">Rawat Inap</option>
					<option value="RJ">Rawat Jalan</option>
                </select>
			</div>
		</div>

		<div class="form-group row">
			<label class="col-md-3">Tanggal Penagihan</label>
				<div class="col-md-7">
				<input type="date" name="tanggal" id="tanggal" value="{{ date('Y-m-d') }}" class="form-control">        
			</div>
		</div>
				
		<button type="submit" align="right" id="cari" class="btn btn-primary">Cari</button>
        </div>
    </div>
</div>

<div class=" modal fade" id="formModal" aria-hidden="true" aria-labelledby="formModalLabel" role="dialog" tabindex="-1">
</div>
@endsection

@push('js')
<script type="text/javascript">

$(document).ready(function(){

	$('#index').formValidation({
	framework: "bootstrap4",
	button: {
	    selector: "#cari",
	    disabled: "disabled"
	  },

	icon: null,
	fields: {
		
		tipe_pasien : { 
		validators: {
		notEmpty: {
		message: 'Kolom Tipe Pasien tidak boleh kosong'
				}
			}
		}, 

		tanggal : { 
		validators: {
		notEmpty: {
		message: 'Kolom Tanggal tidak boleh kosong'
				}
			}
		}, 
					
		jenis : { 
		validators: {
		notEmpty: {
		message: 'Kolom Jenis Pasien tidak boleh kosong'
					}
				}
			} 
		},

		err: {
		clazz: 'invalid-feedback'
		},

		control: {
		valid: 'is-valid',
		invalid: 'is-invalid'
		},

		row: {
			invalid: 'has-danger'
		}
	});

});
</script>
@endpush