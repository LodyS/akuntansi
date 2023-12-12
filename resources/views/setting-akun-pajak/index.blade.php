@extends('layouts.app')

@section('content')
<div class="page-header">
    <h1 class="page-title">Rekening Kontrol Pajak</h1>
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
    	<form action="{{ ($setting == null) ? url('simpanSettingPajak') : url('updateSettingPajak') }}" method="post">{{ @csrf_field() }}
        	<input type="hidden" name="user_update" value="{{ Auth::user()->id }}">
			<input type="hidden" name="user_input" value="{{ Auth::user()->id }}">
			<input type="hidden" name="keterangan" value="Pajak">

       	 		<div class="form-group row">
					<label class="col-md-3">Pajak Masukan</label>
						<div class="col-md-7">
							<select name="id_perkiraan[]" class="form-control select" id="satu" required>
							<option value="">Pilih Perkiraan</option>
                    		@foreach ($perkiraan as $kira)
                    		<option value="{{ $kira->id }}" {{ (optional($pajakMasukan)->id_perkiraan == $kira->id)?'selected': '' }}>{{ $kira->nama }}</option>
                    		@endforeach
                    		</select>
						<input type="hidden" name="id[]" value="{{ optional($pajakMasukan)->id  }}">
						<input type="hidden" name="jenis[]" value="Pajak Masukan">
					</div>
				</div>

				<div class="form-group row">
					<label class="col-md-3">Pajak Keluaran</label>
						<div class="col-md-7">
							<select name="id_perkiraan[]" class="form-control select" id="dua" required>
							<option value="">Pilih Perkiraan</option>
                    		@foreach ($perkiraan as $kira)
                    		<option value="{{ $kira->id }}" {{ (optional($pajakKeluaran)->id_perkiraan == $kira->id)?'selected':''}}>{{ $kira->nama }}</option>
                    		@endforeach
                    		</select>
						<input type="hidden" name="id[]" value="{{ optional($pajakKeluaran)->id  }}">
						<input type="hidden" name="jenis[]" value="Pajak Keluaran">
					</div>
				</div>

				<div class="form-group row">
					<label class="col-md-3">PPh Badan</label>
						<div class="col-md-7">
							<select name="id_perkiraan[]" class="form-control select" id="tiga" required>
							<option value="">Pilih Perkiraan</option>
                    		@foreach ($perkiraan as $kira)
                    		<option value="{{ $kira->id }}" {{ (optional($pphBadan)->id_perkiraan == $kira->id)?'selected':''}}>{{ $kira->nama }}</option>
                    		@endforeach
                    		</select>
						<input type="hidden" name="id[]" value="{{ optional($pphBadan)->id  }}">
						<input type="hidden" name="jenis[]" value="PPh Badan">
					</div>
				</div>

				<div class="form-group row">
					<label class="col-md-3">PPh PS 21 Karyawan</label>
						<div class="col-md-7">
							<select name="id_perkiraan[]" class="form-control select" id="empat" required>
							<option value="">Pilih Perkiraan</option>
                    		@foreach ($perkiraan as $kira)
                    		<option value="{{ $kira->id }}" {{ (optional($pphKaryawan)->id_perkiraan == $kira->id)?'selected':''}}>{{ $kira->nama }}</option>
                    		@endforeach
                    		</select>
						<input type="hidden" name="id[]" value="{{ optional($pphKaryawan)->id  }}">
						<input type="hidden" name="jenis[]" value="PPh PS 21 Karyawan">
					</div>
				</div>

				<div class="form-group row">
					<label class="col-md-3">PPh 21 Dokter</label>
						<div class="col-md-7">
							<select name="id_perkiraan[]" class="form-control select" id="lima" required>
							<option value="">Pilih Perkiraan</option>
                    		@foreach ($perkiraan as $kira)
                    		<option value="{{ $kira->id }}" {{ (optional($pphDokter)->id_perkiraan == $kira->id) ? 'selected' : '' }}>{{ $kira->nama }}</option>
                    		@endforeach
                    		</select>
						<input type="hidden" name="id[]" value="{{ optional($pphDokter)->id  }}">
						<input type="hidden" name="jenis[]" value="PPh 21 Dokter">
					</div>
				</div>

				<div class="form-group row">
					<label class="col-md-3">Hutang PPh PS 21</label>
						<div class="col-md-7">
							<select name="id_perkiraan[]" class="form-control select" required id="enam">
							<option value="">Pilih Perkiraan</option>
                    		@foreach ($perkiraan as $kira)
                    		<option value="{{ $kira->id }}" {{ (optional($pphDuaSatu)->id_perkiraan == $kira->id)?'selected':''}}>{{ $kira->nama }}</option>
                    		@endforeach
                    		</select>
						<input type="hidden" name="id[]" value="{{ optional($pphDuaSatu)->id  }}">
						<input type="hidden" name="jenis[]" value="Hutang PPh PS 21">
					</div>
				</div>

				<div class="form-group row">
					<label class="col-md-3">Hutang PPh PS 25</label>
						<div class="col-md-7">
							<select name="id_perkiraan[]" class="form-control select" required id="tujuh">
							<option value="">Pilih Perkiraan</option>
                    		@foreach ($perkiraan as $kira)
                   			<option value="{{ $kira->id }}" {{ (optional($pphDuaLima)->id_perkiraan == $kira->id)?'selected':''}}>{{ $kira->nama }}</option>
                    		@endforeach
                    		</select>
						<input type="hidden" name="id[]" value="{{ optional($pphDuaLima)->id }}">
						<input type="hidden" name="jenis[]" value="Hutang PPh PS 25">
					</div>
				</div>

				<div class="form-group row">
					<label class="col-md-3">Hutang PPh PS 26</label>
						<div class="col-md-7">
							<select name="id_perkiraan[]" class="form-control select" required id="delapan">
							<option value="">Pilih Perkiraan</option>
                    		@foreach ($perkiraan as $kira)
                    		<option value="{{ $kira->id }}" {{ (optional($pphDuaEnam)->id_perkiraan == $kira->id)?'selected':''}}>{{ $kira->nama }}</option>
                    		@endforeach
                    		</select>
						<input type="hidden" name="id[]" value="{{ optional($pphDuaEnam)->id  }}">
						<input type="hidden" name="jenis[]" value="Hutang PPh PS 26">
					</div>
				</div>

				<div class="form-group row">
					<label class="col-md-3">Hutang PPh PS 29</label>
						<div class="col-md-7">
							<select name="id_perkiraan[]" class="form-control select" required id="sembilan">
							<option value="">Pilih Perkiraan</option>
                    		@foreach ($perkiraan as $kira)
                    		<option value="{{ $kira->id }}" {{ (optional($pphDuaSembilan)->id_perkiraan == $kira->id)?'selected':''}}>{{ $kira->nama }}</option>
                    		@endforeach
                    		</select>
						<input type="hidden" name="id[]" value="{{ optional($pphDuaSembilan)->id  }}">
						<input type="hidden" name="jenis[]" value="Hutang PPh PS 29">
					</div>
				</div>

				<div class="form-group row">
					<label class="col-md-3">Hutang PBB</label>
						<div class="col-md-7">
							<select name="id_perkiraan[]" class="form-control select" required id="sepuluh">
							<option value="">Pilih Perkiraan</option>
                    		@foreach ($perkiraan as $kira)
                    		<option value="{{ $kira->id }}" {{ (optional($hutangPbb)->id_perkiraan == $kira->id)?'selected':''}}>{{ $kira->nama }}</option>
                    		@endforeach
                    		</select>
						<input type="hidden" name="id[]" value="{{ optional($hutangPbb)->id  }}">
						<input type="hidden" name="jenis[]" value="Hutang PBB">
					</div>
				</div>

        		<button type="submit" align="right" class="btn btn-primary">Simpan</button>
    		</form>
    	</div>
    </div>
</div>
@endsection

@push('js')
<script type="text/javascript">

$('#satu').select2({
 	width : '100%'
});

$('#dua').select2({
 	width : '100%'
});

$('#tiga').select2({
 	width : '100%'
});

$('#empat').select2({
 	width : '100%'
});

$('#lima').select2({
 	width : '100%'
});

$('#enam').select2({
 	width : '100%'
});

$('#tujuh').select2({
 	width : '100%'
});

$('#delapan').select2({
 	width : '100%'
});

$('#sembilan').select2({
 	width : '100%'
});

$('#sepuluh').select2({
 	width : '100%'
});
</script>
@endpush
