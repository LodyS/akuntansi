<?php
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!d
|
*/
// Application Performence Monitoring
use Illuminate\Http\Request;

Route::get('/apm', '\Done\LaravelAPM\ApmController@index')->name('apm');

Route::get('/user/verify/{token}', 'Auth\RegisterController@verifyUser');
Route::get('register/create-resend/{user}', ['as' => 'register.create_resend', 'uses' => 'Auth\RegisterController@createResend']);
Route::post('register/resend', ['as' => 'register.resend', 'uses' => 'Auth\RegisterController@resend']);

Route::get('/send', 'SendMessageController@index')->name('send');
Route::get('/sendEmailNotifikasi', 'SendMessageController@sendEmailNotifikasi');
Route::post('/postMessage', 'SendMessageController@sendMessage')->name('postMessage');

Route::get('/', function () {
    // return view('welcome');
    if (Auth::check()) {

        return redirect('beranda');
    } else {

        return redirect('login');
    }
});

Route::get('/admin', function () {
    // return view('welcome');
    if (Auth::check()) {

        return redirect('beranda');
    } else {

        return redirect('login');
    }
});

Route::get('/coba', function () {
    return view('coba');
});

Auth::routes();

Route::get('/update/pull', 'UpdateSystemController@pull');

Route::get('/beranda', 'HomeController@index')->name('beranda');
Route::get('/pencarian-laba', 'HomeController@search')->name('pencarian-laba');
Route::post('/pencarian-laba', 'HomeController@search')->name('pencarian-laba');

Route::match(['get', 'post'], 'home/get-notif', 'HomeController@getNotif');
Route::match(['get', 'post'], 'home/update-notif', 'HomeController@updateNotif');
Route::match(['get', 'post'], 'home/delete-notif', 'HomeController@deleteNotif');

Route::get('user/load-data', 'UserController@loadData');
Route::get('user/json', 'UserController@json');
Route::get('user/activate/{id}', 'UserController@activate');
Route::get('user/update/{id}', 'UserController@update');
Route::get('user/deactivate/{id}', 'UserController@deactivate');
Route::get('user/{id}/reset', 'UserController@reset');
Route::resource('user', 'UserController');
Route::delete('user/{id}/restore', 'UserController@restore');


Route::get('user/cek-username','UserController@checkUsername');
Route::get('user/cek-email','UserController@checkEmail');

Route::get('autocomplete/{method}', 'AutocompleteController@search');

Route::post('role/createpermissionrole', ['as' => 'role.createpermissionrole', 'uses' => 'RoleController@createpermissionrole']);

Route::get('role/load-data', 'RoleController@loadData');
Route::get('permission-role/get/{id}/menu', 'RoleController@hakmenus');
Route::get('role/permission-role/get/{id}/menu', 'RoleController@hakmenus');
Route::resource('role', 'RoleController');
Route::delete('role/{id}/restore', 'RoleController@restore');

Route::get('permission-role/load-data', 'PermissionRoleController@loadData');
Route::resource('permission-role', 'PermissionRoleController');
Route::delete('permission-role/{id}/restore', 'PermissionRoleController@restore');

Route::get('menu/load-data', 'MenuController@loadData');
Route::resource('menu', 'MenuController');
Route::delete('menu/{id}/restore','MenuController@restore');

Route::get('permission/load-data', 'PermissionController@loadData');
Route::resource('permission', 'PermissionController');
Route::delete('permission/{id}/restore', 'PermissionController@restore');

Route::get('/pengaturan', 'ConfigIdController@settings')->name('settings');
Route::get('/delete-logo', 'ConfigIdController@deleteLogo');
Route::get('config-id/load-data', 'ConfigIdController@loadData');
Route::match(['get', 'post'], 'config-id/upload-foto', 'ConfigIdController@uploadFoto');
Route::match(['get', 'post'], 'config-id/check-username', 'ConfigIdController@checkUsername');
Route::match(['get', 'post'], 'config-id/check-email', 'ConfigIdController@checkEmail');
Route::match(['get', 'post'], 'config-id/check-password', 'ConfigIdController@checkPassword');
Route::get('config-id/delete-foto', 'ConfigIdController@deleteFoto');
Route::resource('config-id', 'ConfigIdController');
Route::delete('config-id/{id}/restore', 'ConfigIdController@restore');

Route::get('activity/load-data', 'ActivityLogController@loadData');
Route::get('activity/get-data', 'ActivityLogController@getData');
Route::resource('activity', 'ActivityLogController');
Route::delete('activity/{id}/restore', 'ActivityLogController@restore');

//Route master perusahaan
Route::get('perusahaan/load-data', 'PerusahaanController@loadData');
Route::resource('perusahaan', 'PerusahaanController');
Route::delete('perusahaan/{id}/restore', 'PerusahaanController@restore');

//route master unit
Route::get('unit/load-data', 'UnitController@loadData');
Route::resource('unit', 'UnitController');
Route::delete('unit/{id}/restore', 'UnitController@restore');
Route::get('unit/deactivate/{id}', 'UnitController@deactivate');
Route::get('unit/activate/{id}', 'UnitController@activate');
Route::get('unit/cekKodeDepartemen/{kode}', 'UnitController@cekKode')->name('cekKodeDepartemen');

//route master tarif pajak
Route::get('tarif-pajak/load-data', 'TarifPajakController@loadData');
Route::resource('tarif-pajak', 'TarifPajakController');
Route::delete('tarif-pajak/{id}/restore', 'TarifPajakController@restore');
Route::get('tarif-pajak/deactivate/{id}', 'TarifPajakController@deactivate');
Route::get('tarif-pajak/activate/{id}', 'TarifPajakController@activate');

Route::get('termin-pembayaran/load-data', 'TerminPembayaranController@loadData');
Route::resource('termin-pembayaran', 'TerminPembayaranController');
Route::delete('termin-pembayaran/{id}/restore', 'TerminPembayaranController@restore');
Route::get('termin-pembayaran/cekKodeTerminPembayaran/{kode}', 'TerminPembayaranController@cekKode')->name('cekKodeTerminPembayaran');

Route::get('pelanggan/load-data', 'PelangganController@loadData');
Route::resource('pelanggan', 'PelangganController');
Route::delete('pelanggan/{id}/restore', 'PelangganController@restore');
Route::get('pelanggan/deactivate/{id}', 'PelangganController@deactivate');
Route::get('pelanggan/activate/{id}', 'PelangganController@activate');
Route::get('pelanggan/get-kabupaten/{id}', 'PelangganController@getKabupaten');
Route::get('pelanggan/get-kecamatan/{id}', 'PelangganController@getKecamatan');
Route::get('pelanggan/get-kelurahan/{id}', 'PelangganController@getKelurahan');

Route::get('instansi-relasi/load-data', 'InstansiRelasiController@loadData');
Route::get('detail', 'InstansiRelasiController@detail');
Route::resource('instansi-relasi', 'InstansiRelasiController');
Route::delete('instansi-relasi/{id}/restore', 'InstansiRelasiController@restore');

Route::get('tipe-jurnal/load-data', 'TipeJurnalController@loadData');
Route::resource('tipe-jurnal', 'TipeJurnalController');
Route::delete('tipe-jurnal/{id}/restore', 'TipeJurnalController@restore');

Route::get('instansi-relasi/load-data', 'InstansiRelasiController@loadData');
Route::resource('instansi-relasi', 'InstansiRelasiController');
Route::delete('instansi-relasi/{id}/restore', 'InstansiRelasiController@restore');
Route::get('instansi-relasi/get-kabupaten/{id}', 'InstansiRelasiController@getKabupaten');
Route::get('instansi-relasi/get-kecamatan/{id}', 'InstansiRelasiController@getKecamatan');
Route::get('instansi-relasi/get-kelurahan/{id}', 'InstansiRelasiController@getKelurahan');

Route::get('kelompok-aktiva/load-data', 'KelompokAktivaController@loadData');
Route::resource('kelompok-aktiva', 'KelompokAktivaController');
Route::delete('kelompok-aktiva/{id}/restore', 'KelompokAktivaController@restore');
Route::get('kelompok-aktiva/deactivate/{id}', 'KelompokAktivaController@deactivate');
Route::get('kelompok-aktiva/activate/{id}',   'KelompokAktivaController@activate');
Route::get('kelompok-aktiva/cekKodeKelompokAktiva/{kode}', 'KelompokAktivaController@cekKode')->name('cekKodeKelompokAktiva');
Route::get('kelompok-aktiva/cekNamaKelompokAktiva/{nama}', 'KelompokAktivaController@cekNama')->name('cekNamaKelompokAktiva');

Route::get('periode-keuangan/load-data', 'PeriodeKeuanganController@loadData');
Route::resource('periode-keuangan', 'PeriodeKeuanganController');
Route::delete('periode-keuangan/{id}/restore', 'PeriodeKeuanganController@restore');
Route::post('periode-keuangan/pencarian-tahun', 'PeriodeKeuanganController@cari');
Route::get('periode-keuangan/pencarian-tahun', 'PeriodeKeuanganController@cari');

Route::get('fungsi/load-data', 'FungsiController@loadData');
Route::resource('fungsi', 'FungsiController');
Route::delete('fungsi/{id}/restore', 'FungsiController@restore');
Route::get('fungsi/deactivate/{id}', 'FungsiController@deactivate');
Route::get('fungsi/activate/{id}',   'FungsiController@activate');
Route::get('fungsi/cekNamaFungsi/{nama_fungsi}', 'FungsiController@cekNamaFungsi')->name('cekNamaFungsi');

//Route::get('transaksi/load-data', 'TransaksiController@loadData');
Route::resource('transaksi', 'TransaksiController');
Route::delete('transaksi/{id}/restore', 'TransaksiController@restore');
Route::post('/insert', 'TransaksiController@insert');
Route::post('/update-transaksi', 'TransaksiController@update');
Route::get('transaksi/perkiraan-tipe-detail', 'TransaksiController@perkiraan');
Route::get('transaksi/edit/{id}', 'TransaksiController@editData');

Route::get('akun-pendapatan-jasa', 'AkunPendapatanJasaController@index');
Route::post('akun-pendapatan-jasa/pencarian', 'AkunPendapatanJasaController@pencarian');
route::get('edit-setting-pendapatan-jasa', 'AkunPendapatanJasaController@edit')->name('edit-setting-pendapatan-jasa');
Route::post('/update-setting-coa-pendapatan-jasa', 'AkunPendapatanJasaController@UpdateJasa')->name('update-setting-coa-pendapatan-jasa');

Route::get('akun-penjualan-obat/index', 'AkunPenjualanObatController@index');
route::get('edit-setting-pendapatan-obat', 'AkunPenjualanObatController@edit')->name('edit-setting-pendapatan-obat');
Route::post('/update-obat', 'AkunPenjualanObatController@update')->name('update-setting-pendapatan-obat');

Route::get('setting-akun-hutang/index', 'SettingAkunHutangController@index');
Route::get('setting-akun-hutang/hutang-jangka-panjang', 'SettingAkunHutangController@JangkaPanjang');
Route::post('/simpanSettingHutang', 'SettingAkunHutangController@store');
Route::post('/updateSettingHutang', 'SettingAkunHutangController@update');

Route::get('setting-akun-pajak', 'SettingAkunPajakController@index');
Route::post('/simpanSettingPajak', 'SettingAkunPajakController@simpanSettingPajak');
Route::post('/updateSettingPajak', 'SettingAkunPajakController@updateSettingPajak');

Route::get('setting-kas-bank/index', 'SettingKasBankController@index');
route::get('edit-setting-coa-kas-bank', 'SettingKasBankController@edit')->name('edit-setting-coa-kas-bank');
Route::post('/update-setting-coa-kas-bank', 'SettingKasBankController@update')->name('update-setting-coa-kas-bank');

Route::get('setting-akun-piutang/index', 'SettingAkunPiutangController@index');
Route::post('setting-akun-piutang/setting', 'SettingAkunPiutangController@rawat');
Route::get('setting-akun-piutang/rawat-inap', 'SettingAkunPiutangController@rawat');
Route::get('setting-akun-piutang/rawat-jalan', 'SettingAkunPiutangController@rawat');
Route::post('/simpan-setting-piutang', 'SettingAkunPiutangController@store');
Route::post('/update-setting-piutang', 'SettingAkunPiutangController@update');

Route::get('layanan/load-data','LayananController@loadData');
Route::resource('layanan','LayananController');
Route::delete('layanan/{id}/restore','LayananController@restore');

Route::get('kelas/load-data','KelasController@loadData');
Route::resource('kelas','KelasController');
Route::delete('kelas/{id}/restore','KelasController@restore');

Route::get('tarif/load-data', 'TarifController@loadData');
Route::resource('tarif', 'TarifController');

Route::get('spesialisasi/load-data','SpesialisasiController@loadData');
Route::resource('spesialisasi','SpesialisasiController');
Route::delete('spesialisasi/{id}/restore','SpesialisasiController@restore');

Route::get('spesialisasi/edit-data/{id}', 'SpesialisasiController@editData');
Route::post('/updateDataSpesialisasi', 'SpesialisasiController@updateDataSpesialisasi');

Route::get('produk-asuransi/load-data','ProdukAsuransiController@loadData');
Route::resource('produk-asuransi','ProdukAsuransiController');
Route::delete('produk-asuransi/{id}/restore','ProdukAsuransiController@restore');
Route::post('/updateData', 'ProdukAsuransiController@updateData');

Route::get('nakes/load-data','NakesController@loadData');
Route::resource('nakes','NakesController');

Route::get('radiologi/load-data','RadiologiController@loadData');
Route::get('radiologi/load-data-jenis-radiologi','RadiologiController@loadDataJenisRadiologi');
Route::get('radiologi/load-data-golongan-radiologi','RadiologiController@loadDataGolonganRadiologi');
Route::get('radiologi/load-data','RadiologiController@loadData');
Route::get('radiologi/jenis_radiologi', ['as' => 'radiologi.jenis_radiologi', 'uses' => 'RadiologiController@jenis_radiologi']);
Route::get('radiologi/golongan_radiologi', ['as' => 'radiologi.golongan_radiologi', 'uses' => 'RadiologiController@golongan_radiologi']);
Route::get('radiologi/create_jenis_radiologi',['as' => 'radiologi.create_jenis_radiologi', 'uses' => 'RadiologiController@createJenisRadiologi']);
Route::get('radiologi/create_golongan_radiologi',['as' => 'radiologi.create_golongan_radiologi', 'uses' => 'RadiologiController@createGolonganRadiologi']);
Route::post('radiologi/jenis_radiologi',['as' => 'radiologi.store_jenis_radiologi', 'uses' => 'RadiologiController@storeJenisRadiologi']);
Route::post('radiologi/golongan_radiologi',['as' => 'radiologi.store_golongan_radiologi', 'uses' => 'RadiologiController@storeGolonganRadiologi']);
Route::delete('radiologi/jenis_radiologi/{kode}','RadiologiController@destroyJenisRadiologi');
Route::delete('radiologi/golongan_radiologi/{kode}','RadiologiController@destroyGolonganRadiologi');
Route::resource('radiologi','RadiologiController');

Route::get('laboratorium/load-data','LaboratoriumController@loadData');
Route::resource('laboratorium','LaboratoriumController');

Route::get('cabang-user/load-data','CabangUserController@loadData');
Route::resource('cabang-user','CabangUserController');
Route::delete('cabang-user/{id}/restore','CabangUserController@restore');

Route::get('jurnal-pendapatan-jasa/index', 'JurnalPendapatanJasaController@index');
Route::post('jurnal-pendapatan-jasa/rekapitulasi-pendapatan-jasa', 'JurnalPendapatanJasaController@Rekapitulasi');
Route::get('jurnal-pendapatan-jasa/rekapitulasi-pendapatan-jasa', 'JurnalPendapatanJasaController@Rekapitulasi');
Route::get('jurnal-pendapatan-jasa/detail/{id_pendapatan_jasa}', 'JurnalPendapatanJasaController@detail');
Route::get('jurnal-pendapatan-jasa/jurnal-umum/{id_pendapatan_jasa}/{tipe_pasien}/{tanggal}/{tipe_pembayaran}/{jenis}', 'JurnalPendapatanJasaController@Jurnal');
Route::post('/simpan-jurnal-pendapatan-jasa', 'JurnalPendapatanJasaController@Simpan');

Route::get('discharge_pasien/index', 'DischargePasienController@index');
Route::get('discharge_pasien/in', 'DischargePasienController@index');
Route::post('discharge_pasien/rekapitulasi', 'DischargePasienController@rekapitulasi');
Route::get('discharge_pasien/rekapitulasi', 'DischargePasienController@rekapitulasi');
Route::post('/update-discharge', 'DischargePasienController@discharge');
/*Route::get('discharge_pasien', ['as' => 'discharge_pasien.index', 'uses' => 'DischargePasienController@index']);
Route::post('discharge_pasien/search', ['as' => 'discharge_pasien.search', 'uses' => 'DischargePasienController@search']);
Route::post('discharge_pasien/discharge', ['as' => 'discharge_pasien.discharge', 'uses' => 'DischargePasienController@discharge']);*/

Route::get('jurnal-penagihan-piutang/index', 'JurnalPenagihanPiutangController@index');
Route::post('jurnal-penagihan-piutang/rekapitulasi-penagihan-piutang', 'JurnalPenagihanPiutangController@rekapitulasiPenagihan');
Route::get('jurnal-penagihan-piutang/rekapitulasi-penagihan-piutang', 'JurnalPenagihanPiutangController@rekapitulasiPenagihan');
Route::get('jurnal-penagihan-piutang/jurnal-umum', 'JurnalPenagihanPiutangController@JurnalUmum');
Route::post('jurnal-penagihan-piutang/jurnal-umum', 'JurnalPenagihanPiutangController@JurnalUmum');
Route::post('/simpanJurnalPenagihanPiutang', 'JurnalPenagihanPiutangController@simpanJurnalPenagihanPiutang');

Route::get('jurnal-pasien-ri-pulang-rawat/index', 'JurnalPasienRiController@index');
Route::post('jurnal-pasien-ri-pulang-rawat/rekapitulasi', 'JurnalPasienRiController@rekapitulasi');
Route::get('jurnal-pasien-ri-pulang-rawat/rekapitulasi', 'JurnalPasienRiController@rekapitulasi');
Route::post('jurnal-pasien-ri-pulang-rawat/jurnal', 'JurnalPasienRiController@jurnal');
Route::get('jurnal-pasien-ri-pulang-rawat/jurnal', 'JurnalPasienRiController@jurnal');
Route::post('/simpan-jurnal-ri', 'JurnalPasienRiController@simpan');

Route::get('kelompok-bisnis/load-data','KelompokBisnisController@loadData');
Route::resource('kelompok-bisnis','KelompokBisnisController');
Route::delete('kelompok-bisnis/{id}/restore','KelompokBisnisController@restore');
Route::get('kelompok-bisnis/deactivate/{id}', 'KelompokBisnisController@deactivate');
Route::get('kelompok-bisnis/activate/{id}',   'KelompokBisnisController@activate');
Route::get('kelompok-bisnis/cekKodeKelompokBisnis/{kode}', 'KelompokBisnisController@cekKode')->name('cekKodeKelompokBisnis');

Route::get('jenis-usaha/load-data','JenisUsahaController@loadData');
Route::resource('jenis-usaha','JenisUsahaController');
Route::delete('jenis-usaha/{id}/restore','JenisUsahaController@restore');
Route::get('jenis-usaha/deactivate/{id}', 'JenisUsahaController@deactivate');
Route::get('jenis-usaha/activate/{id}',   'JenisUsahaController@activate');
Route::get('jenis-usaha/cekKodeBadanUsaha/{kode}', 'JenisUsahaController@cekKodeBadanUsaha')->name('cekKodeBadanUsaha');

Route::get('sub-unit-usaha/load-data','SubUnitUsahaController@loadData');
Route::resource('sub-unit-usaha','SubUnitUsahaController');
Route::delete('sub-unit-usaha/{id}/restore','SubUnitUsahaController@restore');
Route::get('sub-unit-usaha/deactivate/{id}', 'SubUnitUsahaController@deactivate');
Route::get('sub-unit-usaha/activate/{id}',   'SubUnitUsahaController@activate');
Route::get('sub-unit-usaha/cekKodeSubUnitUsaha/{kode}', 'SubUnitUsahaController@cekKode')->name('cekKodeSubUnitUsaha');

Route::get('sub-jenis-usaha/load-data','SubJenisUsahaController@loadData');
Route::resource('sub-jenis-usaha','SubJenisUsahaController');
Route::delete('sub-jenis-usaha/{id}/restore','SubJenisUsahaController@restore');
Route::get('sub-jenis-usaha/deactivate/{id}', 'SubJenisUsahaController@deactivate');
Route::get('sub-jenis-usaha/activate/{id}',   'SubJenisUsahaController@activate');
Route::get('sub-jenis-usaha/cekKodeSubJenisUsaha/{kode}', 'SubJenisUsahaController@cekKode')->name('cekKodeSubJenisUsaha');

Route::get('provinsi/load-data','ProvinsiController@loadData');
Route::resource('provinsi','ProvinsiController');
Route::delete('provinsi/{id}/restore','ProvinsiController@restore');

Route::get('kabupaten/load-data','KabupatenController@loadData');
Route::resource('kabupaten','KabupatenController');
Route::delete('kabupaten/{id}/restore','KabupatenController@restore');

Route::get('kecamatan/load-data','KecamatanController@loadData');
Route::resource('kecamatan','KecamatanController');
Route::delete('kecamatan/{id}/restore','KecamatanController@restore');

Route::get('kelurahan/load-data','KelurahanController@loadData');
Route::resource('kelurahan','KelurahanController');
Route::delete('kelurahan/{id}/restore','KelurahanController@restore');

Route::resource('pendapatan-jasa','PendapatanJasaController');
Route::delete('pendapatan-jasa/{id}/restore','PendapatanJasaController@restore');
Route::get('pendapatan-jasa/isiPasien/{no_kunjungan}', 'PendapatanJasaController@isiPasien')->name('isiPasien');
Route::get('pendapatan-jasa/isiTarif/{id}', 'PendapatanJasaController@isiTarif')->name('isiTarif');
Route::post('/simpan-pendapatan-jasa', 'PendapatanJasaController@simpanPendapatanJasa');

Route::get('aktiva-tetap/load-data','AktivaTetapController@loadData');
Route::resource('aktiva-tetap','AktivaTetapController');
Route::delete('aktiva-tetap/{id}/restore','AktivaTetapController@restore');
Route::get('aktiva-tetap/isiKodeKelompokAktiva/{id}', 'AktivaTetapController@isiKode')->name('isiKodeKelompokAktiva');
Route::get('aktiva-tetap/detail/{id}', 'AktivaTetapController@detail');

Route::get('setup-awal-periode/load-data','SetupAwalPeriodeController@loadData');
Route::resource('setup-awal-periode','SetupAwalPeriodeController');
Route::delete('setup-awal-periode/{id}/restore','SetupAwalPeriodeController@restore');

Route::get('kas-bank/load-data','KasBankController@loadData');
Route::resource('kas-bank','KasBankController');
Route::delete('kas-bank/{id}/restore','KasBankController@restore');
Route::get('kas-bank/deactivate/{id}', 'KasBankController@deactivate');
Route::get('kas-bank/activate/{id}', 'KasBankController@activate');
Route::get('kas-bank/cekKode/{kode_bank}', 'KasBankController@cekKode')->name('cekKode');

Route::get('perkiraan/load-data','PerkiraanController@loadData');
Route::resource('perkiraan','PerkiraanController');
Route::delete('perkiraan/{id}/restore','PerkiraanController@restore');
Route::get('perkiraan/isiKolom/{id_induk}', 'PerkiraanController@isiKolom')->name('isiKolom');
Route::get('perkiraan/cekNamaPerkiraan/{nama}', 'PerkiraanController@cekNama')->name('cekNamaPerkiraan');

Route::get('sistem-informasi-piutang', 'SistemInformasiPiutangController@index');
Route::get('sistem-informasi-piutang/tambah-saldo/{id}', 'SistemInformasiPiutangController@tambahSaldo');
Route::get('sistem-informasi-piutang/detail-piutang/{id}', 'SistemInformasiPiutangController@detailPiutang');
Route::get('sistem-informasi-piutang/mutasi-piutang/{id}', 'SistemInformasiPiutangController@mutasiPiutang');
Route::post('/simpan-saldo', 'SistemInformasiPiutangController@simpanSaldo');

Route::get('jurnal-penerimaan-piutang/index', 'JurnalPenerimaanPiutangController@index');
Route::get('jurnal-penerimaan-piutang/antar-unit', 'JurnalPenerimaanPiutangController@AntarUnit');
Route::post('jurnal-penerimaan-piutang/rekapitulasi-jurnal-penerimaan-piutang', 'JurnalPenerimaanPiutangController@rekapitulasi');
Route::get('jurnal-penerimaan-piutang/rekapitulasi-jurnal-penerimaan-piutang', 'JurnalPenerimaanPiutangController@rekapitulasi');
Route::get('jurnal-penerimaan-piutang/detail/{id_pelanggan}/{tanggal}', 'JurnalPenerimaanPiutangController@detail');
Route::get('jurnal-penerimaan-piutang/jurnal-umum', 'JurnalPenerimaanPiutangController@jurnal');
Route::post('jurnal-penerimaan-piutang/jurnal-umum', 'JurnalPenerimaanPiutangController@jurnal');
Route::post('/simpan-jurnal-penerimaan-piutang', 'JurnalPenerimaanPiutangController@simpanJurnalPenerimaanPiutang');

Route::get('penerimaan-piutang', 'PenerimaanPiutangController@index');
Route::get('penerimaan-piutan/isiPasien/{id}', 'PenerimaanPiutangController@cariPasien')->name('cariPasien');
Route::post('penerimaan-piutang/laporan-penerimaan-piutang', 'PenerimaanPiutangController@LaporanPenerimaanPiutang');
Route::get('penerimaan-piutang/laporan-penerimaan-piutang', 'PenerimaanPiutangController@LaporanPenerimaanPiutang');
Route::post('/simpan-penerimaan-piutang', 'PenerimaanPiutangController@SimpanPenerimaanPiutang');

Route::get('penjualan-obat/index', 'PenjualanObatController@index');
Route::get('penjualan-obat/penjualan-obat-bebas', 'PenjualanObatController@PenjualanObatBebas');
Route::get('penjualan-obat/isiBarang/{barcode}', 'PenjualanObatController@isiBarang')->name('isiBarang');
Route::get('penjualan-obat/isiDiskon/{diskon}', 'PenjualanObatController@isiDiskon')->name('isiDiskon');
Route::get('penjualan-obat/cariPasien/{id_visit}', 'PenjualanObatController@cariPasien')->name('cariPasien');
Route::post('/simpan-penjualan-obat', 'PenjualanObatController@SimpanPenjualanObat'); //menyimpan penjualan obat resep
Route::post('/simpan-penjualan-obat-bebas', 'PenjualanObatController@SimpanPenjualanObatBebas');

Route::get('sistem-informasi-hutang', 'SistemInformasiHutangController@index');
Route::get('sistem-informasi-hutang/tambah-saldo/{id}', 'SistemInformasiHutangController@tambahSaldo');
Route::get('sistem-informasi-hutang/detail-hutang/{id}', 'SistemInformasiHutangController@detailHutang');
Route::get('sistem-informasi-hutang/mutasi-hutang/{id}', 'SistemInformasiHutangController@mutasiHutang');
Route::post('/simpan-saldo-hutang', 'SistemInformasiHutangController@simpanSaldoHutang');

Route::get('pembelian-logistik-farmasi/index', 'PembelianLogistikFarmasiController@index');
Route::get('pembelian-logistik-farmasi/CariPemasok/{id_pemasok}', 'PembelianLogistikFarmasiController@cariPemasok')->name('CariPemasok');
Route::get('pembelian-logistik-farmasi/CariBarang/{barcode}', 'PembelianLogistikFarmasiController@cariBarang')->name('CariBarang');
Route::post('/simpan-pembelian-logistik-farmasi', 'PembelianLogistikFarmasiController@SimpanPembelianLogistik');

Route::get('jurnal-penjualan-obat-tunai', 'JurnalPenjualanObatTunaiController@index');
Route::post('jurnal-penjualan-obat-tunai/rekapitulasi-jurnal-penjualan-obat-tunai', 'JurnalPenjualanObatTunaiController@RekapitulasiPenjualanObatTunai');
Route::get('jurnal-penjualan-obat-tunai/rekapitulasi-jurnal-penjualan-obat-tunai', 'JurnalPenjualanObatTunaiController@RekapitulasiPenjualanObatTunai');
Route::get('jurnal-penjualan-obat-tunai/jurnal-umum/{id_penjualan}/{id_tipe_pasien?}', 'JurnalPenjualanObatTunaiController@JurnalPenjualanObatTunai');
Route::post('/simpan-jurnal-penjualan-obat-tunai', 'JurnalPenjualanObatTunaiController@SimpanObatTunai');

Route::get('jenis-pembelian/load-data','JenisPembelianController@loadData');
Route::resource('jenis-pembelian','JenisPembelianController');
Route::delete('jenis-pembelian/{id}/restore','JenisPembelianController@restore');

Route::get('jurnal-pembelian-logistik-farmasi/index', 'JurnalPembelianLogistikFarmasiController@index');
Route::post('jurnal-pembelian-logistik-farmasi/rekapitulasi', 'JurnalPembelianLogistikFarmasiController@rekapitulasi');
Route::get('jurnal-pembelian-logistik-farmasi/rekapitulasi', 'JurnalPembelianLogistikFarmasiController@rekapitulasi');
Route::get('jurnal-pembelian-logistik-farmasi/jurnal/{id_pembelian}/{status}/{tanggal}', 'JurnalPembelianLogistikFarmasiController@jurnal');
Route::post('/simpan-jurnal-pembelian-logistik-farmasi', 'JurnalPembelianLogistikFarmasiController@SimpanJurnalPembelianLogistik');

Route::get('pembayaran-hutang/index', 'PembayaranHutangController@index');
Route::get('pembayaran-hutang/rekapitulasi', 'PembayaranHutangController@rekapitulasi');
Route::post('pembayaran-hutang/rekapitulasi', 'PembayaranHutangController@rekapitulasi');
Route::get('pembayaran-hutang/pembayaran/{id}', 'PembayaranHutangController@pembayaran');
Route::get('pembayaran-hutang/laporan-angsuran/{id}', 'PembayaranHutangController@LaporanAngsuran');
Route::post('/simpan-pembayaran-hutang', 'PembayaranHutangController@SimpanPembayaranHutang');

Route::get('mutasi-kas/load-data','MutasiKasController@loadData');
Route::resource('mutasi-kas','MutasiKasController');
Route::post('/simpan-mutasi-kas', 'MutasiKasController@store');
Route::post('/update-mutasi-kas', 'MutasiKasController@update');
Route::get('mutasi-kas/get-id/{id_pembayaran}', 'MutasiKasController@getId');
Route::get('mutasi-kas/lihat-bukti/{id}', 'MutasiKasController@lihatBukti');
Route::get('mutasi-kas/lihat-bukti-asli/{id}', 'MutasiKasController@lihatBuktiAsli');
Route::delete('mutasi-kas/{id}/restore','MutasiKasController@restore');

Route::get('pengeluaran-kas/load-data','PengeluaranKasController@loadData');
Route::post('/simpan-pengeluaran-kas', 'PengeluaranKasController@store');
Route::post('/update-pengeluaran-kas', 'PengeluaranKasController@update');
Route::get('pengeluaran-kas/lihat-bukti/{id}', 'PengeluaranKasController@lihatBukti');
Route::resource('pengeluaran-kas','PengeluaranKasController');
Route::get('pengeluaran-kas/get-id/{id_induk}', 'PengeluaranKasController@getId');
Route::delete('pengeluaran-kas/{id}/restore','PengeluaranKasController@restore');
Route::get('pengeluaran-kas/lihat-bukti-asli/{id}', 'PengeluaranKasController@lihatBuktiAsli');

Route::get('jurnal-pembayaran-hutang/index', 'JurnalPembayaranHutangController@index');
Route::get('jurnal-pembayaran-hutang/rekapitulasi-pembayaran-hutang', 'JurnalPembayaranHutangController@rekapitulasi');
Route::post('jurnal-pembayaran-hutang/rekapitulasi-pembayaran-hutang', 'JurnalPembayaranHutangController@rekapitulasi');
Route::post('jurnal-pembayaran-hutang/jurnal', 'JurnalPembayaranHutangController@JurnalPembayaranHutang');
Route::get('jurnal-pembayaran-hutang/jurnal', 'JurnalPembayaranHutangController@JurnalPembayaranHutang');
Route::post('/simpan-jurnal-pembayaran-hutang', 'JurnalPembayaranHutangController@simpan');

Route::get('visit/load-data','VisitController@loadData');
Route::resource('visit','VisitController');
Route::delete('visit/{id}/restore','VisitController@restore');

Route::get('penyusutan-aktiva-tetap/index', 'PenyusutanAktivaTetapController@index');
Route::get('penyusutan-aktiva-tetap/rekapitulasi', 'PenyusutanAktivaTetapController@rekap');
Route::post('penyusutan-aktiva-tetap/rekapitulasi', 'PenyusutanAktivaTetapController@rekap');
Route::get('penyusutan-aktiva-tetap/penyusutan/{id}/{bulan}', 'PenyusutanAktivaTetapController@penyusutan');
Route::get('penyusutan-aktiva-tetap/penyusutan-aktiva/{id}/{bulan}', 'PenyusutanAktivaTetapController@penyusutanAktiva');
Route::post('/simpan-penyusutan-aktiva-tetap', 'PenyusutanAktivaTetapController@simpan');

Route::get('jurnal-penerimaan-kas/load-data','JurnalPenerimaanKasController@loadData');
Route::get('jurnal-penerimaan-kas/create-jurnal/{tgl}','JurnalPenerimaanKasController@createJurnal');
Route::post('jurnal-penerimaan-kas/store-jurnal','JurnalPenerimaanKasController@storeJurnal');
Route::resource('jurnal-penerimaan-kas','JurnalPenerimaanKasController');
Route::delete('jurnal-penerimaan-kas/{id}/restore','JurnalPenerimaanKasController@restore');

Route::get('jurnal-pengeluaran-kas/load-data','JurnalPengeluaranKasController@loadData');
Route::get('jurnal-pengeluaran-kas/create-jurnal/{tgl}','JurnalPengeluaranKasController@createJurnal');
Route::post('jurnal-pengeluaran-kas/store-jurnal','JurnalPengeluaranKasController@storeJurnal');
Route::resource('jurnal-pengeluaran-kas','JurnalPengeluaranKasController');

Route::get('jurnal-penyusutan-aktiva-tetap/index', 'JurnalPenyusutanAktivaTetapController@index');
Route::post('jurnal-penyusutan-aktiva-tetap/rekapitulasi', 'JurnalPenyusutanAktivaTetapController@rekapitulasi');
Route::get('jurnal-penyusutan-aktiva-tetap/rekapitulasi', 'JurnalPenyusutanAktivaTetapController@rekapitulasi');
Route::post('jurnal-penyusutan-aktiva-tetap/jurnal', 'JurnalPenyusutanAktivaTetapController@jurnal');
Route::get('jurnal-penyusutan-aktiva-tetap/jurnal', 'JurnalPenyusutanAktivaTetapController@jurnal');
Route::post('/simpan-jurnal-penyusutan-aktiva-tetap', 'JurnalPenyusutanAktivaTetapController@simpan');

Route::get('laporan-jurnal-umum/index', 'LaporanJurnalUmumController@index');
Route::post('laporan-jurnal-umum/laporan', 'LaporanJurnalUmumController@laporan');
Route::get('laporan-jurnal-umum/voucher/{kode_jurnal}', 'LaporanJurnalUmumController@voucher');
route::get('laporan-jurnal-umum/edit/{id_jurnal}', 'LaporanJurnalUmumController@edit');
route::get('laporan-jurnal-umum/detail/{id_jurnal}', 'LaporanJurnalUmumController@detail');
Route::get('laporan-jurnal-umum/balance', 'LaporanJurnalUmumController@balance');
Route::get('laporan-jurnal-umum/cetak/{kode_jurnal}/{tanggal_mulai}/{tanggal_selesai}', 'LaporanJurnalUmumController@cetak');
Route::post('/update-laporan-jurnal-umum', 'LaporanJurnalUmumController@update');

Route::get('laporan-buku-besar/index', 'LaporanBukuBesarController@index');
Route::get('laporan-buku-besar/laporan', 'LaporanBukuBesarController@laporan');
Route::post('laporan-buku-besar/laporan', 'LaporanBukuBesarController@laporan');

Route::get('laporan-neraca-saldo/index', 'LaporanNeracaSaldoController@index');
Route::get('laporan-neraca-saldo/laporan', 'LaporanNeracaSaldoController@laporan');
Route::post('laporan-neraca-saldo/laporan', 'LaporanNeracaSaldoController@laporan');

Route::get('kategori-barang/load-data','KategoriBarangController@loadData');
Route::resource('kategori-barang','KategoriBarangController');
Route::delete('kategori-barang/{id}/restore','KategoriBarangController@restore');

Route::get('sub-kategori-barang/load-data','SubKategoriBarangController@loadData');
Route::resource('sub-kategori-barang','SubKategoriBarangController');
Route::delete('sub-kategori-barang/{id}/restore','SubKategoriBarangController@restore');

Route::get('barang/load-data','BarangController@loadData');
Route::resource('barang','BarangController');
Route::delete('barang/{id}/restore','BarangController@restore');

Route::get('packing-barang/load-data','PackingBarangController@loadData');
Route::resource('packing-barang','PackingBarangController');
Route::delete('packing-barang/{id}/restore','PackingBarangController@restore');

Route::get('log-stok/load-data','LogStokController@loadData');
Route::resource('log-stok','LogStokController');
Route::delete('log-stok/{id}/restore','LogStokController@restore');
Route::get('log-stok/isibarcode/{barcode}', 'LogStokController@barcode')->name('isibarcode');
Route::get('log-stok/rekapitulasi', 'LogStokController@cari');
Route::post('log-stok/rekapitulasi', 'LogStokController@cari');

Route::get('penagihan-piutang-pasien/index', 'PenagihanPiutangPasienController@index');
Route::get('penagihan-piutang-pasien/rekapitulasi', 'PenagihanPiutangPasienController@rekapitulasi');
Route::post('penagihan-piutang-pasien/rekapitulasi', 'PenagihanPiutangPasienController@rekapitulasi');
route::get('edit-penagihan-piutang-pasien', 'PenagihanPiutangPasienController@edit')->name('edit-penagihan-piutang-pasien');
Route::post('/update-penagihan-piutang-pasien', 'PenagihanPiutangPasienController@update')->name('update-penagihan-piutang-pasien');

Route::get('jurnal-umum/index', 'JurnalUmumController@index');
Route::get('jurnal-umum/isiPerkiraan/{perkiraan}', 'JurnalUmumController@isiPerkiraan')->name('isiPerkiraan');
Route::get('jurnal-umum/isiKodeJurnal/{tipe_jurnal}', 'JurnalUmumController@isiKodeJurnal')->name('isiKodeJurnal');
Route::post('/simpan-jurnal-umum', 'JurnalUmumController@simpan');

Route::get('worksheet/index', 'WorkSheetController@index');
Route::get('worksheet/laporan', 'WorkSheetController@laporan');
Route::post('worksheet/laporan', 'WorkSheetController@laporan');

Route::get('laporan-laba-rugi/index', 'LaporanLabaRugiController@index');
Route::get('laporan-laba-rugi/laporan', 'LaporanLabaRugiController@labaRugi');
Route::post('laporan-laba-rugi/laporan', 'LaporanLabaRugiController@labaRugi');

Route::get('laporan-perubahan-ekuitas/index', 'LaporanPerubahanEkuitasController@index');
Route::get('laporan-perubahan-ekuitas/laporan', 'LaporanPerubahanEkuitasController@laporan');
Route::post('laporan-perubahan-ekuitas/laporan', 'LaporanPerubahanEkuitasController@laporan');

Route::get('laporan-neraca/index', 'LaporanNeracaController@index');
Route::get('laporan-neraca/laporan', 'LaporanNeracaController@laporan');
Route::post('laporan-neraca/laporan', 'LaporanNeracaController@laporan');

Route::get('laporan-neraca-saldo-setelah-penutupan/index', 'LaporanNeracaSaldoSetelahPenutupanController@index');
Route::get('laporan-neraca-saldo-setelah-penutupan/laporan', 'LaporanNeracaSaldoSetelahPenutupanController@laporan');
Route::post('laporan-neraca-saldo-setelah-penutupan/laporan', 'LaporanNeracaSaldoSetelahPenutupanController@laporan');

Route::get('deposit/load-data','DepositController@loadData');
Route::resource('deposit','DepositController');
Route::delete('deposit/{id}/restore','DepositController@restore');
Route::get('deposit/isiPasienDeposit/{id_pelanggan}', 'DepositController@isiPasienDeposit')->name('isiPasienDeposit');
Route::get('deposit/edit-deposit/{id}', 'DepositController@editDeposit');
Route::post('/update-deposit', 'DepositController@updateDeposit');

Route::get('jurnal-deposit/index', 'JurnalDepositController@index');
Route::get('jurnal-deposit/rekapitulasi', 'JurnalDepositController@rekapitulasi');
Route::post('jurnal-deposit/rekapitulasi', 'JurnalDepositController@rekapitulasi');
Route::get('jurnal-deposit/jurnal-umum/{id}', 'JurnalDepositController@jurnal');
Route::post('/simpan-jurnal-deposit', 'JurnalDepositController@simpan');

//route::get('edit-setting-pendapatan-jasa', 'AkunPendapatanJasaController@edit')->name('edit-setting-pendapatan-jasa');
//Route::post('/update-setting-coa-pendapatan-jasa', 'AkunPendapatanJasaController@UpdateJasa')->name('update-setting-coa-pendapatan-jasa');

Route::get('setting-coa-pembayaran-oleh-pasien/index', 'SettingCoaPembayaranOlehPasienController@index');
route::get('edit-setting-coa-pembayaran-oleh-pasien', 'SettingCoaPembayaranOlehPasienController@edit')->name('edit-setting-coa-pembayaran-oleh-pasien');
Route::post('/update-setting-coa-pembayaran-oleh-pasien', 'SettingCoaPembayaranOlehPasienController@update')->name('update-setting-coa-pembayaran-oleh-pasien');

Route::get('setting-aplikasi/load-data','SettingAplikasiController@loadData');
Route::resource('setting-aplikasi','SettingAplikasiController');
Route::delete('setting-aplikasi/{id}/restore','SettingAplikasiController@restore');
Route::post('/simpan-setting-aplikasi', 'SettingAplikasiController@store');
Route::post('/update-setting-aplikasi', 'SettingAplikasiController@update');

Route::get('setting-email/load-data','SettingEmailController@loadData');
Route::resource('setting-email','SettingEmailController');
Route::delete('setting-email/{id}/restore','SettingEmailController@restore');

Route::get('setting-pusher/load-data','SettingPusherController@loadData');
Route::resource('setting-pusher','SettingPusherController');
Route::delete('setting-pusher/{id}/restore','SettingPusherController@restore');

Route::get('jenis-instansi-relasi/load-data','JenisInstansiRelasiController@loadData');
Route::resource('jenis-instansi-relasi','JenisInstansiRelasiController');
Route::delete('jenis-instansi-relasi/{id}/restore','JenisInstansiRelasiController@restore');
Route::get('jenis-instansi-relasi/{id}/detail','JenisInstansiRelasiController@detail');

Route::get('pindah-perkiraan/index', 'PindahPerkiraanController@index');
Route::get('pindah-perkiraan/pencarian', 'PindahPerkiraanController@index');
Route::get('pindah-perkiraan/pencarian', 'PindahPerkiraanController@pencarian');
Route::post('pindah-perkiraan/pencarian', 'PindahPerkiraanController@pencarian');
Route::post('/proses-konversi', 'PindahPerkiraanController@konversi');

Route::get('role/load-data','RoleController@loadData');
Route::resource('role','RoleController');
Route::delete('role/{id}/restore','RoleController@restore');

Route::get('setting-perusahaan/load-data','SettingPerusahaanController@loadData');
Route::resource('setting-perusahaan','SettingPerusahaanController');
Route::delete('setting-perusahaan/{id}/restore','SettingPerusahaanController@restore');
Route::get('setting-perusahaan/deactivate/{id}', 'SettingPerusahaanController@deactivate');
Route::get('setting-perusahaan/activate/{id}', 'SettingPerusahaanController@activate');
Route::post('/simpan-setting-perusahaan', 'SettingPerusahaanController@store');
Route::post('/update-setting-perusahaan', 'SettingPerusahaanController@update');

Route::get('laporan-arus-kas','LaporanArusKasController@index');
Route::post('laporan-arus-kas/pencarian','LaporanArusKasController@pencarian');
Route::get('laporan-arus-kas/pencarian','LaporanArusKasController@pencarian');

Route::get('aktiva-tetap/load-data','AktivaTetapController@loadData');
Route::resource('aktiva-tetap','AktivaTetapController');
Route::delete('aktiva-tetap/{id}/restore','AktivaTetapController@restore');

Route::get('setting-coa-tarif/index', 'SettingCoaTarifController@index');
Route::get('setting-coa-tarif/rawat-jalan', 'SettingCoaTarifController@RawatJalan');
Route::get('setting-coa-tarif/rawat-inap', 'SettingCoaTarifController@RawatInap');
route::get('edit-setting-coa-tarif', 'SettingCoaTarifController@edit')->name('edit-setting-coa-tarif');
Route::post('/simpan-setting-tarif', 'SettingCoaTarifController@SimpanSettingTarif');
Route::post('/update-setting-coa-tarif', 'SettingCoaTarifController@update')->name('update-setting-coa-tarif');
Route::post('setting-coa-tarif/cari-setting-tarif', 'SettingCoaTarifController@cari');
Route::get('setting-coa-tarif/cari-setting-tarif', 'SettingCoaTarifController@cari');

Route::get('arus-ka/load-data','ArusKaController@loadData');
Route::resource('arus-ka','ArusKaController');
Route::delete('arus-ka/{id}/restore','ArusKaController@restore');
Route::get('arus-ka/isiArusKa/{id_induk}', 'ArusKaController@isi')->name('isiArusKa');

Route::get('syarat-penggajuan-anggaran/index', 'SyaratPenggajuanAnggaranController@index');
Route::post('/simpan-syarat-penggajuan-anggaran', 'SyaratPenggajuanAnggaranController@simpan');

Route::get('akun-anggaran/load-data','AkunAnggaranController@loadData');
Route::resource('akun-anggaran','AkunAnggaranController');
Route::delete('akun-anggaran/{id}/restore','AkunAnggaranController@restore');
Route::get('akun-anggaran/cekAkunAnggaran/{kode}', 'AkunAnggaranController@cekKode')->name('cekAkunAnggaran');

Route::get('item/load-data','ItemController@loadData');
Route::resource('item','ItemController');
Route::delete('item/{id}/restore','ItemController@restore');

Route::get('bukti-bayar/load-data','BuktiBayarController@loadData');
Route::resource('bukti-bayar','BuktiBayarController');
Route::delete('bukti-bayar/{id}/restore','BuktiBayarController@restore');

Route::resource('setting-coa-invoice','SettingCoaInvoiceController');

Route::get('info-pembayaran-invoice/load-data','InfoPembayaranInvoiceController@loadData');
Route::resource('info-pembayaran-invoice','InfoPembayaranInvoiceController');
Route::delete('info-pembayaran-invoice/{id}/restore','InfoPembayaranInvoiceController@restore');

Route::get('invoice/load-data','InvoiceController@loadData');
Route::resource('invoice','InvoiceController');
Route::delete('invoice/{id}/restore','InvoiceController@restore');

Route::get('jurnal-invoice','JurnalInvoiceController@index');
Route::get('jurnal-invoice/load-data','JurnalInvoiceController@loadData');
Route::get('jurnal-invoice/create-jurnal/{id}','JurnalInvoiceController@createJurnal');
Route::post('jurnal-invoice/store-jurnal','JurnalInvoiceController@storeJurnal');

Route::get('pembayaran-invoice/load-data','PembayaranInvoiceController@loadData');
Route::resource('pembayaran-invoice','PembayaranInvoiceController');
Route::delete('pembayaran-invoice/{id}/restore','PembayaranInvoiceController@restore');
Route::get('pembayaran-invoice/pembayaran/{id}', 'PembayaranInvoiceController@pembayaran');
Route::post('/simpan-pembayaran-invoice', 'PembayaranInvoiceController@save');

Route::get('jurnal-pembayaran-invoice/index', 'JurnalPembayaranInvoiceController@index');
Route::get('jurnal-pembayaran-invoice/rekapitulasi', 'JurnalPembayaranInvoiceController@rekapitulasi');
Route::post('jurnal-pembayaran-invoice/rekapitulasi', 'JurnalPembayaranInvoiceController@rekapitulasi');
Route::get('jurnal-pembayaran-invoice/jurnal-umum/{id}', 'JurnalPembayaranInvoiceController@jurnal');
Route::post('/simpan-jurnal-pembayaran-invoice', 'JurnalPembayaranInvoiceController@simpan');

Route::get('setting-surplus-defisit/load-data','SettingSurplusDefisitController@loadData');
Route::resource('setting-surplus-defisit','SettingSurplusDefisitController');
Route::delete('setting-surplus-defisit/{id}/restore','SettingSurplusDefisitController@restore');
Route::get('setting-surplus-defisit/isiSurplusDefisit/{induk}', 'SettingSurplusDefisitController@isi')->name('isiSurplusDefisit');

Route::get('set-surplus-defisit-detail/load-data','SetSurplusDefisitDetailController@loadData');
Route::resource('set-surplus-defisit-detail','SetSurplusDefisitDetailController');
Route::delete('set-surplus-defisit-detail/{id}/restore','SetSurplusDefisitDetailController@restore');
Route::get('set-surplus-defisit-detail/detail/{id}', 'SetSurplusDefisitDetailController@detail');
Route::get('set-surplus-defisit-detail/form-tambah/{id}', 'SetSurplusDefisitDetailController@tambah');
Route::get('set-surplus-defisit-detail/form-edit/{id}', 'SetSurplusDefisitDetailController@edit');
Route::get('set-surplus-defisit-detail/isiUnit/{id_unit}', 'SetSurplusDefisitDetailController@isiUnit')->name('isiUnitSetSurplusDefisit');
Route::post('/update-set-surplus-defisit-detail', 'SetSurplusDefisitDetailController@update');
route::get('delete-set-surplus-defisit-detail', 'SetSurplusDefisitDetailController@delete')->name('delete-set-surplus-defisit-detail');
Route::post('/remove-set-surplus-defisit-detail', 'SetSurplusDefisitDetailController@remove')->name('remove-set-surplus-defisit-detail');

Route::get('laporan-surplus-defisit/index', 'LaporanSurplusDefisitController@index');
Route::post('laporan-surplus-defisit/laporan', 'LaporanSurplusDefisitController@laporan');
Route::get('laporan-surplus-defisit/laporan', 'LaporanSurplusDefisitController@laporan');

Route::get('setting-coa-jasa-pegawai/index', 'SettingCoaJasaPegawaiController@index');
Route::get('setting-coa-jasa-pegawai/tambah', 'SettingCoaJasaPegawaiController@tambah');
Route::post('/simpan-setting-coa-jasa-pegawai', 'SettingCoaJasaPegawaiController@simpan');
Route::get('setting-coa-jasa-pegawai/detail/{id}', 'SettingCoaJasaPegawaiController@detail');
route::get('edit', 'SettingCoaJasaPegawaiController@edit')->name('edit-setting-coa-jasa-pegawai');
route::get('delete-setting-coa-jasa-pegawai', 'SettingCoaJasaPegawaiController@delete')->name('delete-setting-coa-jasa-pegawai');
Route::post('/update-setting-coa-jasa-pegawai', 'SettingCoaJasaPegawaiController@update')->name('update-setting-coa-jasa-pegawai');
Route::post('/remove-setting-coa-jasa-pegawai', 'SettingCoaJasaPegawaiController@remove')->name('remove-setting-coa-jasa-pegawai');
Route::get('setting-coa-jasa-pegawai/cari', 'SettingCoaJasaPegawaiController@cari');
Route::post('setting-coa-jasa-pegawai/cari', 'SettingCoaJasaPegawaiController@cari');

Route::get('laporan-payroll/index', 'LaporanPayrollController@index');
Route::get('laporan-payroll/laporan', 'LaporanPayrollController@laporan');
Route::post('laporan-payroll/laporan', 'LaporanPayrollController@laporan');
Route::get('laporan-payroll/detail/{id}', 'LaporanPayrollController@detail');
Route::post('/verifikasi-laporan-payroll', 'LaporanPayrollController@verifikasi');

Route::get('set-neraca/load-data','SetNeracaController@loadData');
Route::resource('set-neraca','SetNeracaController');
Route::delete('set-neraca/{id}/restore','SetNeracaController@restore');
Route::get('set-neraca/isiSetNeraca/{induk}', 'SetNeracaController@isi')->name('isiSetNeraca');

Route::get('set-neraca-detail/load-data','SetNeracaDetailController@loadData');
Route::get('set-neraca-detail/lod-data','SetNeracaDetailController@lodData');
Route::resource('set-neraca-detail','SetNeracaDetailController');
Route::delete('set-neraca-detail/{id}/restore','SetNeracaDetailController@restore');
Route::get('set-neraca-detail/detail-neraca/{id}', 'SetNeracaDetailController@detail');
Route::get('set-neraca-detail/form-tambah/{id}', 'SetNeracaDetailController@tambah');
Route::get('set-neraca-detail/form-edit/{id}', 'SetNeracaDetailController@edit');
Route::post('/update-set-neraca-detail', 'SetNeracaDetailController@update');
route::get('delete', 'SetNeracaDetailController@delete')->name('delete-set-neraca-detail');
Route::post('/remove-set-neraca-detail', 'SetNeracaDetailController@destroy')->name('remove-set-neraca-detail');

Route::get('set-lap-ekuita/load-data','SetLapEkuitaController@loadData');
Route::resource('set-lap-ekuita','SetLapEkuitaController');
Route::delete('set-lap-ekuita/{id}/restore','SetLapEkuitaController@restore');
Route::get('set-lap-ekuita/isiSetLapEkuitas/{induk}', 'SetLapEkuitaController@isi')->name('isiSetLapEkuitas');

Route::get('setting-perubahan-ekuitas/index', 'SettingPerubahanEkuitasController@index');
Route::get('setting-perubahan-ekuitas/detail/{id}', 'SettingPerubahanEkuitasController@detail');
Route::get('setting-perubahan-ekuitas/form-tambah/{id}', 'SettingPerubahanEkuitasController@tambah');
Route::post('/simpan-perubahan-ekuitas', 'SettingPerubahanEkuitasController@store');
Route::get('setting-perubahan-ekuitas/form-edit/{id}', 'SettingPerubahanEkuitasController@edit');
Route::post('/update-perubahan-ekuitas', 'SettingPerubahanEkuitasController@update');
route::get('delete-set-neraca-detail', 'SettingPerubahanEkuitasController@delete')->name('delete-perubahan-ekuitas');
Route::post('/remove-setting-perubahan-ekuitas', 'SettingperubahanEkuitasController@destroy')->name('remove-perubahan-ekuitas');

Route::get('saldo-awal/index', 'SaldoAwalController@index');
Route::post('saldo-awal/laporan', 'SaldoAwalController@laporan');
Route::get('saldo-awal/form', 'SaldoAwalController@create');
Route::get('saldo-awal/form/{id}', 'SaldoAwalController@edit');
Route::post('/simpan-saldo-awal', 'SaldoAwalController@store');
Route::post('/update-saldo-awal', 'SaldoAwalController@update');
route::get('delete-saldo-awal', 'SaldoAwalController@delete')->name('delete-saldo-awal');
Route::post('/remove-saldo-awal', 'SaldoAwalController@destroy')->name('remove-saldo-awal');

Route::get('setting-rumus-neraca/index','SettingRumusNeracaController@index');
Route::get('setting-rumus-neraca/detail/{id}', 'SettingRumusNeracaController@detail');
Route::get('setting-rumus-neraca/form-tambah/{id}', 'SettingRumusNeracaController@tambah');
Route::get('setting-rumus-neraca/form-edit/{id}', 'SettingRumusNeracaController@edit');
Route::post('/update-setting-rumus-neraca', 'SettingRumusNeracaController@update');
Route::post('/simpan-setting-rumus-neraca', 'SettingRumusNeracaController@store');
route::get('delete-setting-rumus-neraca', 'SettingRumusNeracaController@delete')->name('delete-setting-rumus-neraca');
Route::post('/remove-setting-rumus-neraca', 'SettingRumusNeracaController@destroy')->name('remove-setting-rumus-neraca');

Route::get('mutasi-jurnal/index', 'MutasiJurnalController@index');
Route::post('mutasi-jurnal/laporan', 'MutasiJurnalController@laporan');
Route::get('mutasi-jurnal/laporan', 'MutasiJurnalController@laporan');

Route::get('neraca-laporan/index', 'NeracaLaporanController@index');
Route::post('neraca-laporan/laporan', 'NeracaLaporanController@laporan');
Route::get('neraca-laporan/laporan', 'NeracaLaporanController@laporan');
Route::post('neraca-laporan/export', 'NeracaLaporanController@Export');

Route::get('upload-jurnal/index', 'UploadJurnalController@index');
Route::post('/simpan-upload-jurnal', 'UploadJurnalController@store');
Route::get('/dowload-format-jurnal', 'UploadJurnalController@dowload');

Route::get('upload-aktiva-tetap/index', 'UploadAktivaTetapController@index');
Route::post('/simpan-upload-aktiva-tetap', 'UploadAktivaTetapController@store');
Route::get('/dowload-aktiva-tetap', 'UploadAktivaTetapController@dowload');

Route::get('mutasi-penerimaan-kas/index', 'MutasiPenerimaanKasController@index');
Route::post('mutasi-penerimaan-kas/pencarian', 'MutasiPenerimaanKasController@pencarian');
Route::get('mutasi-penerimaan-kas/pencarian', 'MutasiPenerimaanKasController@pencarian');
Route::get('mutasi-penerimaan-kas/verifikasi/{id}', 'MutasiPenerimaanKasController@verifikasi');
Route::post('/verifikasi-mutasi-penerimaan-kas', 'MutasiPenerimaanKasController@simpan');
Route::get('mutasi-penerimaan-kas/form', 'MutasiPenerimaanKasController@create');
Route::post('/simpan-mutasi-penerimaan-kas', 'MutasiPenerimaanKasController@store');
Route::get('mutasi-penerimaan-kas/detail/{id}', 'MutasiPenerimaanKasController@detail');
Route::get('mutasi-penerimaan-kas/id_pembayaran/{id_pembayaran}', 'MutasiPenerimaanKasController@autocomplete');
route::get('edit-mutasi-penerimaan-kas', 'MutasiPenerimaanKasController@edit')->name('edit-mutasi-penerimaan-kas');
Route::post('/update-mutasi-penerimaan-kas', 'MutasiPenerimaanKasController@update')->name('update-mutasi-penerimaan-kas');
route::get('delete-mutasi-penerimaan-kas', 'MutasiPenerimaanKasController@delete')->name('delete-mutasi-penerimaan-kas');
Route::post('/remove-mutasi-penerimaan-kas', 'MutasiPenerimaanKasController@destroy')->name('remove-mutasi-penerimaan-kas');
Route::get('mutasi-penerimaan-kas/isiRekeningMasuk/{id_tarif_pajak}', 'MutasiPenerimaanKasController@isi')->name('isiRekeningMasuk');
Route::get('mutasi-penerimaan-kas/bukti-transaksi-kas-masuk/{id_mutasi_kas}', 'MutasiPenerimaanKasController@buktiKasMasuk');
Route::get('mutasi-penerimaan-kas/lihat-jurnal-kas-masuk/{id_mutasi_kas}', 'MutasiPenerimaanKasController@lihatJurnal');
route::get('verif-mutasi-penerimaan-kas', 'MutasiPenerimaanKasController@verif')->name('verif-mutasi-penerimaan-kas');
Route::post('/verifikasi-mutasi-penerimaan-kas', 'MutasiPenerimaanKasController@verifikasi')->name('verifikasi-mutasi-penerimaan-kas');

Route::get('mutasi-penerimaan-kas/bukti-transaksi/{id}', 'MutasiPenerimaanKasController@bukti');


Route::get('mutasi-pengeluaran-kas/index', 'MutasiPengeluaranKasController@index');
Route::post('mutasi-pengeluaran-kas/pencarian', 'MutasiPengeluaranKasController@pencarian');
Route::get('mutasi-pengeluaran-kas/pencarian', 'MutasiPengeluaranKasController@pencarian');
Route::get('mutasi-pengeluaran-kas/form', 'MutasiPengeluaranKasController@create');
Route::post('/simpan-mutasi-pengeluaran-kas', 'MutasiPengeluaranKasController@store');
Route::get('mutasi-pengeluaran-kas/detail/{id}', 'MutasiPengeluaranKasController@detail');
Route::get('mutasi-pengeluaran-kas/id_pembayaran/{id_pembayaran}', 'MutasiPengeluaranKasController@autocomplete');
route::get('edit-mutasi-pengeluaran-kas', 'MutasiPengeluaranKasController@edit')->name('edit-mutasi-pengeluaran-kas');
Route::post('/update-mutasi-pengeluaran-kas', 'MutasiPengeluaranKasController@update')->name('update-mutasi-pengeluaran-kas');
route::get('delete-mutasi-pengeluaran-kas', 'MutasiPengeluaranKasController@delete')->name('delete-mutasi-pengeluaran-kas');
Route::post('/remove-mutasi-pengeluaran-kas', 'MutasiPengeluaranKasController@destroy')->name('remove-mutasi-pengeluaran-kas');
Route::get('mutasi-pengeluaran-kas/verifikasi/{id}', 'MutasiPengeluaranKasController@verifikasi');
Route::post('/verifikasi-mutasi-pengeluaran-kas', 'MutasiPengeluaranKasController@simpan');
Route::get('mutasi-pengeluaran-kas/isiRekening/{id_tarif_pajak}', 'MutasiPengeluaranKasController@isi')->name('isiRekening');
Route::get('mutasi-pengeluaran-kas/bukti-transaksi-kas-keluar/{id_mutasi_kas}', 'MutasiPengeluaranKasController@buktiKasKeluar');
Route::get('mutasi-pengeluaran-kas/lihat-jurnal-kas-keluar/{id_mutasi_kas}', 'MutasiPengeluaranKasController@lihatJurnal');
route::get('verif-mutasi-pengeluaran-kas', 'MutasiPengeluaranKasController@verif')->name('verif-mutasi-pengeluaran-kas');
Route::post('/verifikasi-mutasi-pengeluaran-kas', 'MutasiPengeluaranKasController@verifikasi')->name('verifikasi-mutasi-pengeluaran-kas');
Route::get('mutasi-pengeluaran-kas/bukti-transaksi/{id}', 'MutasiPengeluaranKasController@bukti');


route::get('delete-mutasi-pengeluaran-kas', 'MutasiPengeluaranKasController@delete')->name('verif-mutasi-pengeluaran-kas');
Route::post('/remove-mutasi-pengeluaran-kas', 'MutasiPengeluaranKasController@destroy')->name('verifikasi-mutasi-pengeluaran-kas');

Route::get('arus-kas-detail/load-data','ArusKasDetailController@loadData');
Route::resource('arus-kas-detail','ArusKasDetailController');
Route::get('arus-kas-detail/isiArusKas/{id_induk}', 'ArusKasDetailController@isi')->name('isiArusKas');
Route::get('arus-kas-detail/detail/{id}', 'ArusKasDetailController@detail');
route::get('delete-arus-kas-detail', 'ArusKasDetailController@delete')->name('delete-arus-kas');
Route::post('/remove-arus-kas-detail', 'ArusKasDetailController@destroy')->name('remove-arus-kas');
Route::get('arus-kas-detail/form/{id}', 'ArusKasDetailController@tambah');
Route::get('arus-kas-detail/edit/{id}', 'ArusKasDetailController@edit');
Route::post('/update-arus-kas-detail', 'ArusKasDetailController@update');

Route::get('arus-kas-rumus/load-data','ArusKasRumusController@loadData');
Route::resource('arus-kas-rumus','ArusKasRumusController');
Route::get('arus-kas-rumus/detail/{id}', 'ArusKasRumusController@detail');
route::get('delete-arus-kas-rumus', 'ArusKasRumusController@delete')->name('delete-rumus-arus-kas');
Route::post('/remove-arus-kas-rumus', 'ArusKasRumusController@destroy')->name('remove-rumus-arus-kas');
Route::get('arus-kas-rumus/form/{id}', 'ArusKasRumusController@tambah');
Route::get('arus-kas-rumus/edit/{id}', 'ArusKasRumusController@edit');
Route::post('/update-arus-kas-rumus', 'ArusKasRumusController@update');

Route::get('transaksi-arus-kas/index', 'TransaksiArusKasController@index');
Route::post('transaksi-arus-kas/pencarian', 'TransaksiArusKasController@pencarian');
Route::get('transaksi-arus-kas/pencarian', 'TransaksiArusKasController@pencarian');
Route::get('transaksi-arus-kas/verifikasi', 'TransaksiArusKasController@verifikasi');
Route::post('transaksi-arus-kas/pencarian-verifikasi', 'TransaksiArusKasController@pencarianVerifikasi');
Route::get('transaksi-arus-kas/pencarian-verifikasi', 'TransaksiArusKasController@pencarianVerifikasi');
Route::post('/update-verifikasi-transaksi-arus-kas', 'TransaksiArusKasController@updateVerifikasi');
Route::get('transaksi-arus-kas/form', 'TransaksiArusKasController@create');
Route::post('/simpan-transaksi-arus-kas', 'TransaksiArusKasController@store');
Route::get('transaksi-arus-kas/detail/{id}', 'TransaksiArusKasController@detail');
Route::get('transaksi-arus-kas/kode/{tipe}', 'TransaksiArusKasController@isiKode')->name('isiKodeBkm');
Route::get('transaksi-arus-kas/id_pembayaran/{id_pembayaran}', 'TransaksiArusKasController@autocomplete');

Route::get('setting-coa-payroll/index', 'SettingCoaPayrollController@index');
Route::post('setting-coa-payroll/pencarian', 'SettingCoaPayrollController@pencarian');
Route::get('setting-coa-payroll/pencarian', 'SettingCoaPayrollController@pencarian');
route::get('edit-setting-coa-payroll', 'SettingCoaPayrollController@edit')->name('edit-setting-coa-payroll');
Route::post('/update-setting-coa-payroll', 'SettingCoaPayrollController@update')->name('update-setting-coa-payroll');
route::get('edit-setting-coa-payroll-dua', 'SettingCoaPayrollController@editDua')->name('edit-setting-coa-payroll-dua');
Route::post('/update-setting-coa-payroll-dua', 'SettingCoaPayrollController@updateDua')->name('update-setting-coa-payroll-dua');
Route::get('setting-coa-payroll/pajak-dan-biaya-adm', 'SettingCoaPayrollController@setCoa');

Route::get('transaksi-payroll/index', 'TransaksiPayrollController@index');
Route::post('transaksi-payroll/pencarian', 'TransaksiPayrollController@pencarian');
Route::get('transaksi-payroll/pencarian', 'TransaksiPayrollController@pencarian');
Route::get('transaksi-payroll/detail/{id}', 'TransaksiPayrollController@detail');

Route::post('transaksi-payroll/jurnal', 'TransaksiPayrollController@jurnal');
Route::get('transaksi-payroll/jurnal', 'TransaksiPayrollController@jurnal');

Route::post('/simpan-jurnal-transaksi-payroll', 'TransaksiPayrollController@store');

Route::get('sinkronasi-data-payroll', 'SinkronasiDataPayrollController@index');
Route::get('sinkronasi-data-payroll/get-data-payroll', 'SinkronasiDataPayrollController@getDataPayroll');
Route::get('sinkronasi-data-payroll/get-data-payroll-detail', 'SinkronasiDataPayrollController@getDataPayrollDetail');
Route::post('sinkronasi-data-payroll/sinkron-payroll', 'SinkronasiDataPayrollController@sinkronPayroll');

Route::get('laporan-profit-center/index', 'LaporanProfitCenterController@index');
Route::get('laporan-profit-center/pencarian', 'LaporanProfitCenterController@pencarian');
Route::post('laporan-profit-center/pencarian', 'LaporanProfitCenterController@pencarian');

Route::get('sales-report/index', 'SalesReportController@index');
Route::post('sales-report/pencarian', 'SalesReportController@pencarian');
Route::get('sales-report/pencarian', 'SalesReportController@pencarian');
Route::get('sales-report/form', 'SalesReportController@create');
Route::post('/simpan-sales-report-detail', 'SalesReportController@store');

Route::get('summary-profit-center/index', 'SummaryProfitCenterController@index');
Route::get('summary-profit-center/pencarian', 'SummaryProfitCenterController@pencarian');
Route::post('summary-profit-center/pencarian', 'SummaryProfitCenterController@pencarian');

Route::get('surplus-defisit/load-data','SurplusDefisitController@loadData');
Route::resource('surplus-defisit','SurplusDefisitController');
Route::delete('surplus-defisit/{id}/restore','SurplusDefisitController@restore');
Route::get('surplus-defisit/cekNamaSurplusDefisit/{nama}', 'SurplusDefisitController@cek')->name('cekNamaSurplusDefisit');

Route::get('surplus-defisit-detail/load-data','SurplusDefisitDetailController@loadData');
Route::resource('surplus-defisit-detail','SurplusDefisitDetailController');
Route::delete('surplus-defisit-detail/{id}/restore','SurplusDefisitDetailController@restore');
Route::get('surplus-defisit-detail/cekNamaSurplusDefisitDetail/{nama}', 'SurplusDefisitDetailController@cek')->name('cekNamaSurplusDefisitDetail');

Route::get('surplus-defisit-unit/load-data','SurplusDefisitUnitController@loadData');
Route::resource('surplus-defisit-unit','SurplusDefisitUnitController');
Route::delete('surplus-defisit-unit/{id}/restore','SurplusDefisitUnitController@restore');
Route::get('surplus-defisit-unit/detail/{id}', 'SurplusDefisitUnitController@detail');
route::get('delete-surplus-defisit-unit', 'SurplusDefisitUnitController@delete')->name('delete-surplus-defisit-unit');
Route::post('/remove-surplus-defisit-unit', 'SurplusDefisitUnitController@hapus')->name('remove-surplus-defisit-unit');
route::get('edit-surplus-defisit-unit', 'SurplusDefisitUnitController@editt')->name('edit-surplus-defisit-unit');
Route::post('/update-surplus-defisit-unit', 'SurplusDefisitUnitController@apdet')->name('update-surplus-defisit-unit');

Route::get('surplus-defisit-rek/load-data','SurplusDefisitRekController@loadData');
Route::post('/cari-detail-setting-rekening-pl','SurplusDefisitRekController@cari');
Route::resource('surplus-defisit-rek','SurplusDefisitRekController');
Route::get('surplus-defisit-rek/detail/{id}', 'SurplusDefisitRekController@detail');
Route::delete('surplus-defisit-rek/{id}/restore','SurplusDefisitRekController@restore');
route::get('delete-surplus-defisit-rek', 'SurplusDefisitRekController@delete')->name('delete-surplus-defisit-rek');
Route::post('/remove-surplus-defisit-rek', 'SurplusDefisitRekController@hapus')->name('remove-surplus-defisit-rek');
route::get('tambah-surplus-defisit-rek', 'SurplusDefisitRekController@tambah')->name('tambah-surplus-defisit-rek');


Route::get('informasi-setting-rekening-pl/index', 'InformasiSettingRekeningPlController@index');
route::get('informasi-setting-rekening-pl/form/{id}', 'InformasiSettingRekeningPlController@tambah');
Route::post('/simpan-informasi-setting-rekening-pl', 'InformasiSettingRekeningPlController@store');
Route::post('informasi-setting-rekening-pl/pencarian', 'InformasiSettingRekeningPlController@pencarian');
Route::get('informasi-setting-rekening-pl/pencarian', 'InformasiSettingRekeningPlController@pencarian');

Route::get('informasi-setting-unit-pl/index', 'InformasiSettingUnitPlController@index');
route::get('informasi-setting-unit-pl/form/{id}', 'InformasiSettingUnitPlController@tambah');
Route::post('/simpan-informasi-setting-unit-pl', 'InformasiSettingUnitPlController@store');
Route::post('informasi-setting-unit-pl/pencarian', 'InformasiSettingUnitPlController@pencarian');
Route::get('informasi-setting-unit-pl/pencarian', 'InformasiSettingUnitPlController@pencarian');

Route::get('informasi-setting-coa-neraca/index', 'InformasiSettingCoaNeracaController@index');
route::get('informasi-setting-coa-neraca/form/{id}', 'InformasiSettingCoaNeracaController@tambah');
Route::post('/simpan-informasi-setting-coa-neraca', 'InformasiSettingCoaNeracaController@store');
Route::post('informasi-setting-coa-neraca/pencarian', 'InformasiSettingCoaNeracaController@pencarian');
Route::get('informasi-setting-coa-neraca/pencarian', 'InformasiSettingCoaNeracaController@pencarian');

//lihat voucher dari laporan jurnal umum
//Route::get('mutasi-penerimaan-kas/bukti-transaksi/{id_jurnal}', 'MutasiPenerimaanKasController@bukti');
//Route::get('mutasi-pengeluaran-kas/bukti-transaksi/{id_jurnal}', 'MutasiPengeluaranKasController@bukti');

Route::get('informasi-setting-pl/index', 'InformasiSettingPlController@index');

Route::get('laporan-pl-cc/index', 'LaporanPlCCController@index');
Route::get('laporan-pl-cc/laporan', 'LaporanPlCCController@laporan');
Route::post('laporan-pl-cc/laporan', 'LaporanPlCCController@laporan');

Route::get('setting-cash-flow/index', 'SettingCashFlowController@index');
Route::get('setting-cash-flow/form', 'SettingCashFlowController@create');
Route::get('setting-cash-flow/isiCashFlow/{induk}', 'SettingCashFlowController@isi')->name('isiCashFlow');
Route::post('/simpan-setting-cash-flow', 'SettingCashFlowController@store');
Route::get('setting-cash-flow/form/{id}', 'SettingCashFlowController@edit');
Route::post('/update-setting-cash-flow', 'SettingCashFlowController@update');
route::get('delete-setting-cash-flow', 'SettingCashFlowController@delete')->name('delete-setting-cash-flow');
Route::post('/remove-setting-cash-flow', 'SettingCashFlowController@destroy')->name('remove-setting-cash-flow');

Route::get('laporan-cash-flow/index', 'LaporanCashFlowController@index');
Route::post('laporan-cash-flow/laporan', 'LaporanCashFlowController@laporan');
Route::get('laporan-cash-flow/laporan', 'LaporanCashFlowController@laporan');

Route::get('perubahan-ekuitas/index', 'PerubahanEkuitasController@index');
Route::get('perubahan-ekuitas/laporan', 'PerubahanEkuitasController@laporan');
Route::post('perubahan-ekuitas/laporan', 'PerubahanEkuitasController@laporan');

Route::get('manajemen-anggaran/index', 'ManajemenAnggaranController@index');
Route::post('/simpan-manajemen-anggaran', 'ManajemenAnggaranController@store');
