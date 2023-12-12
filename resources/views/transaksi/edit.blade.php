@extends('layouts.app')

@section('content')
<div class="page-header">
    <h1 class="page-title">Edit Saldo Awal</h1>
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
	        <form action="{{ url('update-transaksi') }}" method="post">{{ @csrf_field() }}

                <input type="hidden" name="id" value="{{ optional($transaksi)->id }}">
                <input type="hidden" name="id_perkiraan" value="{{ optional($transaksi)->id_perkiraan }}">

		            <div class="form-group row">
			            <label class="col-md-3">Perkiraan</label>
				            <div class="col-md-7">
				            <input type="text" value="{{ optional($transaksi)->perkiraan }}" class="form-control" readonly>
			            </div>
		            </div>

		            <div class="form-group row">
			            <label class="col-md-3">Debet</label>
				            <div class="col-md-7">
				            <input type="text" name="debet" id="debet" value="{{ number_format(optional($transaksi)->debet,2,",",".") }}" class="form-control nominal">
			            </div>
		            </div>

		            <div class="form-group row">
			            <label class="col-md-3">Kredit</label>
				            <div class="col-md-7">
				            <input type="text" name="kredit" id="kredit" value="{{ number_format(optional($transaksi)->kredit,2,",",".") }}" class="form-control nominal">
			            </div>
		            </div>

                    <button type="submit" align="right" class="btn btn-primary">Simpan</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('js')
<script type="text/javascript">

$('.nominal').on('change click keyup input paste',(function (event) {
    $(this).val(function (index, value) {
        return value.replace(/(?!\,)\D/g, "").replace(/(?<=\,,*)\./g, "").replace(/(?<=\.\d\d).*/g, "").replace(/\B(?=(\d{3})+(?!\d))/g, ".");
    });
}));

</script>
@endpush
