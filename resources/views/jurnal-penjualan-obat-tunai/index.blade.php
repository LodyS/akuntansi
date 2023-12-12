@extends('layouts.app')

@section('content')

<div class="page-header">
    <h1 class="page-title">Jurnal Penjualan Obat</h1>
</div>
  @include('layouts.inc.breadcrumb')
<div class="page-content">
    <div class="panel">
        <div class="panel-body">

	<form action="{{ url('jurnal-penjualan-obat-tunai/rekapitulasi-jurnal-penjualan-obat-tunai')}}" method="post">{{ @csrf_field() }}

    <div class="form-group row">
		<label class="col-md-3">Tipe Obat</label>
			<div class="col-md-7">
            <ul class="list-unstyled list-inline mb-0">
                <li class="list-inline-item">
                    <div class="radio-custom radio-primary">
                        <input type="radio" name="tipe_obat" value="Resep" id="resep" onClick="javascript:showForm()" required>
                        <label for="resep">Resep</label>
                    </div>
                </li>
                <li class="list-inline-item">
                    <div class="radio-custom radio-primary">
                        <input type="radio" name="tipe_obat" value="Bebas" id="bebas" onClick="javascript:showForm()" required>
                        <label for="bebas">Bebas</label>
                    </div>
                </li>
            </ul>
		</div>
	</div>

    <div class="form-group row">
		<label class="col-md-3">Tanggal</label>
			<div class="col-md-7">
			<input type="date" name="tanggal" class="form-control" value="{{date('Y-m-d')}}" required>
		</div>
	</div>

    <div id="tampil" style="display:none" class="none">
	    <div class="form-group row">
		    <label class="col-md-3">Jenis Pasien</label>
			    <div class="col-md-7">
                    <select class="form-control" name="jenis_pasien">
                    <option value="">Pilih</option>
                    <option value="RJ">Rawat Jalan</option>
                    <option value="RI">Rawat Inap</option>
                </select>
		    </div>
	    </div>

	    <div class="form-group row">
		    <label class="col-md-3">Tipe Pasien</label>
			    <div class="col-md-7">
                    <select class="form-control" name="tipe_pasien">
                    <option value="">Pilih</option>
                    <option value="1">Perusahaan Langganan</option>
                    <option value="2">Antar Unit</option>
                    <option value="3">Karyawan</option>
                </select>
		    </div>
	    </div>

        <div class="form-group row">
		    <label class="col-md-3">Jenis Pembayaran</label>
			    <div class="col-md-7">
				    <select name="jenis_pembayaran" class="form-control">
                    <option value="">Pilih Pembayaran</option>
                    <option value="Tunai">Tunai</option>
                    <option value="Kredit">Kredit</option>
                </select>
		    </div>
	    </div>
    </div>

        <button type="submit" align="right" class="btn btn-primary">Cari</button>

            </form>
        </div>
    </div>
</div>
@endsection

@push('js')

<script type="text/javascript">

function showForm() {
        if (document.getElementById('resep').checked) {
            document.getElementById('tampil').style.display = 'block';
        } else {
            const selectElm = $('#tampil').find('select');
            selectElm.each(function( index, element ) {
                // kosongkan value agar tidak ter submit saat hidden
                $(this).val('');
            });
            document.getElementById('tampil').style.display = 'none';
        }
    } //untuk menampilkan dan menyembunyikan kolom asuransi

</script>
@endpush
