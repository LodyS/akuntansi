@extends('layouts.app')

@section('content')

<div class="page-header">
    <h1 class="page-title">Detail Setting Unit Profit & Loss</h1>
</div>
@include('layouts.inc.breadcrumb')
<div class="page-content">
    <div class="panel">
        <div class="panel-body">

            <div class="form-group row">
			    <div class="col-md-4">
			    <input type="text" class="form-control btn-round" value="Variable Profit & Loss : {{ optional($surplusUnit)->nama }}" readonly>
		        </div>
	        </div>

            <table class="table table-hover" id="detail">
                <tr>

                    <th>No</th>
                    <th>Unit</th>
                    <th>Aksi</th>
                </tr>

                @foreach($data as $key=>$d)
                <tr>

                    <td>{{ $key + $data->firstItem() }}</td>
                    <td>{{ $d->nama }}</td>
                    <td>
                    <button type="button" class="btn btn-xs btn-success edit" data-id="{{$d->id}}">Edit</button>
                    <button type="button" class="btn btn-xs btn-danger delete" data-id="{{$d->id}}">Hapus</button></td>
                </tr>
                @endforeach
	        </table>
            {{ $data->appends(request()->toArray())->links() }}
        </div>
    </div>
</div>

<div class="modal inmodal fade" id="modal-edit" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-xs">
        <form name="frm_edit" id="frm_edit" class="form-horizontal" action="{{route('update-surplus-defisit-unit')}}" method="POST">@csrf
            <div class="modal-content">
                <div class="modal-header">
                <h4 class="modal-title">Edit Detail Setting Surplus Defisit<br>

            </div>

            <div class="modal-body">

                <div class="form-group row">
		            <label class="col-md-3">Data yang terpilih</label>
			            <div class="col-md-7">
                        <input type="text" readonly class="form-control" id="nama">
		            </div>
	            </div>

                <div class="form-group row">
		            <label class="col-md-3">Unit</label>
			            <div class="col-md-7">
                            <select name="id_unit" id="id_unit" class="form-control select" required>
                                <option value="">Pilih Unit</option>
                                <?php $unit = App\Models\Unit::get(['id', 'nama', 'code_cost_centre']); ?>
                                @foreach ($unit as $u)
                                <option value="{{ $u->id }}">{{ $u->nama }}</option>
                                @endforeach
			                </select>
		                </div>
	                </div>
                </div>

                <div class="modal-footer">
                    <input type="hidden" name="id" id="id_edit">
                    <button type="button" class="btn btn-white" data-dismiss="modal">Tutup</button>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </div>
            </div>
        </form>
    </div>
</div>


<div class="modal inmodal fade" id="modal-delete" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-xs">
        <form name="frm_delete" id="frm_delete" class="form-horizontal" action="{{route('remove-surplus-defisit-unit')}}" method="POST">@csrf
            <div class="modal-content">
                <div class="modal-header">
                <h4 class="modal-title">Apakah Anda yakin untuk menghapus data ini<br>
            </div>

            <div class="modal-body">

                </div>

                <div class="modal-footer">
                    <input type="hidden" name="id" id="id">
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

$(document).ready(function() {

    $('.edit').on("click",function() {
        var id = $(this).attr('data-id');
        $.ajax({
            url : "{{route('edit-surplus-defisit-unit')}}?id="+id,
            type: "GET",
            dataType: "JSON",
            success: function(data){
                $('#id_edit').val(data.id);
                $('#nama').val(data.nama);
                $('#modal-edit').modal('show');
            }
        });
    });

    $('.delete').on("click",function() {
        var id = $(this).attr('data-id');
        $.ajax({
            url : "{{route('delete-surplus-defisit-unit')}}?id="+id,
            type: "GET",
            dataType: "JSON",
            success: function(data){
                $("#id").val(data.id);
                $('#modal-delete').modal('show');
            }
        });
    });


});
</script>
@endpush

