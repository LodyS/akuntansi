@extends('layouts.app')

@section('content')

<div class="page-header">
    <h1 class="page-title">Mutasi Pengeluaran Kas</h1>
    	@include('layouts.inc.breadcrumb')
		<div class="page-header-actions">
        <a class="btn btn-block btn-primary btn-round" href="form">
        <i class="icon glyphicon glyphicon-pencil" aria-hidden="true"></i>&nbsp;Tambah</a>
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
           <form action="{{ url('mutasi-pengeluaran-kas/pencarian')  }}" method="post" >{{ @csrf_field() }}
                <div class="form-group row">
                    <label class="col-md-3">Tanggal Awal :</label>
                      	<div class="col-md-7">
                      	<input type="date" name="tanggal_awal" value="{{ $startDate }}" class="form-control">
                  	</div>
                </div>

                <div class="form-group row">
                  	<label class="col-md-3">Tanggal Akhir :</label>
                    	<div class="col-md-7">
                    	<input type="date" name="tanggal_akhir" value="{{ $endDate }}" class="form-control">
                  	</div>
                </div>

                <div class="form-group row">
			        <label class="col-md-3">Bank</label>
				        <div class="col-md-7">
				            <select name="id_bank" id="id_bank" class="form-control">
                      		<option value="">Pilih Bank</option>
                      		@foreach ($bank as $bang)
                      		<option value="{{ $bang->id }}">{{ $bang->nama }}</option>
                      		@endforeach
                    	</select>
			        </div>
		        </div>

                {{-- <div class="form-group row">
			        <label class="col-md-3">COA</label>
				        <div class="col-md-7">
				            <select name="id_perkiraan" class="form-control">
                      		<option value="">Pilih Perkiraan</option>
                      		@foreach ($perkiraan as $kira)
                      		<option value="{{ $kira->id }}">{{ $kira->nama }}</option>
                      		@endforeach
                    	</select>
			        </div>
		        </div> --}}

                <div class="form-group row">
			        <label class="col-md-3">Status Verifikasi</label>
				        <div class="col-md-7">
				            <select name="flag_bayar" id="flag_bayar" class="form-control">
                      		<option value="">Pilih Status</option>
                      		<option value="Y">Sudah diverifikasi</option>
                            <option value="N">Belum diverifikasi</option>
                    	</select>
			        </div>
		        </div>

                <button type="submit" align="right" class="btn btn-dark btn-round">
                    <i class="icon glyphicon glyphicon-search" aria-hidden="true"></i>Cari</button>
            </form>
        <br/><br/>

        			<table class="table table-hover dataTable table-striped w-full" id="mutasi-kas-table">
                			<tr>
                    			<th>No</th>
                    			<th>Kode</th>
                    			<th>Tanggal</th>
                    			{{-- <th>Pembayaran</th> --}}
                    			<th>Dibayarkan Kepada</th>
                    			<th>Pengeluaran</th>
                    			<th>Nominal</th>
                    			<th>Keterangan</th>
                                <th>Status</th>
                                <th>Status/Flag Bayar</th>
                    			{{-- <th>Detail</th> --}}
                                <th>Verifikasi</th>
                                <th>Bukti Transaksi</th>
                                <th>Jurnal</th>
                			</tr>

                            @foreach ($data as $key =>$rekap)
                            <tr>
                                <td>{{ $key + $data->firstItem() }}</td>
                                <td class="text-nowrap">{{ $rekap->kode }}</td>
                                <td class="text-nowrap">{{ date('d-m-Y', strtotime($rekap->tanggal)) }}</td>
                                <td>{{ $rekap->penerima }}</td>
                                {{-- <td>{{ $rekap->perkiraan }}</td> --}}
                                <td>{{ $rekap->kas_bank }}</td>
                                <td>Rp. {{ number_format($rekap->nominal,2, ",", ".") }}</td>
                                <td>{{ $rekap->keterangan }}</td>
                                <td>{{ $rekap->status }}</td>
                                <td>{{ ($rekap->flag_bayar == 'Y') ? 'Sudah Dibayar' : 'Belum Dibayar' }}</td>
                                {{-- <td>
                                    <a href='detail/{{ $rekap->id }}' class='btn btn-primary btn-sm btn-round' data-toggle="tooltip" data-original-title="Detail">
                                        <i class="icon glyphicon glyphicon-info-sign" aria-hidden="true"></i></a>
                                </td> --}}
                                <td>
                                    @if($rekap->flag_bayar != 'Y')
                                        <button type="button" class="btn btn-sm btn-icon btn-danger btn-round edit" data-id="{{ $rekap->id }}" data-original-title="Verifikasi" data-toggle="tooltip">
                                            <i class="icon md-check" aria-hidden="true"></i></button>
                                    @endif
                                </td>
                                <td>
                                    <a class="btn btn-sm btn-info btn-round" href="bukti-transaksi-kas-keluar/{{ $rekap->id }}" data-toggle="tooltip" data-original-title="Bukti Transaksi">
                                        <i class="icon glyphicon glyphicon-list-alt" aria-hidden="true"></i></a>
                                </td>
                                <td class="text-center"> <a class="btn btn-sm btn-info btn-round" href="lihat-jurnal-kas-keluar/{{ $rekap->id }}"
                                    data-toggle="tooltip" data-original-title="Lihat Jurnal">
                                    <i class="icon glyphicon glyphicon-book" aria-hidden="true"></i></a>
                                </td>
                            </tr>
                            @endforeach
        			</table>
                    {{ $data->appends(request()->toArray())->links() }}
        		</div>
     		</div>
 		</div>

<div class="modal inmodal fade" id="modal-edit" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-xs">
        <form name="frm_edit" id="frm_edit" class="form-horizontal" action="{{route('verifikasi-mutasi-pengeluaran-kas')}}" method="POST">@csrf
            <div class="modal-content">
                <div class="modal-header">
                <h4 class="modal-title">Verifikasi Mutasi Pengeluaran Kas : <br/>
                Keterangan : <input type="text" readonly class="form-control-plaintext sm-3" id="kode">
            </div>

                <div class="modal-footer">
                    <input type="hidden" name="id" id="id">
                    <button type="button" class="btn btn-white" data-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-danger">Verifikasi</button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection

@push('js')
<script type="text/javascript">
$('.edit').on("click",function() {
        var id = $(this).attr('data-id');
        //$("#get_id_bank").val(id)
        $.ajax({
            url : "{{route('verif-mutasi-pengeluaran-kas')}}?id="+id,
            type: "GET",
            dataType: "JSON",
            success: function(data){
                $('#id').val(data.id);
                $('#kode').val(data.kode).change();
                $('#modal-edit').modal('show');
            }
        });
    });

    $(document).ready(function () {
        $('#id_bank').val(`{{ $id_bank ?? '' }}`);
        $('#flag_bayar').val(`{{ $flag_bayar ?? '' }}`);
    });

</script>
@endpush

