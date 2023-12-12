@extends('layouts.app')

@section('content')
<div class="page-header">
    <h1 class="page-title">Deposit</h1>
    @include('layouts.inc.breadcrumb')
    <div class="page-header-actions">

    </div>
</div>


<div class="page-content">
    <!-- Panel Table Tools -->
    <div class="panel">
      <header class="panel-heading">
        <div class="form-group col-md-12">
          <div class="form-group">
        <!-- <h3 class="panel-title">Table Tools</h3> -->

          </div>
        </div>
      </header>
      <div class="panel-body">
            <form action="{{ url('update-deposit') }}" method="post" id="create">{{ @csrf_field() }}

                <input type="hidden" name="id" value="{{ $deposit->id}}">
                    <div class="form-group row">
                        <label class="col-md-3">No Pelanggan</label>
                        <div class="col-md-7">

                            <input type="number" name="id_pelanggan" id="id_pelanggan"
                            value="{{ $deposit->id_pelanggan }}" class="form-control" readonly>
                    </div>
                </div>

                <div class="form-group row">
                    <label class="col-md-3">No Kunjungan</label>
                    <div class="col-md-7">
                        <input type="number" name="id_visit" id="id_visit" value="{{ $deposit->id_visit }}" class="form-control" readonly>
                    </div>
                </div>

                <div class="form-group row">
                    <label class="col-md-3">Nama Pasien</label>
                    <div class="col-md-7">
                        <input type="text" value="{{ $deposit->nama_pasien }}" class="form-control" readonly>
                    </div>
                </div>

                <div class="form-group row">
                    <label class="col-md-3">Waktu</label>
                    <div class="col-md-7">
                        <input type="date" name="waktu" value="{{ $deposit->waktu }}" class="form-control" readonly>
                    </div>
                </div>

                <div class="form-group row">
                    <label class="col-md-3">Jumlah Deposit</label>
                    <div class="col-md-7">
                        <input type="text" name="kredit" id="kredit" value="{{ number_format($deposit->kredit,2,",",".") }}" class="form-control">
                    </div>
                </div>

                <div class="form-group row">
                    <div class="col-md-3"></div>
                    <div class="col-md-7">
                        <button class="btn btn-primary" id="simpan">Simpan</button>
                    </div>
                </div>

            </form>
      </div>
    </div>
    <!-- End Panel Table Tools -->
</div>

@endsection

@push('js')

<script type="text/javascript">

$('#kredit').on('change click keyup input paste',(function (event) {
    $(this).val(function (index, value) {
        return value.replace(/(?!\,)\D/g, "").replace(/(?<=\,,*)\,/g, "").replace(/(?<=\.\d\d).*/g, "").replace(/\B(?=(\d{3})+(?!\d))/g, ".");
    });
}));

</script>
@endpush
