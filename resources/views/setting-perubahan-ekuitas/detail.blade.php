@extends('layouts.app')

@section('content')

<div class="page-header">
    <h1 class="page-title">Detail Setting Perubahan Ekuitas</h1>
    @include('layouts.inc.breadcrumb')
    <div class="page-header-actions">
    <a class="btn btn-block btn-primary" href="{{ url('setting-perubahan-ekuitas/form-tambah', $id_set_lap_ekuitas) }}">Tambah</a>
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
            <b>Jenis Perubahan Ekuitas : {{ isset($nama) ? $nama->nama : ' '}}</b>
            <table class="table table-hover dataTable table-striped w-full" id="detail-set-neraca-detail-table">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Komponen Surplus Defisit </th>
                        <th>Kode</th>
                        <th>Rekening COA</th>
                        <th>Action</th>
                    </tr>

                    @foreach ($data as $key =>$detail)
                    <tr>
                        <td>{{ $key + $data->firstItem() }}</td>
                        <td>{{ $detail->komponen_surplus_defisit }}</td>
                        <td>{{ $detail->kode_rekening }}</td>
                        <td>{{ $detail->rekening_coa }}</td>
                        <td><a href="{{ url('setting-perubahan-ekuitas/form-edit', $detail->id) }}" class="btn btn-xs btn-success">Edit</a>
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
        <form name="frm_delete" id="frm_delete" class="form-horizontal" action="{{route('remove-perubahan-ekuitas')}}" method="POST">@csrf
            <div class="modal-content">
                <div class="modal-header">
                <h4 class="modal-title">Apakah Anda yakin untuk menghapus data ini<br>
            </div>

            <div class="modal-body">

                </div>

                <div class="modal-footer">
                    <input type="hidden" name="id" id="id_setting">
                    <input type="hidden" name="id_set_lap_ekuitas" id="id_set_lap_ekuitas">
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
            url : "{{route('delete-perubahan-ekuitas')}}?id="+id,
            type: "GET",
            dataType: "JSON",
            success: function(data){
                $("#id_setting").val(data.id);
                $("#id_set_lap_ekuitas").val(data.id_set_lap_ekuitas)
                $('#modal-delete').modal('show');
            }
        });
    });
});
</script>
@endpush
