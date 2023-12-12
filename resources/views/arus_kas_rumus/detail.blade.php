@extends('layouts.app')

@section('content')

<div class="page-header">
    <h1 class="page-title">Detail Setting Arus Kas</h1>
    @include('layouts.inc.breadcrumb')
    <div class="page-header-actions">
     <a class="btn btn-block btn-primary" href="{{ url('arus-kas-rumus/form', $id) }}">Tambah</a>
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
            @if (session('error'))
                  <div class="alert alert-danger">{{ session('error') }}</div>
            @endif

            Induk : {{ isset($arusKas) ? $arusKas->induk : 'Tidak Ada data'}}<br/>
            Arus Kas : {{ isset($arusKas) ? $arusKas->nama : 'Tidak ada data' }}<br/>

            <table class="table table-hover dataTable table-striped w-full" id="detail-set-neraca-detail-table">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Kode</th>
                        <th>Arus Kas</th>
                        <th>Jenis</th>
                        <th>Aksi</th>
                    </tr>

                    @foreach ($detail as $key =>$data)
                    <tr>
                        <td>{{ $key + $detail->firstItem() }}</td>
                        <td>{{ $data->kode }}</td>
                        <td>{{ $data->nama }}</td>
                        <td>{{ $data->jenis }}</td>
                        <td><a href="{{ url('arus-kas-rumus/edit', $data->id) }}" class="btn btn-xs btn-success">Edit</a>
                        <button type="button" class="btn btn-xs btn-danger delete" data-id="{{$data->id}}">Hapus</button>
                        </td>
                    </tr>
                    @endforeach
                </thead>
            </table>
            {{ $detail->links() }}
        </div>
    </div>
 </div>

 <div class="modal inmodal fade" id="modal-delete" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-xs">
        <form name="frm_delete" id="frm_delete" class="form-horizontal" action="{{route('remove-rumus-arus-kas')}}" method="POST">@csrf
            <div class="modal-content">
                <div class="modal-header">
                <h4 class="modal-title">Apakah Anda yakin untuk menghapus data ini<br>
            </div>

            <div class="modal-body">

                </div>

                <div class="modal-footer">
                    <input type="hidden" name="id" id="id_setting">
                    <input type="hidden" name="id_set_surplus_defisit" id="id_set_surplus_defisit">
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
            url : "{{route('delete-rumus-arus-kas')}}?id="+id,
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
