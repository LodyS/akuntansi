<div class="modal-dialog modal-simple">

		{{ Form::model($MutasiKas,array('route' => array((!$MutasiKas->exists) ? 'jurnal-penerimaan-kas.store':'jurnal-penerimaan-kas.update',$MutasiKas->pk()),
	        'class'=>'modal-content','id'=>'mutasi-kas-form','method'=>(!$MutasiKas->exists) ? 'POST' : 'PUT')) }}

		<div class="modal-header">
      <button type="button" class="close" data-dismiss="modal" aria-label="Close">
        <span aria-hidden="true">×</span>
      </button>
      <h4 class="modal-title" id="formModalLabel">{{ ($MutasiKas->exists?'Edit':'Tambah').' Penerimaan Kas' }}</h4>
    </div>
    <div class="modal-body">
	<input type="hidden" name="user_input" value="{{ Auth::user()->id }}">
	<input type="hidden" name="user_update" value="{{ Auth::user()->id }}">
	<input type="hidden" name="ref" value="N">
	<input type="hidden" name="no_jurnal" value="0">
	<input type="hidden" name="tipe" value="2">


	<div class="form-group row">
			<label class="col-md-3">Kode</label>
				<div class="col-md-7">
				@if ($MutasiKas->kode != null)
				<input type="text" name="kode" value="{{ $MutasiKas->kode }}" class="form-control" readonly>
				@elseif ($kode == null)
				<input type="text" name="kode" value="BKM-1" class="form-control" readonly>
				@else
				<input type="text" name="kode" value="{{ $kode->kode }}" class="form-control" readonly>
				@endif
			</div>
		</div>

		<div class="form-group row">
			<label class="col-md-3">Jenis Pembayaran Kas</label>
				<div class="col-md-7">
				<select id="id_pembayaran" class="form-control">
            		<option value="">Pilih Jenis Pembayaran Kas</option>
            		@foreach ($ArusKas as $kas)
            		<option value="{{ $kas->id}}">{{ $kas->nama }}</option>
            		@endforeach
          		</select>
			</div>
		</div>

		<div class="form-group row">
			<label class="col-md-3">Sub Jenis Penerimaan Kas</label>
				<div class="col-md-7">
				<select id="id_arus_kas" name="id_arus_kas" class="form-control">
					@if ($MutasiKas->id_arus_kas != null)
					   @foreach ($ArusKas as $kas)
						<option value="{{ $kas->id }}" {{( $MutasiKas->id_arus_kas == $kas->id)?'selected':''}}>{{ $kas->nama }}</option>
						@endforeach
					@else
                	<option value="--">Pilih Sub Jenis Penerimaan Kas</option>
					@endif
                </select>
			</div>
		</div>

		<div class="form-group row">
			<label class="col-md-3">Sub Jenis Penerimaan Kas</label>
				<div class="col-md-7">
				@if ($MutasiKas->tanggal == null)
				<input type="date" name="tanggal" value="{{ date('Y-m-d') }}" class="form-control">
				@else
				<input type="date" name="tanggal" value="{{ $MutasiKas->tanggal }}" class="form-control">
				@endif
			</div>
		</div>

		{!! App\Console\Commands\Generator\Form::textarea( 'keterangan' )->model($MutasiKas)->show() !!}

		<div class="form-group row">
			<label class="col-md-3">Penerimaan</label>
				<div class="col-md-7">
				<select name="id_perkiraan" class="form-control">
            		<option value="">Pilih Penerimaan</option>
					@if ($MutasiKas->id_perkiraan != null)
					   @foreach ($Perkiraan as $perkiraan)
						<option value="{{ $perkiraan->id }}" {{( $MutasiKas->id_perkiraan == $perkiraan->id)?'selected':''}}>{{ $perkiraan->nama }}</option>
						@endforeach
					@else
            		@foreach ($Perkiraan as $perkiraan)
            		<option value="{{ $perkiraan->id}}">{{ $perkiraan->nama }}</option>
            		@endforeach
					@endif
          		</select>
			</div>
		</div>

		<div class="form-group row">
			<label class="col-md-3">Pembayaran</label>
				<div class="col-md-7">
				<select name="id_kas_bank" class="form-control">
            		<option value="">Pilih Bank</option>
					@if ($MutasiKas->id_kas_bank != null)
					   @foreach ($KasBank as $bank)
						<option value="{{ $bank->id }}" {{( $MutasiKas->id_kas_bank == $bank->id)?'selected':''}}>{{ $bank->nama }}</option>
						@endforeach
					@else
            		@foreach ($KasBank as $bank)
            		<option value="{{ $bank->id}}">{{ $bank->nama }}</option>
            		@endforeach
					@endif
          		</select>
			</div>
		</div>

		<div class="form-group row">
			<label class="col-md-3">Nominal</label>
				<div class="col-md-7">
				@if ($MutasiKas->nominal == null)
				<input type="number" name="nominal" class="form-control">
				@else
				<input type="number" name="nominal" value="{{ $MutasiKas->nominal }}" class="form-control">
				@endif
			</div>
		</div>



	<div class="col-md-12 float-right">
		<div class="text-right">
			<button class="btn btn-primary" id="simpan">Simpan</button>
		</div>
	</div>
</div>
{{ Form::close() }}
</div>


<script type="text/javascript">

$(document).ready(function(){

//Kabupaten Ubah
$('#id_pembayaran').change(function(){

   // Department id
   var id_pembayaran = $(this).val();

   // Empty the dropdown
   $('#id_arus_kas').find('option').not(':first').remove();

   // AJAX request
   $.ajax({
	 url: 'mutasi-kas/get-id/'+id_pembayaran,
	 type: 'get',
	 dataType: 'json',
	 success: function(response){

	   var len = 0;
	   if(response['data'] != null){
		 len = response['data'].length;
	   }

	   if(len > 0){
		 // Read data and create <option >
		 for(var i=0; i<len; i++){

		   var id  = response['data'][i].id;
		   var nama = response['data'][i].nama;
		   var option = "<option value='"+id+"'>"+nama+"</option>";

		   $("#id_arus_kas").append(option);
		 }
	   }

	 }
  });
});

});


$(document).ready(function(){


	$('#mutasi-kas-form').formValidation({
	  framework: "bootstrap4",
	  button: {
	    selector: "#simpan",
	    disabled: "disabled"
	  },
	  icon: null,
	  fields: {
	kode : { validators: {
				        notEmpty: {
				          message: 'Kolom kode tidak boleh kosong'
							}
						}
					},nominal : { validators: {
				        notEmpty: {
				          message: 'Kolom nominal tidak boleh kosong'
							}
						}
					}
},
err: {
	clazz: 'invalid-feedback'
},
control: {
	// The CSS class for valid control
	valid: 'is-valid',

	// The CSS class for invalid control
	invalid: 'is-invalid'
},
row: {
	invalid: 'has-danger'
}
});

					var arus_kasEngine = new Bloodhound({
							datumTokenizer: function(d) { return d.tokens; },
							queryTokenizer: Bloodhound.tokenizers.whitespace,
							cache: false,
							remote: {
								url: '{{ url("autocomplete/arus_kas") }}?q=%QUERY',
								wildcard: "%QUERY"
							}
						});

						$("#arus_kas").typeahead({
									hint: true,
									highlight: true,
									minLength: 1
							},
							{
									source: arus_kasEngine.ttAdapter(),
									name: "arus_kas",
									displayKey: "arus_kas",
									templates: {
										suggestion: function(data){
											return Handlebars.compile([
																"<div class=\"tt-dataset\">",
																		"<div>@{{arus_kas}}</div>",
																"</div>",
														].join(""))(data);
										},
											empty: [
													"<div>arus_kas tidak ditemukan</div>"
											]
									}
							}).bind("typeahead:selected", function(obj, datum, name) {
								$("#id_arus_kas").val(datum.id);
							}).bind("typeahead:change", function(obj, datum, name) {

							});

					var perkiraanEngine = new Bloodhound({
							datumTokenizer: function(d) { return d.tokens; },
							queryTokenizer: Bloodhound.tokenizers.whitespace,
							cache: false,
							remote: {
								url: '{{ url("autocomplete/perkiraan") }}?q=%QUERY',
								wildcard: "%QUERY"
							}
						});

						$("#perkiraan").typeahead({
									hint: true,
									highlight: true,
									minLength: 1
							},
							{
									source: perkiraanEngine.ttAdapter(),
									name: "perkiraan",
									displayKey: "perkiraan",
									templates: {
										suggestion: function(data){
											return Handlebars.compile([
																"<div class=\"tt-dataset\">",
																		"<div>@{{perkiraan}}</div>",
																"</div>",
														].join(""))(data);
										},
											empty: [
													"<div>perkiraan tidak ditemukan</div>"
											]
									}
							}).bind("typeahead:selected", function(obj, datum, name) {
								$("#id_perkiraan").val(datum.id);
							}).bind("typeahead:change", function(obj, datum, name) {

							});

					var kas_bankEngine = new Bloodhound({
							datumTokenizer: function(d) { return d.tokens; },
							queryTokenizer: Bloodhound.tokenizers.whitespace,
							cache: false,
							remote: {
								url: '{{ url("autocomplete/kas_bank") }}?q=%QUERY',
								wildcard: "%QUERY"
							}
						});

						$("#kas_bank").typeahead({
									hint: true,
									highlight: true,
									minLength: 1
							},
							{
									source: kas_bankEngine.ttAdapter(),
									name: "kas_bank",
									displayKey: "kas_bank",
									templates: {
										suggestion: function(data){
											return Handlebars.compile([
																"<div class=\"tt-dataset\">",
																		"<div>@{{kas_bank}}</div>",
																"</div>",
														].join(""))(data);
										},
											empty: [
													"<div>kas_bank tidak ditemukan</div>"
											]
									}
							}).bind("typeahead:selected", function(obj, datum, name) {
								$("#id_kas_bank").val(datum.id);
							}).bind("typeahead:change", function(obj, datum, name) {

							});


});
</script>
