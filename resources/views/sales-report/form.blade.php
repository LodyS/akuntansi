@extends('layouts.app')

@section('content')

<style>
.form-control{
  -webkit-border-radius: 0;
     -moz-border-radius: 0;
          border-radius: 0;
}
</style>

<div class="page-header">
    <h1 class="page-title">Sales report</h1>
</div>
@include('layouts.inc.breadcrumb')
<div class="page-content">
    <div class="panel">
        <div class="panel-body">
		@include('flash-message')
            <h3 align="center">Tambah Sales Report Detail</h3><br/>
	            <form action="{{ url('/simpan-sales-report-detail')  }}" method="post">{{ @csrf_field() }}

                    <div class="form-group row">
			            <label class="col-md-3">Sales Report</label>
				            <div class="col-md-7">
				                <select id="id_sales_report" name="id_sales_report" class="form-control select round">
            		            <option value="">Pilih</option>
					            @foreach ($salesReport as $sales)
            		            <option value="{{ $sales->id}}">{{ $sales->nama }}</option>
            		            @endforeach
          		            </select>
			            </div>
		            </div>

                    <div class="form-group row">
			            <label class="col-md-3">Tanggal</label>
				            <div class="col-md-7">
				            <input type="date" name="tanggal" value="{{ date('Y-m-d') }}" class="form-control round">
			            </div>
		            </div>

                    <div class="form-group row">
                        <label class="col-md-3">Persantense Billed</label>
                            <div class="col-md-7">
                            <input type="number" class="form-control round" id="persentase_billed" name="persentase_billed" max="100">
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-md-3">Dispute</label>
                            <div class="col-md-7">
                            <input type="number" class="form-control round" id="dispute" name="dispute" max="100">
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-md-3">Persantense Dispute</label>
                            <div class="col-md-7">
                            <input type="number" class="form-control round" id="persentase_dispute" name="persentase_dispute" readonly>
                        </div>
                    </div>

                <button type="submit" align="right" class="btn btn-primary btn-round"><i class="icon glyphicon glyphicon-floppy-saved" aria-hidden="true"></i>Simpan</button>
            </form>
        </div>
    </div>
</div>
@endsection

@push('js')
<script type="text/javascript">

$(document).on('keyup change', "#dispute",  function() {
    var dispute = $("#dispute").val();
    var hasil = parseInt(100) - parseInt(dispute);
    console.log(hasil);
    $("#persentase_dispute").val(hasil);
    $("#persentase_dispute").change();

}); // hitung total yang harus dibayar

</script>
@endpush
