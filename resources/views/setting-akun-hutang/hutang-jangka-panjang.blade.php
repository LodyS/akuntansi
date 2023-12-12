@extends('layouts.app')

@section('content')

<div class="page-header">
      <h1 class="page-title">Rekening Kontrol Hutang Jangka Panjang</h1>
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

<div class="form-group row">
	<label class="col-sm-3"></label>
		<div class="col-md-5">
		<a href="index" class="btn btn-xs btn-success" >Hutang Jangka Pendek</a>
        <a href="hutang-jangka-panjang" class="btn btn-xs btn-success" >Hutang Jangka Panjang</a>
	</div>
</div>

    <h4>Jenis Hutang : Hutang Jangka Pendek</h4><br/>
        <form action="{{ ($setting == null) ? url('simpanSettingHutang') : url('updateSettingHutang') }}" method="post">{{ @csrf_field() }}

            <input type="hidden" name="keterangan" value="Hutang Jangka Panjang">
            <input type="hidden" name="user_input" value="{{ Auth::user()->id }}">
            <input type="hidden" name="user_update" value="{{ Auth::user()->id }}">

                <div class="form-group row">
		            <label class="col-md-3">Hutang Bank</label>
		                <div class="col-md-7">
			                <select name="id_perkiraan[]" class="form-control select" id="hutang_bank" required>
                                <option value="">Pilih Perkiraan</option>
                                @foreach ($perkiraan as $p)
                                <option value="{{ $p->id }}" {{(isset($hutangBank) && $hutangBank->id_perkiraan == $p->id)?'selected':''}}>{{ $p->nama }}</option>
                                @endforeach
                                </select>
                            <input type="hidden" name="jenis[]" value="Hutang Bank">
                        <input type="hidden" name="id[]" value="{{ isset($hutangBank) ? $hutangBank->id : ''}}">
		            </div>
		        </div>

                <div class="form-group row">
				    <label class="col-md-3">Hutang Leasing</label>
					    <div class="col-md-7">
				            <select name="id_perkiraan[]" class="form-control select" id="hutang_leasing" required>
                                <option value="">Pilih Perkiraan</option>
                                @foreach ($perkiraan as $p)
                                <option value="{{ $p->id }}" {{ (isset($hutangLeasing) && $hutangLeasing->id_perkiraan == $p->id)?'selected':''}}>{{ $p->nama }}</option>
                                @endforeach
                                </select>
                            <input type="hidden" name="jenis[]" value="Hutang Leasing">
                        <input type="hidden" name="id[]" value="{{ isset($hutangLeasing) ? $hutangLeasing->id : ''}}">
				    </div>
			    </div>

                <div class="form-group row">
				    <label class="col-md-3">Hutang Jangka Panjang Lainnya</label>
					    <div class="col-md-7">
				            <select name="id_perkiraan[]" class="form-control select" id="hutang_jangka_panjang_lainnya" required>
                                <option value="">Pilih Perkiraan</option>
                                @foreach ($perkiraan as $p)
                                <option value="{{ $p->id }}" {{ (isset($hutangJangkaPanjang)&&$hutangJangkaPanjang->id_perkiraan == $p->id)?'selected':''}}>{{ $p->nama }}</option>
                                @endforeach
                                </select>
                            <input type="hidden" name="jenis[]" value="Hutang Jangka Panjang Lainnya">
                        <input type="hidden" name="id[]" value="{{ isset($hutangJangkaPanjang) ? $hutangJangkaPanjang->id : ''}}">
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

$('#hutang_bank').select2({
  width : '100%'
});

$('#hutang_leasing').select2({
  width : '100%'
});

$('#hutang_jangka_panjang_lainnya').select2({
  width : '100%'
});

</script>
@endpush
