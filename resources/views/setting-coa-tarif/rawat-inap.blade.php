@extends('layouts.app')

@section('content')

<div class="page-header">
    <h1 class="page-title">Setting COA Tarif Rawat Inap</h1>
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
            <form action="{{ url('setting-coa-tarif/cari-setting-tarif')}}" method="post">{{ @csrf_field() }}
            <input type="hidden" name="asal" value="RI">

                <div class="form-group row">
			        <label class="col-sm-1">Perkiraan</label>
				        <div class="col-md-4">
				        <select name="id_perkiraan" id="perkiraan_id" class="form-control">
                            <option value="">Pilih Perkiraan</option>
                            @foreach ($perkiraan as $bang)
                            <option value="{{ $bang->id }}">{{ $bang->nama }}</option>
                            @endforeach
                        </select>
			        </div>
		        </div>

                <div class="form-group row">
			        <label class="col-sm-1">Tarif</label>
				        <div class="col-md-4">
				        <select name="id_tarif" id="id_tarif" class="form-control">
                            <option value="">Pilih Tarif</option>
                            @foreach ($tariff as $bang)
                            <option value="{{ $bang->id }}">{{ $bang->layanan }}</option>
                            @endforeach
                        </select>
			        </div>
		        </div>

                <button type="submit" id="submit" align="right" class="btn btn-primary">Cari</button>
            </form>
          <br/>
          @include('flash-message')
       <div class="collapse navbar-collapse" id="navbarSupportedContent" align="right">
            <ul class="navbar-nav mr-auto">
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" role="button" data-toggle="dropdown" aria-haspopup="true">Tarif Berdasarkan Kunjungan</a>
                        <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                        <a href="rawat-jalan" class="dropdown-item" >Rawat Jalan</a>
                        <a href="rawat-inap" class="dropdown-item" >Rawat Inap</a>
                    </div>
                </li>
            </ul>
        </div>

            <form action="{{ url('/simpan-setting-tarif') }}" method="post" id="create">{{ @csrf_field() }}

            <input type="hidden" name="user_input" value="{{ Auth::user()->id }}">
            <input type="hidden" name="type" value="RI">
            <input type="hidden" name="status" value="{{ isset($status) ? 'Ada' : 'Kosong' }}">

                    <div class="form-group row">
		                <label class="col-md-3">COA</label>
		                    <div class="col-md-7">
			                    <select name="id_perkiraan" class="form-control" id="id_perkiraan" required>
                                <option value="">Pilih Perkiraan</option>
                                @foreach ($perkiraan as $p)
                                <option value="{{ $p->id }}">{{ $p->nama }}</option>
                                @endforeach
                            </select>
		                </div>
		            </div>

                        <table class="table table-hover">
                            <tr>
                                <th>No</th>
                                <th>Nama Tarif</th>
                                <th>Kelas</th>
                                <th>Tarif</th>
                                <th>Aksi</th>
                            </tr>
                        @php ($i=1)
                        @foreach($tarif as $key => $coa)
                            <tr>
                                <input type="hidden" name="id_tarif[]" value="{{ $coa->id }}">
                                <input type="hidden" name="id_kelas[]" value="{{ $coa->id_kelas }}">
                                <td>{{ $i }}</td>
                                <td>{{ $coa->nama_tarif }}</td>
                                <td>{{ $coa->kelas }}</td>
                                <td>Rp. {{ number_format($coa->total) }}</td>
                                <td><input type="radio" class="check" name="centang[]{{ $i }}" value="Y">Ya
                                <input type="radio" name="centang[]{{ $i }}" value="N" checked>Tidak</td>
                            </tr>
                            @php($i++)
                            @endforeach
                        </table>
                    <button type="submit" align="right" class="btn btn-primary">Simpan</button>
                </form><br/>
            <p3>Keterangan : Hanya bisa ceklis ya 10 kali</p3>
        </div>
    </div>
</div>
@endsection

@push('js')
<script type="text/javascript">
$("#perkiraan_id").select2({
	width: '100%'
});

$("#id_tarif").select2({
	width: '100%'
});

$("#id_perkiraan").select2({
	width: '100%'
});

var checks = document.querySelectorAll(".check");
var max = 10;

for (var i = 0; i < checks.length; i++)
    checks[i].onclick = selectiveCheck;

function selectiveCheck (event)
{
    var checkedChecks = document.querySelectorAll(".check:checked");

    if (checkedChecks.length >= max + 1)
    {
        alert("Sudah tidak bisa checklist tarif");
        return false;
    }
}
</script>
@endpush
