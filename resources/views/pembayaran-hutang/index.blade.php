@extends('layouts.app')

@section('content')
   <div class="page-header">
     <h1 class="page-title">Pembayaran Hutang</h1>
      @include('layouts.inc.breadcrumb')
     <div class="page-header-actions">
     
     </div>
   </div>
   <div class="page-content">
     <!-- Panel Table Tools -->
     <div class="panel">
       <header class="panel-heading">
         <div class="form-group col-md-12">
           <div class="form-group">
           </div>
         </div>
       </header>

  <div class="panel-body">
    <form action="{{ url('pembayaran-hutang/rekapitulasi')}}" method="post" id="post">{{ @csrf_field() }} 

    <div class="form-group row">
			<label class="col-md-3">Pemasok</label>
				<div class="col-md-7">
				<select name="id_pemasok" id="id_pemasok" class="form-control select">   
          <option value="">Pilih Pemasok</option>
          @foreach ($InstansiRelasi as $pemasok)
          <option value="{{ $pemasok->id }}">{{ $pemasok->nama }}</option>
          @endforeach
        </select>
			</div>
		</div>

    <div class="form-group row">
			<label class="col-md-3">No Faktur</label>
				<div class="col-md-7">
        <input type="text" name="no_faktur" class="form-control">
			</div>
		</div>

    <div class="form-group row">
			<label class="col-md-3">Tanggal Awal</label>
				<div class="col-md-7">
        <input type="date" name="tanggal_awal" id="tanggal_awal" class="form-control" >
			</div>
		</div>

    <div class="form-group row">
			<label class="col-md-3">Tanggal Akhir</label>
				<div class="col-md-7">
        <input type="date" name="tanggal_akhir" id="tanggal_akhir" class="form-control" >
			</div>
		</div>
      
    <button type="submit" id="submit" align="right" class="btn btn-primary">Cari</button>
</form>

       </div>
     </div>
     <!-- End Panel Table Tools -->
 </div>

@endsection

@push('js')

<script type="text/javascript">


$('#id_pemasok').select2({
  width : '100%'
});


</script>
@endpush