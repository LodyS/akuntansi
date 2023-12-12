@extends('layouts.app')

@section('content')
      <h4 class="modal-title" id="formModalLabel">Edit Spesialisasi</h4><br/>
   
    <form action="{{ url('updateDataSpesialisasi') }}" method="post">
                            {{ @csrf_field() }} 
	<div class="form-body">

  <input type="hidden" name="id" value="{{ $spesialisasi->id }}">

				<div class="form-group row">
					<label class="col-md-3">Nama</label>
					<div class="col-md-7">
					<input name="nama" id="nama" value="{{ $spesialisasi->nama}}" class="form-control" type="text" required>
					</div>
				</div>

                    </div>

				<div class="col-md-12 float-right">
					<div class="text-left">
						<button class="btn btn-primary" id="simpan">Simpan</button>
					</div>
				</div>
	</form>
</div>
@endsection