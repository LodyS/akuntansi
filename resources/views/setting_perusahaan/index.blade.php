@extends('layouts.app')

@section('content')

<div class="page-header">
    <h1 class="page-title">Setting Perusahaan</h1>
</div>
@include('layouts.inc.breadcrumb')
<div class="page-content">
    <div class="panel">
        <div class="panel-body">
        <h3 align="center">{{ ($cek == null) ? 'Tambah Setting Perusahaan' : 'Edit Setting Perusahaan' }}</h3><br/>
	    <form action="{{ url('/simpan-setting-perusahaan')  }}" method="post">{{ @csrf_field() }}
        <input type="hidden" name="id" value="{{ optional($cek)->id  }}">

        <div class="form-group row">
		    <label class="col-md-3">Kode Perusahaan</label>
			    <div class="col-md-7">
			    <input type="text" name="kode" class="form-control" value="{{ optional($cek)->kode }}" required>
		      </div>
	      </div>

        <div class="form-group row">
		    <label class="col-md-3">Nama Perusahaan</label>
			    <div class="col-md-7">
			    <input type="text" name="nama" class="form-control" value="{{ optional($cek)->nama }}" required>
		      </div>
	      </div>

        <div class="form-group row">
		    <label class="col-md-3">Alamat</label>
			    <div class="col-md-7">
			    <input type="text" name="alamat" class="form-control" value="{{ optional($cek)->alamat  }}" required>
		    </div>
	    </div>

        <div class="form-group row">
			<label class="col-md-3">Email</label>
			    <div class="col-md-7">
			    <input type="email" name="email" class="form-control" value="{{ optional($cek)->email }}" required>
		    </div>
	    </div>

        <div class="form-group row">
		    <label class="col-md-3">Website</label>
			    <div class="col-md-7">
			    <input type="text" name="website" class="form-control" value="{{ optional($cek)->website  }}" required>
		    </div>
	    </div>

        <div class="form-group row">
		    <label class="col-md-3">Telepon</label>
			    <div class="col-md-7">
			    <input type="number" name="telepon" class="form-control" value="{{ optional($cek)->telepon  }}" required>
		    </div>
	    </div>

        <div class="form-group row">
		    <label class="col-md-3">Fax</label>
			    <div class="col-md-7">
			    <input type="number" name="fax" class="form-control" value="{{ optional($cek)->telepon  }}" required>
		    </div>
	    </div>

        <div class="form-group row">
		    <label class="col-md-3">Kode Pos</label>
			    <div class="col-md-7">
			    <input type="number" name="kode_pos" class="form-control" value="{{ optional($cek)->kode_pos  }}" required>
		    </div>
	    </div>

        <div class="form-group row">
		    <label class="col-md-3">URL</label>
			    <div class="col-md-7">
			    <input type="text" name="url" class="form-control" value="{{ optional($cek)->url }}" required>
		    </div>
      	</div>

        <div class="form-group row">
		    <label class="col-md-3">Tanggal Berdiri</label>
			    <div class="col-md-7">
			    <input type="date" name="tanggal_berdiri" class="form-control" value="{{ isset($cek)?$cek->tanggal_berdiri : date('Y-m-d')}}" required>
		    </div>
	    </div>

              <button type="submit" align="right" class="btn btn-primary">Simpan</button>
            </form>
        </div>
    </div>
</div>
@endsection
