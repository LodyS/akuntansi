@extends('layouts.app')

@section('content')

<div class="page-header">
    <h1 class="page-title">Setting COA Pembayaran Oleh Pasien</h1>
        <div class="page-header-actions">
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
            <table class="table table-hover">
                <tr>
                    <th>No</th>
                    <th>Nama</th>
                    <th>Perkiraan</th>   
                    <th>Aksi</th> 
                </tr>

                @php ($i=1)
                <tr>
                    @foreach ($setting_coa as $setting)
                        <td>{{ $i }}</td>
                        <td>{{ $setting->nama }}</td>
                        <td>{{ $setting->perkiraan }}</td>
                        <td><button type="button" class="btn btn-xs btn-success edit" data-id="{{$setting->id}}">Edit</button></td>
                    </tr> 
                    @php($i++)
                @endforeach
                </table>          
            </div>
        </div>
    </form>
</div>

<div class="modal inmodal fade" id="modal-edit" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-xs">
        <form name="frm_edit" id="frm_edit" class="form-horizontal" action="{{route('update-setting-coa-pembayaran-oleh-pasien')}}" method="POST">@csrf
            <div class="modal-content">
                <div class="modal-header">
                <h4 class="modal-title">Edit Setting COA Pembayaran Oleh Pasien : <input readonly class="form-control-plaintext" id="nama"><br/> 
                Perkiraan : <input readonly class="form-control-plaintext sm-3" id="nama_perkiraan">
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
            url : "{{route('edit-setting-coa-pembayaran-oleh-pasien')}}?id="+id,
            type: "GET",
            dataType: "JSON",
            success: function(data){
                $('#id').val(data.id);
                $('#nama').val(data.nama);
                $('#nama_perkiraan').val(data.perkiraan);
                $('#modal-edit').modal('show');
            }
        });
    });
});
</script>
@endpush
