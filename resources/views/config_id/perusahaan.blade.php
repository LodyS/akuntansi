<div class="row col-lg-8" style="margin-bottom: 0.8rem">
	<label for="nama" style="font-size:9pt" class="col-form-label col-sm-4 offset-md-0">Nama Perusahaan</label>
	<div class="col-lg-8">
		<input name="nama_perusahaan" placeholder="" id="nama_perusahaan" class="form-control form-control-sm" value="{{isset($perusahaan)?$perusahaan->nama_perusahaan!==null?$perusahaan->nama_perusahaan:'':''}}" type="text">
		<span id="help-block"></span>
	</div>
</div>

<div class="row col-lg-8" style="margin-bottom: 0.8rem">
	<label for="nama" style="font-size:9pt" class="col-form-label col-sm-4 offset-md-0">Alamat</label>
	<div class="col-lg-8">
		<textarea class="form-control form-control-sm" name="alamat" id="alamat">{{isset($perusahaan)?$perusahaan->alamat!==null?$perusahaan->alamat:'':''}}</textarea>
		<span id="help-block"></span>
	</div>
</div>

<div class="row col-lg-8" style="margin-bottom: 0.8rem">
	<label for="nama" style="font-size:9pt" class="col-form-label col-sm-4 offset-md-0">Website</label>
	<div class="form-group col-lg-8">
		<div class="input-group">
			<input name="website" placeholder="" id="website" class="form-control form-control-sm col-4" value="http://" type="text" readonly="">
			<input name="website1" placeholder="" id="website1" class="form-control form-control-sm" value="{{isset($perusahaan)?$perusahaan->website!==null?$perusahaan->website:'':''}}" type="text">
		</div>
	<span id="help-block"></span>
	</div>
</div>

<div class="row col-lg-8" style="margin-bottom: 0.8rem">
	<?php
	$email=isset($perusahaan)?$perusahaan->email!==null?explode('@',$perusahaan->email):'':'';
	?>
	<label for="nama" style="font-size:9pt" class="col-form-label col-sm-4 offset-md-0">Email</label>
	<div class="col-lg-4">
		<input type="text" value="{{$email!==''?$email[0]:''}}" name="email" id="email" class="form-control form-control-sm" placeholder="">
	</div>
	<div class="form-group col-lg-4">
		<div class="input-group">
			<input name="at" placeholder="" id="at" class="form-control form-control-sm col-4" value="@" type="text" readonly="">
		<input type="text" value="{{$email!==''?$email[1]:''}}" name="email1" id="email1" class="form-control form-control-sm" placeholder="">
	</div>
	</div>
	<span id="help-block"></span>
</div>
<div class="row col-lg-8" style="margin-bottom: 0.8rem">
	<label for="nama" style="font-size:9pt" class="col-form-label col-sm-4 offset-md-0">Toleransi Keterlambatan</label>
	<div class="col-lg-8">
		<input name="toleransi_keterlambatan" placeholder="" min="0" id="toleransi_keterlambatan" class="form-control form-control-sm" value="{{isset($perusahaan)?$perusahaan->toleransi_keterlambatan!==null?$perusahaan->toleransi_keterlambatan:'':0}}" type="number">
		<span id="help-block"></span>
	</div>
</div>
<div class="row col-lg-8" style="margin-bottom: 0.8rem">
	<label for="nama" style="font-size:9pt" class="col-form-label col-sm-4 offset-md-0">Jumlah Pengurangan Gaji</label>
	<div class="col-lg-8">
		<input name="jumlah_pengurangan_gaji" placeholder="" min="0" id="jumlah_pengurangan_gaji" class="form-control form-control-sm" value="{{isset($perusahaan)?$perusahaan->jumlah_pengurangan_gaji!==null?$perusahaan->jumlah_pengurangan_gaji:'':0}}" type="number">
		<span id="help-block"></span>
	</div>
</div>
<div class="row col-lg-8" style="margin-bottom: 0.8rem">
	<label for="nama" style="font-size:9pt" class="col-form-label col-sm-4 offset-md-0">Jumlah Pengurangan Jam</label>
	<div class="col-lg-8">
		<input name="jumlah_pengurangan_jam" placeholder="" min="0" id="jumlah_pengurangan_jam" class="form-control form-control-sm" value="{{isset($perusahaan)?$perusahaan->jumlah_pengurangan_jam!==null?$perusahaan->jumlah_pengurangan_jam:'':0}}" type="number">
		<span id="help-block"></span>
	</div>
</div>
<div class="row col-lg-8" style="margin-bottom: 0.8rem">
	<label for="nama" style="font-size:9pt" class="col-form-label col-sm-4 offset-md-0">Kebijakan Shift Perusahaan</label>
	<div class="col-lg-8">
		<select class="form-control form-control-sm select2" id="status_shift" name="status_shift" data-plugin="select2">
			<optgroup label="Status Shift">
				<option value="Y" {{isset($perusahaan)?$perusahaan->status_shift!==null?$perusahaan->status_shift=='Y'?'selected':'':'':''}}>Shift</option>
				<option value="N" {{isset($perusahaan)?$perusahaan->status_shift!==null?$perusahaan->status_shift=='N'?'selected':'':'':''}}>Non Shift</option>
			</optgroup>
		</select>
		<span id="help-block"></span>
	</div>
</div>
@if(isset($perusahaan) && $perusahaan->logo!==null)
<div class="row col-lg-8" style="margin-bottom: 0.8rem">
	<label for="nama" style="font-size:9pt" class="col-form-label col-sm-4 offset-md-0">Preview</label>
	<div class="col-lg-8">
		<img class="img-rounded" width="40%" height="auto" src="{{ asset('images/') }}/logo_perusahaan/{{$perusahaan->logo}}">
		<div class="text-right">
		<a href="#" class="btn btn-xs btn-warning" id="hapus" onclick="hapus_logo()" style="color: white">Hapus Logo</a>
		<input type="text" name="file_logo" value="{{$perusahaan->logo}}" style="display: none">
	</div>
	</div>
</div>
@endif
<div class="row col-lg-8" style="margin-bottom: 0.8rem">
	<label for="nama" style="font-size:9pt" class="col-form-label col-sm-4 offset-md-0">Logo</label>
	<div class="col-lg-8">
		<input type="file" class="form-control" name="logo" id="logo">
		<span id="help-block"></span>
	</div>
</div>

