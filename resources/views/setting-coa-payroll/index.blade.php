@extends('layouts.app')

@section('content')

<div class="page-header">
    <h1 class="page-title">Setting COA Payroll</h1>
    <div class="page-header-actions">
        <a class="btn btn-block btn-primary btn-round" href="pajak-dan-biaya-adm">
        <i class="icon glyphicon glyphicon-info-sign" aria-hidden="true"></i>&nbsp;Pajak dan Biaya Adm</a>
    </div>
</div>

@include('layouts.inc.breadcrumb')
<div class="page-content">
    <div class="panel">
        <div class="panel-body">
            <form action="{{ url('setting-coa-payroll/pencarian')}}" method="post">{{ @csrf_field() }}

                <div class="form-group row">
		            <label class="col-md-3">Komponen</label>
			            <div class="col-md-7">
			            <select name="komponen" id="komponen" class="form-control" required>
				        <option value="">Pilih Komponen</option>
                        @foreach ($komponen as $kom)
				        <option value="{{ $kom->komponen }}">{{ $kom->komponen }}</option>
                        @endforeach
                        </select>
		            </div>
	            </div>

                <div class="form-group row">
		            <label class="col-md-3">COA</label>
			            <div class="col-md-7">
			            <select name="id_perkiraan"  class="form-control" required>
				        <option value="">Pilih COA</option>
                        @foreach ($perkiraan as $coa)
				        <option value="{{ $coa->id }}">{{ $coa->nama }}</option>
                        @endforeach
                        </select>
		            </div>
	            </div>

                <div class="form-group row">
		            <label class="col-md-3">Status</label>
			            <div class="col-md-7">
			                <select name="status" class="form-control" required>
				            <option value="">Pilih Status</option>
                            <option value="X">Semua</option>
				            <option value="Y">Aktif</option>
                            <option value="N">Non Aktif</option>
                        </select>
		            </div>
	            </div>

                <button type="submit" align="right" class="btn btn-primary btn-round"><i class="icon glyphicon glyphicon-search"
                aria-hidden="true"></i>Cari</button>
            </form>

            <table class="table table-hover dataTable table-striped w-full" id="setting">
                <tr>
                    <th>No</th>
                    <th>Komponen</th>
                    <th>Perkiraan</th>
                    <th>Status Aktif</th>
                    <th>Aksi</th>
                </tr>
                    @php ($i=1)
                    @foreach($setting as $key=>$coa)
                    <tr>
                        <td>{{ $i }}</td>
                        <td> {{ $coa->komponen }} </td>
                        <td> {{ $coa->rekening}} </td>
                        <td> @if($coa->flag_aktif == 'Y')
                            <button type="button" class='btn btn-primary btn-xs btn-round'
                                data-toggle="tooltip" data-original-title="Aktif">
                                <i class="icon glyphicon glyphicon-check" aria-hidden="true"></i>Aktif</button>
                            @else
                            <button type="button" class='btn btn-danger btn-xs btn-round'
                                data-toggle="tooltip" data-original-title="Tidak Aktif">
                                <i class="icon glyphicon glyphicon-unchecked" aria-hidden="true"></i>Tidak Aktif</button>
                            @endif</td>
                        <td><button type="button" class="btn btn-xs btn-danger btn-round edit" data-id="{{$coa->id}}"
                            data-original-title="Edit" data-toggle="tooltip">
                        <i class="icon glyphicon glyphicon-pencil" aria-hidden="true"></i></button></td>
                     </tr>
                    @php($i++)
                    @endforeach
                </table>
            </div>
        </div>
    </div>
</div>
@endsection

<div class="modal inmodal fade" id="modal-edit" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-xs">
        <form name="frm_edit" id="frm_edit" class="form-horizontal" action="{{route('update-setting-coa-payroll')}}" method="POST">@csrf
            <div class="modal-content">
                <div class="modal-header">
                <h4 class="modal-title">Edit Setting COA Payroll <br/>
            </div>

            <div class="modal-body">

                <div class="form-group row">
		            <label class="col-md-3">Komponen</label>
			            <div class="col-md-7">
                        <input type="text" id="komponen" class="form-control round" readonly>
		            </div>
	            </div>

                <div class="form-group row">
		            <label class="col-md-3">Perkiraan</label>
			            <div class="col-md-7">
                            <select name="id_perkiraan" id="perkiraan" class="form-control" required>
                                <option value="">Pilih Perkiraan</option>
                                <?php $perkiraan = App\Models\Perkiraan::get(['id', 'nama']); ?>
                                @foreach ($perkiraan as $Perkiraan)
                                <option value="{{ $Perkiraan->id }}">{{ $Perkiraan->nama }}</option>
                                @endforeach
			                </select>
		                </div>
	                </div>

                    <div class="form-group row">
		                <label class="col-md-3">Status</label>
			                <div class="col-md-7">
                                <select name="flag_aktif" id="flag_aktif" class="form-control" required>
                                <option value="">Pilih Status</option>
                                <option value="Y">Aktif</option>
                                <option value="N">Non Aktif</option>
			                </select>
		                </div>
	                </div>
                </div>

                <div class="modal-footer">
                    <input type="hidden" name="id" id="id">
                    <button type="button" class="btn btn-danger btn-round" data-dismiss="modal">
                    <i class="icon glyphicon glyphicon-remove" aria-hidden="true"></i>Tutup</button>
                    <button type="submit" class="btn btn-primary btn-round">
                    <i class="icon glyphicon glyphicon-save" aria-hidden="true"></i>Simpan</button>
                </div>
            </div>
        </form>
    </div>
</div>

@push('js')
<script type="text/javascript">
$("#id_perkiraan").select2({
    width : '100%'
});

$('.edit').on("click",function() {
    var id = $(this).attr('data-id');

    $.ajax({
        url : "{{route('edit-setting-coa-payroll')}}?id="+id,
        type: "GET",
        dataType: "JSON",
        success: function(data){
            $('#id').val(data.id);
            $('#komponen').val(data.komponen).change();
            $('#perkiraan').val(data.id_perkiraan).change();
            $('#flag_aktif').val(data.flag_aktif).change();
            $('#modal-edit').modal('show');
        }
    });
});
</script>
@endpush
