@extends('layouts.app')

@section('content')

<div class="page-header">
    <h1 class="page-title">Setting COA Payroll (Pajak dan Biaya ADM)</h1>
    	@include('layouts.inc.breadcrumb')
		<div class="page-header-actions">

    </div>
</div>

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
       	@include('flash-message')
            <table class="table table-hover dataTable table-striped w-full" id="mutasi-kas-table">
                <tr>
                    <th>No</th>
                    <th>Komponen</th>
                    <th>Perkiraan</th>
                    <th>Aksi</th>
                </tr>

                @foreach ($settingCoa as $key =>$rekap)
                <tr>
                    <td>{{ $key + $settingCoa->firstItem() }}</td>
                    <td>{{ $rekap->nama }}</td>
                    <td>{{ $rekap->kode_rekening }} - {{ $rekap->perkiraan }}</td>
                    <td><button type="button" class="btn btn-xs btn-danger btn-round edit" data-id="{{$rekap->id}}"
                        data-original-title="Edit" data-toggle="tooltip">
                        <i class="icon glyphicon glyphicon-pencil" aria-hidden="true"></i></button></td>
                </tr>
                @endforeach
        	</table>
            {{ $settingCoa->appends(request()->toArray())->links() }}
        </div>
    </div>
</div>

<div class="modal inmodal fade" id="modal-edit" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-xs">
        <form name="frm_edit" id="frm_edit" class="form-horizontal" action="{{route('update-setting-coa-payroll-dua')}}" method="POST">@csrf
            <div class="modal-content">
                <div class="modal-header">
                <h4 class="modal-title">Edit
                Komponen : <input type="text" readonly class="form-control-plaintext sm-3" id="komponen">
            </div>

            <div class="modal-body">
                <div class="form-group row">
		            <label class="col-md-3">Perkiraan</label>
			            <div class="col-md-7">
                            <select name="id_perkiraan" id="id_perkiraan" class="form-control" required>
                                <option value="">Pilih Perkiraan</option>
                                <?php $perkiraan = App\Models\Perkiraan::select('id', 'nama')->get(); ?>
                                @foreach ($perkiraan as $Perkiraan)
                                <option value="{{ $Perkiraan->id }}">{{ $Perkiraan->nama }}</option>
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

        $.ajax({
            url : "{{route('edit-setting-coa-payroll-dua')}}?id="+id,
            type: "GET",
            dataType: "JSON",
            success: function(data){
                $('#id').val(data.id);
                $('#komponen').val(data.nama);
                $('#id_perkiraan').val(data.id_perkiraan).change();
                $('#modal-edit').modal('show');
            }
        });
    });
});
</script>
@endpush
