@extends('layouts.app')

@section('content')

<div class="page-header">
    <h1 class="page-title">Detail Setting Rekening Profit & Loss</h1>
    <div align="right">
    <button type="button" class="btn btn-primary btn-sm" data-id="{{$id}}" id="tambah" align="right">Tambah</button>
</div>
</div>


@include('layouts.inc.breadcrumb')


<div class="page-content">
    <div class="panel">
        <div class="panel-body">

            <div class="form-group row">
			    <div class="col-md-4">
			    <input type="text" class="form-control" value="Variable Profit & Loss : {{ optional($rek)->nama }}" readonly>
		        </div>
	        </div>

            <form action="{{ url('/cari-detail-setting-rekening-pl')}}" method="post">{{ @csrf_field() }}

            <div class="form-group" align="right">
                <label class="col-md-3">Pencarian</label>
                <input type="text" class="form-controller" id="search" name="search">
                <input type="hidden" name="id" value="{{ $id }}">
            </div>

            <div class="text-right">
			    <button class="btn btn-primary" type="submit">Cari</button>
			</div>
            </form>


            <table class="table table-hover dataTable table-striped w-full" id="surplus-defisit-rek-table">
                <tr>

                    <th>No</th>
                    <th>Rekening</th>
                    <th>Kode Rekening</th>
                    <th>Aksi</th>
                </tr>

                @foreach ($data as $key =>$d)
                <tr>
                    <td>{{ $key + $data->firstItem() }}</td>
                    <td>{{ $d->nama }}</td>
                    <td>{{ $d->kode_rekening }}</td>
                    <td> <button type="button" class="btn btn-sm btn-icon btn-danger hapus" data-id="{{$d->id}}">Hapus</button></td>
                </tr>
                @endforeach

	        </table>
            {{ $data->appends(request()->toArray())->links() }}
        </div>
    </div>
</div>

<div class="modal inmodal fade" id="modal-create" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-xs">
        <form name="frm_edit" id="frm_edit" class="form-horizontal" action="{{route('surplus-defisit-rek.store')}}" method="POST">@csrf
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Tambah Setting Rekening Profit & Loss</h4>
                </div>

                    <div class="form-group row">
		                <label class="col-md-3">Surplus Defisit</label>
			                <div class="col-md-7">
                            <input type="text" readonly class="form-control" id="nama">
		                </div>
	                </div>

                    <div class="form-group row">
		                <label class="col-md-3">Rekening</label>
			                <div class="col-md-7">
                                <select name="id_perkiraan" id="id_perkiraan" class="form-control" required>
                                <option value="">Pilih Perkiraan</option>
                                <?php $perkiraan = App\Models\Perkiraan::get(['id', 'nama']); ?>
                                @foreach ($perkiraan as $Perkiraan)
                                <option value="{{ $Perkiraan->id }}">{{ $Perkiraan->nama }}</option>
                                @endforeach
			                </select>
		                </div>
	                </div>


                    <div class="modal-footer">
                        <input type="hidden" name="id_surplus_defisit_detail" id="id">
                        <button type="button" class="btn btn-white" data-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-danger">Simpan</button>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>

<div class="modal inmodal fade" id="modal-edit" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-xs">
        <form name="frm_edit" id="frm_edit" class="form-horizontal" action="{{route('remove-surplus-defisit-rek')}}" method="POST">@csrf
            <div class="modal-content">
                <div class="modal-header">
                <h4 class="modal-title">Apakah Anda yakin akan hapus data ini ?
            </div>

                <div class="modal-footer">
                    <input type="hidden" name="id" id="id">
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

$("#id_perkiraan").select2({
        width : '100%'
    });

$('.hapus').on("click",function() {
        var id = $(this).attr('data-id');
        //$("#get_id_bank").val(id)
        $.ajax({
            url : "{{route('delete-surplus-defisit-rek')}}?id="+id,
            type: "GET",
            dataType: "JSON",
            success: function(data){
                $('#id').val(data.id);
                $('#modal-edit').modal('show');
            }
        });
    });

    $('#tambah').on("click",function() {
        var id = $(this).attr('data-id');
        //$("#get_id_bank").val(id)
        $.ajax({
            url : "{{route('tambah-surplus-defisit-rek')}}?id="+id,
            type: "GET",
            dataType: "JSON",
            success: function(data){
                $('#id').val(data.id);
                $('#nama').val(data.nama)
                $('#modal-create').modal('show');
            }
        });
    });
</script>
@endpush



