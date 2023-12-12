@extends('layouts.app')

@section('content')

<div class="page-header">
    <h1 class="page-title">Pencarian </h1>
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
                <th>Keterangan</th>
                <th>Perkiraan</th>  
                <th>Total</th> 
                <th>Nama Tarif</th>    
                <th>Kelas</th>  
                <th>Tipe Pasien</th>
                <th>Aksi</th>
            </tr>

                <tr>
                    @foreach ($data as $key => $cari)
                    <td>{{ $key + $data->firstItem() }}</td>
                    <td>{{ $cari->keterangan }}</td>
                    <td>{{ $cari->perkiraan }}</td>
                    <td>Rp. {{ number_format($cari->total) }}</td>
                    <td>{{ $cari->tarif }}</td>
                    <td>{{ $cari->kelas }}</td>
                    <td>{{ $cari->tipe_pasien }}</td>
                    <td><button type="button" class="btn btn-xs btn-success edit" data-id="{{$cari->id}}">Edit</button></td>
                </tr> 
                   
                @endforeach
                </thead>
            </table>
            {{ $data->appends(request()->toArray())->links() }}
            </div>
        </div>
    </form>
</div>

<div class="modal inmodal fade" id="modal-edit" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-xs">
        <form name="frm_edit" id="frm_edit" class="form-horizontal" action="{{route('update-setting-coa-tarif')}}" method="POST">@csrf
            <div class="modal-content">
                <div class="modal-header">
                <h4 class="modal-title">Edit Setting COA Tarif : <input readonly class="form-control-plaintext" id="nama_layanan"><br/> 
                Perkiraan : <input readonly class="form-control-plaintext sm-3" id="nama_perkiraan"><br/>
                Kelas : <input readonly class="form-control-plaintext sm-3" id="kelas"><br/>
                Total : Rp. <input readonly class="form-control-plaintext sm-3" id="total"><br/>
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

    function formatRupiah(number) {
        return number.toString().replace(/(?!\,)\D/g, "").replace(/(?<=\,,*)\./g, "").replace(/(?<=\.\d\d).*/g, "").replace(/\B(?=(\d{3})+(?!\d))/g, ".");
    }

    $('.edit').on("click",function() {
        var id = $(this).attr('data-id');
  
        $.ajax({
            url : "{{route('edit-setting-coa-tarif')}}?id="+id,
            type: "GET",
            dataType: "JSON",
            success: function(data){
                $('#id').val(data.id);
                $('#nama_layanan').val(data.layanan);
                $('#kelas').val(data.kelas);
                $('#total').val(formatRupiah(data.total));
                $('#nama_perkiraan').val(data.perkiraan);
                $('#modal-edit').modal('show');
            }
        });
    });
});
</script>
@endpush
