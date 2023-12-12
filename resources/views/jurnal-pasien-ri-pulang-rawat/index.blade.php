@extends('layouts.app')

@section('content')   

<div class="page-header">
    <h1 class="page-title">Jurnal Pasien RI Pulang Rawat</h1>
</div>

@include('layouts.inc.breadcrumb')

<div class="page-content">
    <div class="panel">
        <div class="panel-body">
            
			<form action="{{ url('jurnal-pasien-ri-pulang-rawat/rekapitulasi') }}" method="post" id="jurnal">{{ @csrf_field() }} 
				  
				<div class="form-group row">
					<label class="col-md-3">Tanggal Pulang Pasien</label>
						<div class="col-md-7">
						<input type="date" name="tanggal" value="{{ date('Y-m-d')}}" class="form-control" required>
                	</div>
				</div>

				<div class="form-group row">
					<label class="col-md-3">Jenis Pasien</label>
						<div class="col-md-7">
						<select name="tipe_pasien" id="tipe_pasien" class="form-control" required>
							<option value="">Pilih</option>
					        <option value="1">Perusahaan Langganan</option>
							<option value="2">Antar Unit</option>
                     	</select>
					</div>
				</div>
				
				        <button type="submit" align="right" id="cari" class="btn btn-primary">Cari</button>
				</form>
        	</div>
    	</div>
	</div>
</div>
@endsection