@extends('layouts.app')

@section('content')
<div class="page-header">
    <h1 class="page-title">Rekapitulasi Penagihan Piutang</h1>
</div>

<form action="" method="post" id="check">{{ @csrf_field() }}


<div class="page-content">
    <div class="panel">
        <div class="panel-body">
        @include('flash-message')
            <table class="table table-hover" id="tagihan">
                <tr>
                    <th>No</th>
                    <th>Tanggal</th>
                    <th>Nama</th>
                    <th>No Kunjungan</th>
                    <th>Piutang</th>
                    <th>Aksi</th>
                </tr>
                @php ($i=1)
                    @foreach ($tagihan as $rekap)
                        <tr>
                            <td>{{ $i }}</td>
                            <td>{{ date('d-m-Y', strtotime($rekap->tanggal)) }}</td>
                            <td>{{ $rekap->nama }}</td>
                            <td>{{ $rekap->no_kunjungan }}</td>
                            <td>Rp. {{ number_format($rekap->piutang) }}</td>
                            <td><button type="button" class="btn btn-xs btn-dark btn-round edit" data-id="{{$rekap->id}}"
                            data-original-title="Edit" data-toggle="tooltip">
                            <i class="icon glyphicon glyphicon-pencil" aria-hidden="true"></i></button></td>
                        </tr>
                    @php($i++)
                @endforeach
            </table>
            {{ $tagihan->appends(request()->toArray())->links() }}
	        </form>
        </div>
    </div>
</div>
</div>

<div class="modal inmodal fade" id="modal-edit" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-xs">
        <form name="frm_edit" id="frm_edit" class="form-horizontal" action="{{route('update-penagihan-piutang-pasien')}}" method="POST">@csrf
            <div class="modal-content">
                <div class="modal-header">
                <h4 class="modal-title">Edit Tagihan : <input type="text" readonly class="form-control-plaintext" id="pelanggan"><br/>
                No Kunjungan : <input type="text" readonly class="form-control-plaintext sm-3" id="no_kunjungan">
            </div>

            <div class="modal-body">
                <div class="form-group row">
		            <label class="col-md-3">Checklist</label>
			            <div class="col-md-7">
                        <input type="radio" name="status_tagihan" value="Y"><label>Status Tagihan</label>
		            </div>
	            </div>

                <div class="modal-footer">
                    <input type="hidden" name="id" id="id">
                    <button type="button" class="btn btn-white" data-dismiss="modal">Tutup</button>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </div>
            </div>
        </form>
    </div>
</div>

@endsection

@push('js')
<script type="text/javascript">

$(document).ready(function() {

    $('.edit').on("click",function() {
        var id = $(this).attr('data-id');
        //$("#get_id_bank").val(id)
        $.ajax({
            url : "{{route('edit-penagihan-piutang-pasien')}}?id="+id,
            type: "GET",
            dataType: "JSON",
            success: function(data){
                $('#id').val(data.id);
                $('#no_kunjungan').val(data.no_kunjungan);
                $('#pelanggan').val(data.pelanggan);
                $('#modal-edit').modal('show');
            }
        });
    });
});
</script>
@endpush

