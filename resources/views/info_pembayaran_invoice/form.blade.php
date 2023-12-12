<div class="modal-dialog modal-simple">

    <form action="{{ route('info-pembayaran-invoice.store') }}" method="post" class="modal-content">

        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">×</span>
            </button>
            <h4 class="modal-title" id="formModalLabel">Tambah Informasi Pembayaran</h4>
        </div>
        <div class="modal-body">
            <div class="form-group row">
                {{ @csrf_field() }}
                <input name="_method" type="hidden" value="POST">
                <input type="hidden" name="user_input" value="{{ Auth::user()->id }}">
                <label for="nama" class="col-md-12 control-label">Bank</label>
                <div class="col-md-12">
                    <select name="id_bank" id="id_bank" class="form-control">
                        @foreach ($bank as $bnk)
                            <option value="{{ $bnk->id }}">{{ $bnk->nama }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="col-md-12 float-right">
                <div class="text-right">
                    <button class="btn btn-primary" id="simpan">Simpan</button>
                </div>
            </div>
        </div>

    </form>
</div>


<script type="text/javascript">
    $(document).ready(function () {


    });
</script>
