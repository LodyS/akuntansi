@extends('layouts.app')

@section('content')
<div class="page-header">
    <h1 class="page-title">Setting Cash Flow</h1>
    @include('layouts.inc.breadcrumb')

        <div class="page-header-actions">
        <a href="form" align="right" class="btn btn-primary">Tambah</a>
    </div>
</div>

    <div class="page-content">
        <div class="panel">
            <div class="panel-body">
            @include('flash-message')
                <table class="table table-hover dataTable table-striped w-full" id="setting-surplus-defisit-table">
                    <tr>
                        <th>No</th>
                        <th>Kode</th>
                        <th>Nama</th>
                        <th>Induk</th>
                        <th>Level</th>
                        <th>Urutan</th>
                        <th>Aksi</th>
                    </tr>

                    @foreach($data as $key=> $d)
                    <tr>
                        <td>{{ $key + $data->firstItem() }}</td>
                        <td>{{ $d->kode }}</td>
                        <td>{{ $d->nama}}</td>
                        <td>{{ $d->induk }}</td>
                        <td>{{ $d->level }}</td>
                        <td>{{ $d->urutan }}</td>
                        <td><a href="form/{{$d->id}}" class="btn btn-primary btn-round">
                        <i class="icon glyphicon glyphicon-pencil" aria-hidden="true"></i>Edit</a>
                        <button type="button" class="btn btn-danger btn-round hapus" data-id="{{$d->id}}">
                        <i class="icon glyphicon glyphicon-trash" aria-hidden="true"></i>Hapus</button></td>
                    </tr>
                    @endforeach
                {{ $data->appends(request()->toArray())->links() }}
            </table>
        </div>
    </div>
</div>

<div class="modal inmodal fade" id="modal-edit" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-xs">
        <form name="frm_edit" id="frm_edit" class="form-horizontal" action="{{route('remove-setting-cash-flow')}}" method="POST">@csrf
            <div class="modal-content">
                <div class="modal-header">
                <h4 class="modal-title">Edit Setting Cash Flow.</h4>

            </div>

            <div class="modal-body">
                <div class="form-group row">
		            <label class="col-md-3">Nama</label>
			            <div class="col-md-7">
                            <input type="text" id="nama" class="form-control" readonly>
		                </div>
	                </div>
                </div>

                <div class="modal-footer">
                    <input type="hidden" name="id" id="id">
                    <button type="button" class="btn btn-white" data-dismiss="modal">Tutup</button>
                    <button type="submit" class="btn btn-primary">Hapus</button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection

@push('js')
<script type="text/javascript">

$(document).ready(function() {

    $('.hapus').on("click",function() {
        var id = $(this).attr('data-id');
        //$("#get_id_bank").val(id)
        $.ajax({
            url : "{{route('delete-setting-cash-flow')}}?id="+id,
            type: "GET",
            dataType: "JSON",
            success: function(data){
                $('#id').val(data.id);
                $('#nama').val(data.nama);
                $('#modal-edit').modal('show');
            }
        });
    });
});
</script>
@endpush

