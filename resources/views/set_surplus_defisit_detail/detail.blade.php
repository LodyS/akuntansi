@extends('layouts.app')

@section('content')

<div class="page-header">
    <h1 class="page-title">Detail Setting Surplus Defisit</h1>
    @include('layouts.inc.breadcrumb')
    <div class="page-header-actions">
     <a class="btn btn-block btn-primary" href="{{ url('set-surplus-defisit-detail/form-tambah', $id) }}">Tambah</a>
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

            Induk : {{ isset($setting) ? $setting->induk : 'Tidak Ada data'}}<br/>
            Komponen Surplus Defisit : {{ isset($setting) ? $setting->nama : 'Tidak ada data' }}<br/>
            Jenis : {{ isset($setting) ? $setting->jenis : 'Tidak ada data' }}<br/>

            <table class="table table-hover dataTable table-striped w-full" id="detail-set-neraca-detail-table">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Kode Cost Centre</th>
                        <th>Unit</th>
                        <th>Action</th>
                    </tr>

                    @foreach ($detail as $key =>$data)
                    <tr>
                        <td>{{ $key + $detail->firstItem() }}</td>
                        <td>{{ $data->kode }}</td>
                        <td>{{ $data->unit }}</td>
                        <td><a href="{{ url('set-surplus-defisit-detail/form-edit', $data->id) }}" class="btn btn-xs btn-success">Edit</a>
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
        <form name="frm_delete" id="frm_delete" class="form-horizontal" action="{{route('remove-set-surplus-defisit-detail')}}" method="POST">@csrf
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
            url : "{{route('delete-set-surplus-defisit-detail')}}?id="+id,
            type: "GET",
            dataType: "JSON",
            success: function(data){
                $("#id_setting").val(data.id);
                $("#id_set_surplus_defisit").val(data.id_set_surplus_defisit);
                $('#modal-delete').modal('show');
            }
        });
    });
});
</script>
@endpush
