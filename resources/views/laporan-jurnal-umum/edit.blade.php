@extends('layouts.app')

@section('content')

<div class="page-header">
    <h1 class="page-title">Mutasi Penerimaan Kas</h1>
</div>
@include('layouts.inc.breadcrumb')
<div class="page-content">
    <div class="panel">
        <div class="panel-body">
		@include('flash-message')
            <h3 align="center">Tambah Mutasi Penerimaan Kas</h3><br/>
	            <form action="{{ url('/update-laporan-jurnal-umum')  }}" method="post">{{ @csrf_field() }}

                <input type="hidden" name="id" value="{{ $data->id }}">

                    <div class="form-group row">
		                <label class="col-md-3">Keterangan</label>
			                <div class="col-md-7">
			                <input type="text" class="form-control btn-round" value="{{ isset($data) ? $data->keterangan : ''}}" readonly>
		                </div>
	                </div>

                    <div class="form-group row">
		                <label class="col-md-3">Kode Jurnal</label>
			                <div class="col-md-7">
			                <input type="text" class="form-control btn-round" value="{{ isset($data) ? $data->kode_jurnal : ''}}" readonly>
		                </div>
	                </div>

                    <div class="form-group row">
                        <label class="col-md-3">Status</label>
                            <div class="col-md-7">
                                <select name="status" id="status" class="form-control btn-round" required>
                                <option value="">Pilih</option>
                                <option value="1" {{ ($data->status==1)?'selected':''}}>Pending</option>
                                <option value="2" {{ ($data->status==2)?'selected':''}}>Terverifikasi</option>
                                <option value="3" {{ ($data->status==3)?'selected':''}}>Batal</option>
                            </select>
                        </div>
                    </div>

                <button type="submit" align="right" class="btn btn-primary btn-round"><i class="icon glyphicon glyphicon-floppy-saved" aria-hidden="true"></i>Simpan</button>
            </form>
        </div>
    </div>
</div>
@endsection
