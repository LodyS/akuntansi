@extends('layouts.app')

@section('content')

<div class="page-header">
    <h1 class="page-title">Detail Rumus Neraca</h1>
    @include('layouts.inc.breadcrumb')
    <div class="page-header-actions">
     <a class="btn btn-block btn-primary" href="{{ url('setting-rumus-neraca/form-tambah', $id) }}">Tambah</a>
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
            Jenis Neraca : {{ isset($nama) ? $nama->jenis_neraca : 'Tidak ada' }}<br/>
            Neraca : {{ isset($nama) ? $nama->nama : 'Tidak ada' }}<br/>
            <table class="table table-hover dataTable table-striped w-full" id="detail-set-neraca-detail-table">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Kode</th>
                        <th>Neraca</th>
                        <th>Rumus</th>
                        <th>Jenis</th>
                        <th>Action</th>
                    </tr>

                    @foreach ($data as $key =>$detail)
                    <tr>
                        <td>{{ $key + $data->firstItem() }}</td>
                        <td>{{ $detail->kode }}</td>
                        <td>{{ $detail->nama }}</td>
                        <td>{{ $detail->rumus }}</td>
                        <td>{{ $detail->jenis }}</td>
                        <td><a href="{{ url('setting-rumus-neraca/form-edit', $detail->id) }}" class="btn btn-xs btn-success">Edit</a>
                        <button type="button" class="btn btn-xs btn-danger delete" data-id="{{$detail->id}}">Hapus</button>
                        </td>
                    </tr>
                    @endforeach
                </thead>
            </table>
            {{ $data->links() }}
        </div>
    </div>
 </div>

 <div class="modal inmodal fade" id="modal-delete" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-xs">
        <form name="frm_delete" id="frm_delete" class="form-horizontal" action="{{route('remove-setting-rumus-neraca')}}" method="POST">@csrf
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

$(document).ready(function() {

    $('.delete').on("click",function() {
        var id = $(this).attr('data-id');
        $.ajax({
            url : "{{route('delete-setting-rumus-neraca')}}?id="+id,
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
