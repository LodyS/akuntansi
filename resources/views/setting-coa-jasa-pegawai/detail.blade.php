@extends('layouts.app')

@section('content')
<div class="page-header">
    <h1 class="page-title">Setting COA Jasa Pegawai</h1>
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

            <div class="form-group row">
				<div class="col-md-4">
				<input type="text" value="Unit : {{ optional($unit)->nama }}" class="form-control" readonly>
			    </div>
		    </div>

            <table class="table table-hover table-striped w-full" id="setting">
                <tr>
                    <th>No</th>
                    <th>Komponen</th>
                    <th>Rekening</th>
                    <th>Aksi</th>
                </tr>

                    @foreach($settingCoaJasaPegawai as $key => $coa)
                    <tr>
                        <td>{{ $key + $settingCoaJasaPegawai->firstItem() }}</td>
                        <td> {{$coa->komponen }} </td>
                        <td> {{$coa->rekening}} </td>
                        <td><button type="button" class="btn btn-xs btn-success edit" data-id="{{$coa->id}}">Edit</button>
                        <button type="button" class="btn btn-xs btn-danger delete" data-id="{{$coa->id}}">Hapus</button></td>
                    </tr>
                    @endforeach
                </table>
            {{ $settingCoaJasaPegawai->links() }}
        </div>
    </div>
</div>

<div class="modal inmodal fade" id="modal-edit" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-xs">
        <form name="frm_edit" id="frm_edit" class="form-horizontal" action="{{route('update-setting-coa-jasa-pegawai')}}" method="POST">@csrf
            <div class="modal-content">
                <div class="modal-header">
                <h4 class="modal-title">Edit Setting COA Jasa Pegawai<br>
                Unit : <input type="text" readonly class="form-control-plaintext" id="nama"><br/>
                Perkiraan : <input type="text" readonly class="form-control-plaintext sm-3" id="rekening">
            </div>

            <div class="modal-body">
                <div class="form-group row">
		            <label class="col-md-3">Perkiraan</label>
			            <div class="col-md-7">
                            <select name="id_perkiraan" id="id_perkiraan" class="form-control select" required>
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

<div class="modal inmodal fade" id="modal-delete" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-xs">
        <form name="frm_delete" id="frm_delete" class="form-horizontal" action="{{route('remove-setting-coa-jasa-pegawai')}}" method="POST">@csrf
            <div class="modal-content">
                <div class="modal-header">
                <h4 class="modal-title">Apakah Anda yakin untuk menghapus perkiraan pada<br>
                Unit : <input type="text" readonly class="form-control-plaintext" id="nama_unit"><br/>
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

    $('.edit').on("click",function() {
        var id = $(this).attr('data-id');
        $.ajax({
            url : "{{route('edit-setting-coa-jasa-pegawai')}}?id="+id,
            type: "GET",
            dataType: "JSON",
            success: function(data){
                $('#id').val(data.id);
                $('#nama').val(data.nama);
                $('#rekening').val(data.rekening);
                $('#modal-edit').modal('show');
            }
        });
    });

    $('.delete').on("click",function() {
        var id = $(this).attr('data-id');
        $.ajax({
            url : "{{route('delete-setting-coa-jasa-pegawai')}}?id="+id,
            type: "GET",
            dataType: "JSON",
            success: function(data){
                $('#id_setting').val(data.id);
                $('#nama_unit').val(data.nama);
                $('#modal-delete').modal('show');
            }
        });
    });
});
</script>
@endpush
