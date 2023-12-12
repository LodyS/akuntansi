@extends('layouts.app')

@section('content')

<div class="page-header">
    <h1 class="page-title">Saldo Awal</h1>
        <div class="page-header-actions">
        <a class="btn btn-block btn-primary" href="{{ url('saldo-awal/form') }}">Tambah</a>
    </div>
</div>
@include('layouts.inc.breadcrumb')

<div class="page-content">
    <div class="panel">

    <header class="panel-heading">
            <div class="form-group col-md-12">
                <div class="form-group">
            <div>
        </div>
    </div>
</header>

        <div class="panel-body">
            <form action="{{ url('saldo-awal/laporan') }}" method="POST">{{ @csrf_field() }}
                <div class="form-group row">
		            <label class="col-md-3">Bulan</label>
			            <div class="col-md-7">
			                <select name="bulan" class="form-control" required>
                            <option value="">Pilih</option>
                            <option value="1">Januari</option>
                            <option value="2">Febuari</option>
                            <option value="3">Maret</option>
                            <option value="4">April</option>
                            <option value="5">Mei</option>
                            <option value="6">Juni</option>
                            <option value="7">Juli</option>
                            <option value="8">Agustus</option>
                            <option value="9">September</option>
                            <option value="10">Oktober</option>
                            <option value="11">November</option>
                            <option value="12">Desember</option>
                        </select>
		            </div>
	            </div>

                <div class="form-group row">
		            <label class="col-md-3">Tahun</label>
			            <div class="col-md-7">
			                <select name="tahun" id="tahun" class="form-control" required>
                            <option value="">Pilih</option>
                            <option value="">Pilih Semua Tahun</option>
                            @for($i=2020; $i<2050; $i++)
                            <option value="{{ $i }}">{{ $i}} </option>
                            @endfor
                        </select>
		            </div>
	            </div>

                <div class="form-group row">
		            <label class="col-md-3">Kode Cost Centre</label>
			            <div class="col-md-7">
			                <select name="id_unit" id="id_unit" class="form-control">
                            <option value="">Pilih</option>
                            @foreach($unit as $unitt)
                            <option value="{{ $unitt->id }}">{{ $unitt->code_cost_centre }} - {{ $unitt->nama }} </option>
                            @endforeach
                        </select>
		            </div>
	            </div>

                <div class="form-group row">
		            <label class="col-md-3">Rekening</label>
			            <div class="col-md-7">
			                <select name="id_perkiraan" id="id_perkiraan" class="form-control">
                            <option value="">Pilih</option>
                            @foreach($perkiraan as $kira)
                            <option value="{{ $kira->id }}">{{ $kira->kode_rekening }} - {{ $kira->nama }}</option>
                            @endforeach
                        </select>
		            </div>
	            </div>

                <button type="submit" align="right" class="btn btn-primary" id="cari">Cari</button>

            </form>
        </div>

            <table class="table table-hover" id="laporan-neraca-saldo">
                <tr>
                    <th>No</th>
                    <th>Account</th>
                    <th>COST</th>
                    <th>Chart Of Account</th>
                    <th>Keterangan</th>
                    <th>Debet</th>
                    <th>Kredit</th>
                    <!--<th>Aksi</th>-->
                </tr>


            @if (isset($data))


                @foreach ($data as $key=>$rekap)
                <tr>
                    <td>{{ $i}}</td>
                    <td>{{ $rekap->kode_rekening }}</td>

                    @if (substr(preg_replace('/[^0-9]/','', $rekap->kode_rekening),0, 1) == 1 ||
                    substr(preg_replace('/[^0-9]/','', $rekap->kode_rekening),0, 1) == 2 ||
                    substr(preg_replace('/[^0-9]/','', $rekap->kode_rekening),0, 1) == 3)

                    <td>NER</td>
                    @else
                    <td>{{ $rekap->code_cost_centre }}</td>
                    @endif

                    @if(substr(preg_replace('/[^0-9]/','', $rekap->kode_rekening),0, 1) == 1 ||
                    substr(preg_replace('/[^0-9]/','', $rekap->kode_rekening),0, 1) == 2 ||
                    substr(preg_replace('/[^0-9]/','', $rekap->kode_rekening),0, 1) == 3)
                    <td>{{ $rekap->kode_rekening }}</td>
                    @else
                    <td>{{ $rekap->code_cost_centre }} - {{ $rekap->kode_rekening }}</td>
                    @endif

                    <td>{{ $rekap->perkiraan }} - {{ $rekap->unit }}</td>
                    <td>Rp. {{ number_format($rekap->debet,2, ",", ".") }}</td>
                    <td>Rp. {{ number_format($rekap->kredit,2, ",", ".") }}</td>
                   <!-- <td><a href="{{ url('saldo-awal/form', $rekap->id) }}" class="btn btn-xs btn-success">Edit</a>
                        <button type="button" class="btn btn-xs btn-danger delete" data-id="{{$rekap->id}}">Hapus</button></td>-->

                </tr>

                @endforeach


                <tr>
                    <td><b>Total</b></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td><b>Rp. {{ number_format($total_debet,2, ",", ".") }}</b></td>
                    <td><b>Rp. {{ number_format($total_kredit,2, ",", ".") }}</b></td>
                    <td></td>
                </tr>@endif



            </table>
        </div>
    </div>
</div>
</div>

<div class="modal inmodal fade" id="modal-delete" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-xs">
        <form name="frm_delete" id="frm_delete" class="form-horizontal" action="{{route('remove-saldo-awal')}}" method="POST">@csrf
            <div class="modal-content">
                <div class="modal-header">
                <h4 class="modal-title">Apakah Anda yakin untuk menghapus data ini<br>
            </div>

            <div class="modal-body">

                </div>

                <div class="modal-footer">
                    <input type="hidden" name="id" id="id_setting">
                    <button type="button" class="btn btn-white" data-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-danger">Hapus</button>
                </div>
            </div>
        </form>
    </div>
</div>

@endsection

@push('js')
<script type="text/javascript">
$("#id_perkiraan").select2({
    width : '100%'
});

$("#id_unit").select2({
    width : '100%'
});

$(document).ready(function() {

    $('.delete').on("click",function() {
        var id = $(this).attr('data-id');
        $.ajax({
            url : "{{route('delete-saldo-awal')}}?id="+id,
            type: "GET",
            dataType: "JSON",
            success: function(data){
                $("#id_setting").val(data.id);
                $('#modal-delete').modal('show');
            }
        });
    });
});

</script>
@endpush
