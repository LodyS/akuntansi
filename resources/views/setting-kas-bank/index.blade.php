@extends('layouts.app')

@section('content')
<div class="page-header">
    <h1 class="page-title">Setting COA Kas Bank</h1>
    @include('layouts.inc.breadcrumb')
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
            <table class="table table-hover dataTable table-striped w-full" id="setting">
                <tr>
                    <th>No</th>
                    <th>Kode</th>
                    <th>Bank</th>
                    <th>Perkiraan</th>
                    <th>Edit</th>
                </tr>

                @foreach($data as $key => $coa)
                    <tr>
                        <td>{{ ++$key }}</td>
                        <td> {{ $coa['kode_bank'] }} </td>
                        <td> {{ $coa['nama'] }}</td>
                        <td> {{ $coa['perkiraan'] }}</td>
                        <td><button type="button" class="btn btn-xs btn-danger edit" data-id="{{ $coa['id'] }}">
                        <i class="icon glyphicon glyphicon-pencil"></i></button></td>
                     </tr>
                @endforeach
            </table>
        </div>
    </div>
</div>

<div class="modal inmodal fade" id="modal-edit" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-xs">
        <form name="frm_edit" id="frm_edit" class="form-horizontal" action="{{route('update-setting-coa-kas-bank')}}" method="POST">@csrf
            <div class="modal-content">
                <div class="modal-header">
                <h4 class="modal-title">Edit Kas Bank

            </div>

            <div class="modal-body">

                <div class="form-group row">
		            <label class="col-md-3">Kas Bank</label>
			            <div class="col-md-7">
                        <input type="text" readonly class="form-control" id="nama_bank">
		            </div>
	            </div>

                <div class="form-group row">
		            <label class="col-md-3">Data Rekening</label>
			            <div class="col-md-7">
                        <input type="text" readonly class="form-control" id="nama_perkiraan">
		            </div>
	            </div>

                <div class="form-group row">
		            <label class="col-md-3">Rekening</label>
			            <div class="col-md-7">
                            <select name="id_perkiraan" id="id_perkiraan" class="form-control" required>
                                <option value="">Pilih Perkiraan</option>
                                <?php $perkiraan = App\Models\Perkiraan::pluck('nama', 'id'); ?>
                                @foreach ($perkiraan as $id => $p)
                                <option value="{{ $id }}">{{ $p }}</option>
                                @endforeach
			                </select>
		                </div>
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

    $("#id_perkiraan").select2({
        width : '100%'
    });

    $('.edit').on("click",function() {
        var id = $(this).attr('data-id');
        //$("#get_id_bank").val(id)
        $.ajax({
            url : "{{route('edit-setting-coa-kas-bank')}}?id="+id,
            type: "GET",
            dataType: "JSON",
            success: function(data){
                $('#id').val(data.id);
                $('#nama_bank').val(data.nama);
                $('#nama_perkiraan').val(data.perkiraan);
                $('#id_perkiraan').val(data.id_perkiraan).change();
                $('#modal-edit').modal('show');
            }
        });
    });
});
</script>
@endpush
