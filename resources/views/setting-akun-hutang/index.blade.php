@extends('layouts.app')

@section('content')

<div class="page-header">
    <h1 class="page-title">Rekening Kontrol Hutang Jangka Pendek</h1>
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

            <input type="hidden" name="keterangan" value="Hutang Jangka Pendek">
            <input type="hidden" name="user_input" value="{{ Auth::user()->id }}">
            <input type="hidden" name="user_update" value="{{ Auth::user()->id }}">

            <div class="form-group row">
		        <label class="col-md-3">Hutang Supplier Obat</label>
		            <div class="col-md-7">
			        <select name="id_perkiraan[]" class="form-control" id="hutang_supplier_obat" required>
                        <option value="">Pilih Perkiraan</option>
                            @foreach ($perkiraan as $p)
                            <option value="{{ $p->id }}" {{ (isset($hutangSupplierObat) && $hutangSupplierObat->id_perkiraan == $p->id)?'selected':''}}>{{ $p->nama }}</option>
                            @endforeach
                            </select>
                        <input type="hidden" name="jenis[]" value="Hutang Supplier Obat">
                    <input type="hidden" name="id[]" value="{{ isset($hutangSupplierObat) ? $hutangSupplierObat->id : ''}}">
		        </div>
		    </div>

            <div class="form-group row">
				<label class="col-md-3">Hutang Supplier Logistik</label>
					<div class="col-md-7">
				        <select name="id_perkiraan[]" class="form-control" id="hutang_supplier_logistik" required>
                            <option value="">Pilih Perkiraan</option>
                            @foreach ($perkiraan as $p)
                            <option value="{{ $p->id }}" {{ (isset($hutangSupplierLogistik) && $hutangSupplierLogistik->id_perkiraan == $p->id)?'selected':''}}>{{ $p->nama }}</option>
                            @endforeach
                            </select>
                        <input type="hidden" name="jenis[]" value="Hutang Supplier Logistik">
                    <input type="hidden" name="id[]" value="{{ isset($hutangSupplierLogistik) ? $hutangSupplierLogistik->id : ''}}">
				</div>
			</div>

            <div class="form-group row">
				<label class="col-md-3">Deposito</label>
					<div class="col-md-7">
					<select name="id_perkiraan[]" class="form-control" id="deposito" required>
                        <option value="">Pilih Perkiraan</option>
                            @foreach ($perkiraan as $p)
                            <option value="{{ $p->id }}" {{ (isset($deposito) && $deposito->id_perkiraan == $p->id)?'selected':''}}>{{ $p->nama }}</option>
                            @endforeach
                            </select>
                        <input type="hidden" name="jenis[]" value="Deposito">
                    <input type="hidden" name="id[]" value="{{ isset($deposito) ? $deposito->id : ''}}">
				</div>
			</div>

            <div class="form-group row">
				<label class="col-md-3">Honor Dokter</label>
				    <div class="col-md-7">
					    <select name="id_perkiraan[]" class="form-control" id="honor_dokter" required>
                            <option value="">Pilih Perkiraan</option>
                            @foreach ($perkiraan as $p)
                            <option value="{{ $p->id }}" {{ (isset($honorDokter) && $honorDokter->id_perkiraan == $p->id)?'selected':''}}>{{ $p->nama }}</option>
                            @endforeach
                            </select>
                        <input type="hidden" name="jenis[]" value="Honor Dokter">
                    <input type="hidden" name="id[]" value="{{ isset($honorDokter) ? $honorDokter->id : ''}}">
				</div>
			</div>

            <div class="form-group row">
				<label class="col-md-3">Hutang Gaji Karyawan</label>
					<div class="col-md-7">
					    <select name="id_perkiraan[]" class="form-control" id="hutang_gaji_karyawan" required>
                            <option value="">Pilih Perkiraan</option>
                            @foreach ($perkiraan as $p)
                            <option value="{{ $p->id }}" {{ (isset($hutangGajiKaryawan) && $hutangGajiKaryawan->id_perkiraan == $p->id)?'selected':''}}>{{ $p->nama }}</option>
                            @endforeach
                            </select>
                        <input type="hidden" name="jenis[]" value="Hutang Gaji Karyawan">
                    <input type="hidden" name="id[]" value="{{ isset($hutangGajiKaryawan) ? $hutangGajiKaryawan->id : ''}}">
				</div>
			</div>

            <div class="form-group row">
				<label class="col-md-3">Iuran Astek</label>
					<div class="col-md-7">
					    <select name="id_perkiraan[]" class="form-control" id="iuran_astek" required>
                            <option value="">Pilih Perkiraan</option>
                            @foreach ($perkiraan as $p)
                            <option value="{{ $p->id }}" {{ (isset($iuranAstek) && $iuranAstek->id_perkiraan == $p->id)?'selected':''}}>{{ $p->nama }}</option>
                            @endforeach
                            </select>
                        <input type="hidden" name="jenis[]" value="Iuran Astek">
                    <input type="hidden" name="id[]" value="{{ isset($iuranAstek) ? $iuranAstek->id : ''}}">
				</div>
			</div>

            <div class="form-group row">
				<label class="col-md-3">Hutang Biaya Listrik</label>
				    <div class="col-md-7">
					    <select name="id_perkiraan[]" class="form-control" id="hutang_biaya_listrik" required>
                            <option value="">Pilih Perkiraan</option>
                            @foreach ($perkiraan as $p)
                            <option value="{{ $p->id }}" {{ (isset($biayaListrik) && $biayaListrik->id_perkiraan == $p->id)?'selected':''}}>{{ $p->nama }}</option>
                            @endforeach
                            </select>
                        <input type="hidden" name="jenis[]" value="Hutang Biaya Listrik">
                    <input type="hidden" name="id[]" value="{{ isset($biayaListrik) ? $biayaListrik->id : ''}}">
				</div>
			</div>

            <div class="form-group row">
				<label class="col-md-3">Lain-lain</label>
					<div class="col-md-7">
					    <select name="id_perkiraan[]" class="form-control" id="lain-lain" required>
                            <option value="">Pilih Perkiraan</option>
                            @foreach ($perkiraan as $p)
                            <option value="{{ $p->id }}" {{ (isset($lainLain) && $lainLain->id_perkiraan == $p->id)?'selected':''}}>{{ $p->nama }}</option>
                            @endforeach
                            </select>
                        <input type="hidden" name="jenis[]" value="Lain-lain">
                    <input type="hidden" name="id[]" value="{{ isset($lainLain) ? $lainLain->id : ''}}">
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

$(document).ready(function () {

$("#hutang_supplier_obat").select2({
    width: '100%'
});

$("#hutang_supplier_logistik").select2({
    width: '100%'
});

$("#deposito").select2({
    width: '100%'
});

$("#honor_dokter").select2({
    width: '100%'
});

$("#hutang_gaji_karyawan").select2({
    width: '100%'
});

$("#iuran_astek").select2({
    width: '100%'
});

$("#hutang_biaya_listrik").select2({
    width: '100%'
});

$("#lain-lain").select2({
    width: '100%'
});

});

</script>
@endpush
