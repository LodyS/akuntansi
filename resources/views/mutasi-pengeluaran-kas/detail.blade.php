@extends('layouts.app')

@section('content')

<div class="page-header">
    <h1 class="page-title">Mutasi Pengeluaran Kas</h1>
</div>
@include('layouts.inc.breadcrumb')
<div class="page-content">
    <div class="panel">
        <div class="panel-body">
		@include('flash-message')
            <h3 align="center"></h3><br/>

                    <div class="form-group row">
		                <label class="col-md-3">Kode</label>
			                <div class="col-md-7">
			                <input type="text" class="form-control" value="{{ isset($mutasiKas) ? $mutasiKas->kode : ''}}" readonly>
		                </div>
	                </div>

                    <div class="form-group row">
			            <label class="col-md-3">Jenis Penerimaan Kas</label>
				            <div class="col-md-7">
                            <input type="text" class="form-control" value="{{ isset($mutasiKas) ? $mutasiKas->induk : ''}}" readonly>
			            </div>
		            </div>

		            <div class="form-group row">
			            <label class="col-md-3">Sub Jenis Penerimaan Kas</label>
				            <div class="col-md-7">
                            <input type="text" class="form-control" readonly value="{{isset($mutasiKas) ? $mutasiKas->arus_kas : ''}}">
			            </div>
		            </div>

                    <div class="form-group row">
			            <label class="col-md-3">Tanggal</label>
				            <div class="col-md-7">
				            <input type="date" value="{{ isset($mutasiKas) ? $mutasiKas->tanggal : '' }}" class="form-control" readonly>
			            </div>
		            </div>

                    <div class="form-group row">
                        <label class="col-md-3">Keterangan</label>
                            <div class="col-md-7">
                            <input type="text" class="form-control" value="{{ isset($mutasiKas) ? $mutasiKas->keterangan : '' }}" readonly>
                        </div>
                    </div>

                    <div class="form-group row">
			            <label class="col-md-3">Penerimaan</label>
				            <div class="col-md-7">
                            <input type="text" class="form-control" readonly value="{{isset($mutasiKas) ? $mutasiKas->bank : ''}}">
			            </div>
		            </div>

                    <div class="form-group row">
                        <label class="col-md-3">Total Nominal</label>
                            <div class="col-md-7">
                            <input type="text" class="form-control" readonly value="Rp. {{ isset($mutasiKas) ? number_format($mutasiKas->nominal) : '0'}}">
                        </div>
                    </div>

                    <h3>Detail Mutasi Penerimaan Kas</h3><br/>
                    <table class="table table-hover">
                        <tr>
                            <th>No</th>
                            <th>Pajak</th>
                            <th>Rekening</th>
                            <th>Keterangan</th>
                            <th>Cost Centre</th>
                            <th>Nominal</th>
                            <th>Aksi</th>
                        </tr>

                    @foreach ($mutasiKasDetail as $key=>$data)
                        <tr>
                            <td>{{ $key + $mutasiKasDetail->firstItem() }}</td>
                            <td>{{ $data->nama_pajak }}</td>
                            <td>{{ $data->kode_rekening }} - {{ $data->rekening }}</td>
                            <td>{{ $data->keterangan }}</td>
                            <td>{{ $data->cost_centre }}</td>
                            <td>Rp. {{ number_format($data->nominal) }}</td>
                            <td><button type="button" class="btn btn-sm btn-icon btn-primary btn-round edit"  data-id="{{$data->id}}"
                            data-original-title="Edit" data-toggle="tooltip">
                            <i class="icon md-edit" aria-hidden="true"></i></button>
                            <button type="button" class="btn btn-sm btn-icon btn-danger btn-round delete"" data-id="{{$data->id}}"
                            data-original-title="Hapus" data-toggle="tooltip">
                            <i class="icon glyphicon glyphicon-trash" aria-hidden="true"></i></button>
                            </td>
                        </tr>
                    @endforeach
                </table>
                {{ $mutasiKasDetail->appends(request()->toArray())->links() }}
            </form>
        </div>
    </div>
</div>

<div class="modal inmodal fade" id="modal-edit" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-xs">
        <form name="frm_edit" id="frm_edit" class="form-horizontal" action="{{route('update-mutasi-pengeluaran-kas')}}" method="POST">@csrf
            <div class="modal-content">
                <div class="modal-header">
                <h4 class="modal-title">Edit Mutasi Pengeluaran Kas : <br/>
                Keterangan : <input type="text" readonly class="form-control-plaintext sm-3" id="keterangan">
            </div>

            <div class="modal-body">
                <div class="form-group row">
		            <label class="col-md-3">Rekening</label>
			            <div class="col-md-7">
                            <select name="id_perkiraan" id="id_perkiraan" class="form-control" required>
                                <option value="">Pilih Rekening</option>
                                <?php $perkiraan = App\Models\Perkiraan::select('id', 'nama', 'kode_rekening')->get(); ?>
                                @foreach ($perkiraan as $Perkiraan)
                                <option value="{{ $Perkiraan->id }}">{{ $Perkiraan->kode_rekening }} - {{ $Perkiraan->nama }}</option>
                                @endforeach
			                </select>
		                </div>
	                </div>

                    <div class="form-group row">
		            <label class="col-md-3">Perkiraan</label>
			            <div class="col-md-7">
                            <select name="id_unit" id="id_unit" class="form-control" required>
                                <option value="">Pilih Cost Centre</option>
                                <?php $unit = App\Models\Unit::select('id', 'nama', 'code_cost_centre')->get(); ?>
                                @foreach ($unit as $u)
                                <option value="{{ $u->id }}">{{ $u->code_cost_centre }} - {{ $u->nama }}</option>
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
        <form name="frm_delete" id="frm_delete" class="form-horizontal" action="{{route('remove-mutasi-pengeluaran-kas')}}" method="POST">@csrf
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

    $("#id_perkiraan").select2({
        width : '100%'
    });

    $("#id_unit").select2({
        width : '100%'
    });

    $('.edit').on("click",function() {
        var id = $(this).attr('data-id');
        //$("#get_id_bank").val(id)
        $.ajax({
            url : "{{route('edit-mutasi-pengeluaran-kas')}}?id="+id,
            type: "GET",
            dataType: "JSON",
            success: function(data){
                $('#id').val(data.id);
                $('#id_unit').val(data.id_unit).change();
                $('#id_perkiraan').val(data.id_perkiraan).change();
                $('#keterangan').val(data.keterangan);
                $('#modal-edit').modal('show');
            }
        });
    });

    $('.delete').on("click",function() {
        var id = $(this).attr('data-id');
        $.ajax({
            url : "{{route('delete-mutasi-pengeluaran-kas')}}?id="+id,
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


