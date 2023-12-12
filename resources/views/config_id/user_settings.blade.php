
{{-- <div class="col-lg-12 text-center upload" style="margin-bottom: 2rem">
	<div class="kv-avatar">
		<div class="file-loading">
			<input id="avatar-2" name="avatar-2" type="file">
		</div>
	</div>

</div>

<div class="col-lg-12 text-center preview" style="margin-bottom: 2rem;display:none">
	<div class="kv-avatar">
		<div class="file-loading">
			<input id="avatar-3" type="file">
		</div>
		<div class="text-center">
			<a class="btn btn-xs btn-primary" style="color:white" id="edit_button"> Edit Foto</a>
			<a class="btn btn-xs btn-outline-success" style="color:#4caf50" id="hapus_button">Hapus Foto</a>
		</div>
	</div>
</div> --}}
<div class="row col-lg-12" style="margin-bottom: 0.8rem">
	<label for="nama" style="font-size:9pt" class="col-form-label col-sm-3 offset-md-0">Nama</label>
	<div class="form-group col-lg-7">
		<input name="nama" placeholder="" id="nama" class="form-control form-control-sm" value="{{isset($user) && !empty($user->name)?$user->name:''}}" type="text">
		<span id="help-block"></span>
	</div>
</div>

<div class="row col-lg-12" style="margin-bottom: 0.8rem">
	<label for="nama" style="font-size:9pt" class="col-form-label col-sm-3 offset-md-0">Username</label>
	<div class="form-group col-lg-7">
		<input name="username" placeholder="" id="username" class="form-control form-control-sm" value="{{isset($user) && !empty($user->username)?$user->username:''}}" type="text">
		<span id="help-block"></span>
	</div>
</div>

<div class="row col-lg-12" style="margin-bottom: 0.8rem">
	<label for="nama" style="font-size:9pt" class="col-form-label col-sm-3 offset-md-0">Email</label>
	<div class="form-group col-lg-6">
		<input type="email" value="{{isset($user) && !empty($user->email)?$user->email:''}}" name="email_username" id="email_username" class="form-control form-control-sm" placeholder="">
	<span id="help-block"></span>
	</div>
</div>

<div class="row col-lg-12" style="margin-bottom: 0.8rem">
	<label for="nama" style="font-size:9pt" class="col-form-label col-sm-3 offset-md-0">Password Lama</label>
	<div class="form-group col-lg-6">
		<input name="old_password" placeholder="" id="old_password" class="form-control form-control-sm password" value="" type="password">
	<span id="help-block"></span>
	</div>
	<div class="col-md-3">
		<i class="icon md-eye-off" aria-hidden="true">&nbsp<span style="font-family: sans-serif;font-size: 9pt">Lihat Password</span></i> 
	</div>
</div>

<div class="row col-lg-12" style="margin-bottom: 0.8rem">
	<label for="nama" style="font-size:9pt" class="col-form-label col-sm-3 offset-md-0">Password Baru</label>
	<div class="form-group col-lg-6">
		<input name="new_password" placeholder="" id="new_password" class="form-control form-control-sm password" value="" type="password">
	<span id="help-block"></span>
	</div>
</div>

<div class="row col-lg-12" style="margin-bottom: 0.8rem">
	<label for="nama" style="font-size:9pt" class="col-form-label col-sm-3 offset-md-0">Re-Type Password Baru</label>
	<div class="form-group col-lg-6">
		<input name="re_password" placeholder="" id="re_password" class="form-control form-control-sm password" value="" type="password">
	<span id="help-block"></span>
	</div>
</div>
{{-- 
<br>
<h5 class="example-title"><b>FASILITAS NOTIFIKASI</b></h5><span style="font-family: sans-serif;font-size: 9pt;color:blue">(Opsional, Tidak Wajib Diisi)</span>
<hr/>

<div class="example" id="notifikasi">
	<div class="row col-lg-12" style="margin-bottom: 1.429rem;">
		<div class="col-lg-12">
			<center>
				<span class="btn btn-sm btn-info" id="show_notifikasi_telegram" name="show_notifikasi_telegram" title="Aktifkan Notifikasi dengan menggunakan Telegram" type="button"><i class="icon md-notifications"></i>Aktifkan Notifikasi Telegam</span>

				<span class="btn btn-sm btn-success" id="show_notifikasi_whatsapp" name="show_notifikasi_whatsapp" title="Aktifkan Notifikasi dengan menggunakan Whatsapp" type="button"><i class="icon md-notifications"></i>Aktifkan Notifikasi Whatsapp</span>

				<span class="btn btn-sm btn-warning" id="show_notifikasi_email" name="show_notifikasi_email" title="Aktifkan Notifikasi dengan menggunakan Email" type="button"><i class="icon md-notifications"></i>Aktifkan Notifikasi Email</span>
			</center>
		</div>

	</div>


</div>
<br>
<br>
<div class="example-wrap notifikasi_telegram">
	<h5 class="example-title"><b>Notifikasi Telegram</b></h5>
	<hr/>

	<div class="example">
		<div class="row col-lg-8" style="margin-bottom: 1.429rem;">
			<label for="nama" class="col-form-label col-sm-5 offset-md-0">ID Pengguna</label>
			<div class="col-lg-7">
				<input name="notifikasi_telegram" id="notifikasi_telegram" class="form-control" placeholder="ID Pengguna. Cth : 109239123" value="{{(isset($pegawai) && !empty($pegawai->notifikasi_telegram)?$pegawai->notifikasi_telegram:'')}}" type="text" maxlength="30">

				<span style="color:black;font-size:11px;">Note : Untuk mendapatkan ID Pengguna Telegram anda, silakan klik <a style="color:blue;font-size:11px;" href="https://www.t.me/morhumanBot">Morhuman Bot<a></span>            </div>
				</div>
			</div>


		</div>

		<div class="example-wrap notifikasi_whatsapp">
			<h5 class="example-title"><b>Notifikasi Whatsapp</b></h5>
			<hr/>

			<div class="example">
				<div class="row col-lg-8" style="margin-bottom: 1.429rem;">
					<label for="nama" class="col-form-label col-sm-5 offset-md-0">Nomor Whatsapp</label>
					<div class="col-lg-7">
						<input name="notifikasi_whatsapp" id="notifikasi_whatsapp" class="form-control" placeholder="Nomor Whatsapp. Cth : +6281321123321" value="{{(isset($pegawai) && !empty($pegawai->notifikasi_whatsapp)?$pegawai->notifikasi_whatsapp:'')}}" type="text" maxlength="30">
						<span style="color:orange;font-size:11px;">Nomor Whatsapp yang di inputkan wajip menggunakan kode negara, Cth : +6281321123321.</span>       
					</div>
				</div>
			</div>


		</div>

		<div class="example-wrap notifikasi_email">
			<h5 class="example-title"><b>Notifikasi Email</b></h5>
			<hr/>

			<div class="example">
				<div class="row col-lg-8" style="margin-bottom: 1.429rem;">
					<label for="nama" class="col-form-label col-sm-5 offset-md-0">Email Aktif</label>
					<div class="col-lg-7">
						<input name="notifikasi_email" id="notifikasi_email" class="form-control" placeholder="Email Anda" value="{{(isset($pegawai) && !empty($pegawai->notifikasi_email)?$pegawai->notifikasi_email:'')}}" type="email" maxlength="30">
						<span style="color:orange;font-size:11px;">Email yang di inputkan harus merupakan email aktif dan benar.</span>   
					</div>
				</div>
			</div>
		</div>
 --}}
