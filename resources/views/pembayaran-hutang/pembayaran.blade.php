@extends('layouts.app')

@section('content')
   
<div class="page-header">
    <h1 class="page-title">Pembayaran Hutang</h1>
        @include('layouts.inc.breadcrumb')
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
            <form action="{{ url('/simpan-pembayaran-hutang')}}" method="post">{{ @csrf_field() }} 

                <input type="hidden" name="id" value="{{ $pembelian->id }}" class="form-control">
                    <input type="hidden" name="id_perkiraan" value="{{ $pembelian->id_perkiraan }}">
                    <input type="hidden" name="id_pemasok" value="{{ $pembelian->id_pemasok }}">
  
                    <div class="form-group row">
		                <label class="col-md-3">No Bukti</label>
			                <div class="col-md-7">
                            @if ($no_bukti == null)
                            <input type="text" name="no_bukti" value="BKK-1" class="form-control" readonly>
                            @else
                            <input type="text" name="no_bukti" value="{{ $no_bukti->bukti_pembayaran }}" class="form-control" readonly>
                            @endif
		                </div>
	                </div>

                    <div class="form-group row">
		                <label class="col-md-3">Tanggal Pembelian</label>
		                    <div class="col-md-7">
                            <input type="date" name="tanggal_pembelian" value="{{ $pembelian->tanggal }}" class="form-control" readonly>
		                </div>
	                </div>

                    <div class="form-group row">
		                <label class="col-md-3">Tanggal Pembayaran</label>
			                <div class="col-md-7">
                            <input type="date" name="tanggal_pembayaran" id="tanggal_pembayaran" value="{{date('Y-m-d')}}" class="form-control" >
		                </div>
	                </div>

                    <div class="form-group row">
		                <label class="col-md-3">Supplier</label>
			                <div class="col-md-7">
                            <input type="text" name="nama_pemasok" value="{{ $pembelian->nama }}" class="form-control" readonly>
		                </div>
	                </div>

                    <div class="form-group row">
		                <label class="col-md-3">Cara Bayar</label>
			                <div class="col-md-7">
			                <select name="id_bank" id="id_bank" class="form-control" required>   
                                <option value="">Pilih Bank</option>
                                @foreach ($KasBank as $bank)
                                <option value="{{ $bank->id }}">{{ $bank->nama }}</option>
                                @endforeach
                            </select>
		                </div>
	                </div>

                    <div class="form-group row">
		                <label class="col-md-3">Rekening</label>
			                <div class="col-md-7">
                            <input type="text" name="rekening" value="{{ $pembelian->rekening }}" class="form-control" readonly>
		                </div>
	                </div>

                    <div class="form-group row">
		                <label class="col-md-3">Dibayar Oleh</label>
			                <div class="col-md-7">
                            <input type="text" name="dibayar_oleh" class="form-control" required>
		                </div>
	                </div>
    
                    <div class="form-group row">
		                <label class="col-md-3">Total Pembelian</label>
			                <div class="col-md-7">
                            <input type="hidden" name="total_pembelian" value="{{ $pembelian->total_pembelian }}">
                            <input type="text" value="{{ number_format($pembelian->jumlah_nominal,2,",",".") }}" class="form-control" readonly>
		                </div>
	                </div>

                    <div class="form-group row">
		                <label class="col-md-3">Diskon</label>
			                <div class="col-md-7">
                            <input type="hidden" name="diskon" value="{{ $pembelian->diskon }}">
                            <input type="text" value="{{ number_format($pembelian->diskon,2,",",".") }}" class="form-control" readonly>
		                </div>
	                </div>

                    <div class="form-group row">
		                <label class="col-md-3">Materai</label>
			                <div class="col-md-7">
                            <input type="hidden" name="materai" value="{{ $pembelian->materai }}">
                            <input type="text" value="{{ number_format($pembelian->materai,2,",",".") }}" class="form-control" readonly>
		                </div>
	                </div>

                    <div class="form-group row">
		                <label class="col-md-3">PPN</label>
			                <div class="col-md-7">
                            <input type="hidden" name="ppn" value="{{ $pembelian->ppn }}">
                            <input type="text" value="{{ number_format($pembelian->ppn,2,",",".") }}" class="form-control" readonly>
		                </div>
	                </div>

                    <div class="form-group row">
		                <label class="col-md-3">Total Tagihan</label>
			                <div class="col-md-7">
                            <input type="hidden" name="total_tagihan" id="total_tagihan" value="{{ $pembelian->total_tagihan }}">
                            <input type="text" value="{{ number_format($pembelian->total_tagihan,2, ",",".") }}" class="form-control" readonly>
		                </div>
	                </div>

                    <div class="form-group row">
		                <label class="col-md-3">Pembayaran</label>
			                <div class="col-md-7">
                            <input type="text" name="pembayaran" id="pembayaran" class="form-control" required>
		                </div>
	                </div>

                    <div class="form-group row">
		                <label class="col-md-3">Sisa Tagihan</label>
			                <div class="col-md-7">
                            <input type="hidden" name="sisa_tagihan" id="sisa_tagihan">
                            <input type="text" id="sisa_tagihan_rupiah" class="form-control" readonly>
		                </div>
	                </div>

                <button type="submit" id="submit" align="right" class="btn btn-primary">Simpan</button>
            </form>
        </div>
    </div>
</div>
 
@endsection

@push('js')

<script type="text/javascript">

$('#pembayaran').on('change click keyup input paste',(function (event) {
    $(this).val(function (index, value) {
        return value.replace(/(?!\,)\D/g, "").replace(/(?<=\,,*)\,/g, "").replace(/(?<=\.\d\d).*/g, "").replace(/\B(?=(\d{3})+(?!\d))/g, ".");
    });
}));

function formatRupiah(number) {
    return number.toString().replace(/(?!\,)\D/g, "").replace(/(?<=\,,*)\./g, "").replace(/(?<=\.\d\d).*/g, "").replace(/\B(?=(\d{3})+(?!\d))/g, ".");
}

$(document).on('keyup change', "#pembayaran", "#total_tagihan",  function() {
    var val1 = $("#total_tagihan").val()
    var val2 = $("#pembayaran").val()
    var pembayaran = Number(val2.replace(/[^0-9]+/g,""));
  
    var result = parseFloat(val1) - parseFloat(pembayaran)
    $("#sisa_tagihan").val(result)
    $("#sisa_tagihan_rupiah").val(formatRupiah(result))
}); // sisa tagihan

$('#sisa_tagihan_rupiah').on('change click keyup input paste',(function (event) {
    $(this).val(function (index, value) {
        return value.replace(/(?!\,)\D/g, "").replace(/(?<=\,,*)\,/g, "").replace(/(?<=\.\d\d).*/g, "").replace(/\B(?=(\d{3})+(?!\d))/g, ".");
    });
}));

</script>
@endpush