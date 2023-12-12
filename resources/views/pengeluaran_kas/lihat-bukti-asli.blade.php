@extends('layouts.app')

@section('content')

<div class="page-header">
    <h1 class="page-title">Bukti Penerimaan Kas </h1>
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
       
        <div class="panel-body" id="print"> 
        <p align="center"><b>BUKTI KAS MASUK</b></p>
            Tanggal : {{date('d-m-Y', strtotime($bukti->tanggal))}}<br/>
            Keterangan : {{ $bukti->keterangan }}<br/>
            Nominal : Rp. {{ number_format($bukti->nominal) }}
            <br/><br/>
            <img src=" {{ url('lampiran/'. $bukti->file) }}" width="800" height="360"><br/>
            <br/>
				<div class="text-left">
                    <button class="btn btn-outline-danger print-link no-print" onclick="jQuery('#print').print()">Cetak </button>
                </div>

            </div>

            
        </div>
    </form>
</div>
@endsection

@push('js')
<script src="https://cdnjs.cloudflare.com/ajax/libs/jQuery.print/1.6.0/jQuery.print.min.js" 
integrity="sha512-i8ERcP8p05PTFQr/s0AZJEtUwLBl18SKlTOZTH0yK5jVU0qL8AIQYbbG5LU+68bdmEqJ6ltBRtCxnmybTbIYpw==" crossorigin="anonymous" 
referrerpolicy="no-referrer"></script>
<script type="text/javascript">
</script>
@endpush