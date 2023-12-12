<?php

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use App\Models\Menu;
use App\Permission;
class MenuSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
	{
        $this->command->info('Delete semua tabel menu');
        Model::unguard();
        Menu::truncate();
        $this->menuAcl();
        $this->menuSetting();
        $this->menuMaster();
        $this->menuSaldoAwal();
        $this->menuRekeningKontrol();
        $this->menuAnggaran();
        $this->menuPayroll();
        $this->menuMutasiKas();
        $this->menuAktivaTetap();
        //$this->menuTransaksi();
        $this->menuJurnal();
        //$this->menuSistemInformasi();
        $this->menuLaporanKeuangan();
        //$this->menuPersediaan();
        //$this->menuNeraca();
        //$this->menuArusKas();
        //$this->menuSurplusDefisit();
        //$this->menuProfitCenter();
        //$this->menuLaporanSales();
        //$this->menuMasterDataPl();
        //$this->menuInformasiSettingPl();
        $this->menuUploadFile();
        //$this->menuInformasiSettingCoa();

    }

    private function menuAcl()
    {
        $this->command->info('Menu ACL Seeder');
        $permission = Permission::firstOrNew(array(
            'name'=>'read-acl-menu'
        ));

        $permission->display_name = 'Read ACL Menus';
        $permission->save();
        $menu = Menu::firstOrNew(array(
            'name'=>'ACL',
            'permission_id'=>$permission->id,
            'ordinal'=>1,
            'parent_status'=>'Y'
        ));

        $menu->icon = 'md-settings';
        $menu->save();

          //create SUBMENU master
        $permission = Permission::firstOrNew(array(
            'name'=>'read-users',
        ));
        $permission->display_name = 'Read Users';
        $permission->save();

        $submenu = Menu::firstOrNew(array(
            'name'=>'Users',
            'parent_id'=>$menu->id,
            'permission_id'=>$permission->id,
            'ordinal'=>2,
            'parent_status'=>'N',
            'url'=>'user',
            )
        );
        $submenu->save();

        $permission = Permission::firstOrNew(array(
            'name'=>'read-permissions',
        ));

        $permission->display_name = 'Read Permissions';
        $permission->save();

        $submenu = Menu::firstOrNew(array(
            'name'=>'Permissions',
            'parent_id'=>$menu->id,
            'permission_id'=>$permission->id,
            'ordinal'=>2,
            'parent_status'=>'N',
            'url'=>'permission',
            )
        );
        $submenu->save();

        $permission = Permission::firstOrNew(array(
            'name'=>'read-menus',
        ));
        $permission->display_name = 'Read Menus';
        $permission->save();

        $submenu = Menu::firstOrNew(array(
            'name'=>'Menus',
            'parent_id'=>$menu->id,
            'permission_id'=>$permission->id,
            'ordinal'=>2,
            'parent_status'=>'N',
            'url'=>'menu',
            )
        );
        $submenu->save();

        $permission = Permission::firstOrNew(array(
            'name'=>'read-roles',
        ));
        $permission->display_name = 'Read Role';
        $permission->save();

        $submenu = Menu::firstOrNew(array(
            'name'=>'Role',
            'parent_id'=>$menu->id,
            'permission_id'=>$permission->id,
            'ordinal'=>2,
            'parent_status'=>'N',
            'url'=>'role',
            )
        );
        $submenu->save();

    }

    private function menuSetting()
    {
        $this->command->info('Menu Setting');

        $permission = Permission::firstOrNew(array(
		    'name'=>'read-menu-setting',
        ));
	    $permission->display_name = 'Read Menu Setting';
        $permission->save();

        $menu = Menu::firstOrNew(array(
		    'name'          =>'Settings',
            'permission_id' =>$permission->id,
            'ordinal'       =>1,
            'parent_status' =>'Y'
        ));

        $menu->icon = 'md-folder';
        $menu->save();

        $permission = Permission::firstOrNew(array(
            'name'=>'read-setting-aplikasi',
        ));
        $permission->display_name = 'Read Setting Aplikasi';
        $permission->save();

        $submenu = Menu::firstOrNew(array(
            'name'          =>'Setting Aplikasi',
            'parent_id'     =>$menu->id,
            'permission_id' =>$permission->id,
            'ordinal'       =>2,
            'parent_status' =>'N',
            'url'           =>'setting-aplikasi',
            )
        );
        $submenu->save();  //tambah

        $permission = Permission::firstOrNew(array(
            'name'=>'read-setting-email',
        ));
        $permission->display_name = 'Read Setting Email';
        $permission->save();

        $submenu = Menu::firstOrNew(array(
            'name'          =>'Setting Email',
            'parent_id'     =>$menu->id,
            'permission_id' =>$permission->id,
            'ordinal'       =>2,
            'parent_status' =>'N',
            'url'           =>'setting-email',
            )
        );
        $submenu->save();  //tambah

        $permission = Permission::firstOrNew(array(
            'name'=>'read-setting-pusher',
        ));
        $permission->display_name = 'Read Setting Pusher';
        $permission->save();

        $submenu = Menu::firstOrNew(array(
            'name'          =>'Setting Pusher',
            'parent_id'     =>$menu->id,
            'permission_id' =>$permission->id,
            'ordinal'       =>2,
            'parent_status' =>'N',
            'url'           =>'setting-pusher',
            )
        );
        $submenu->save();  //tambah

        $permission = Permission::firstOrNew(array(
            'name'=>'read-setting-perusahaan',
        ));
        $permission->display_name = 'Read Setting Perusahaan';
        $permission->save();

        $submenu = Menu::firstOrNew(array(
            'name'          =>'Setting Perusahaan',
            'parent_id'     =>$menu->id,
            'permission_id' =>$permission->id,
            'ordinal'       =>2,
            'parent_status' =>'N',
            'url'           =>'setting-perusahaan',
            )
        );
        $submenu->save();  //tambah
    }

    private function menuMaster()
	{
        $this->command->info('Menu Master Seeder');
        $permission = Permission::firstOrNew(array(

		    'name'=>'read-master-menu'
        ));

	    $permission->display_name = 'Read Master Menus';
        $permission->save();
        $menu = Menu::firstOrNew(array(

		    'name'          =>'Master Data',
            'permission_id' =>$permission->id,
            'ordinal'       =>1,
            'parent_status' =>'Y'
        ));

        $menu->icon = 'md-folder';
        $menu->save();

        //sub menu informasi awal
        $permission = Permission::firstOrNew(array(
            'name'=>'read-informasi-awal',
        ));

        $permission->display_name = 'Read Menu Informasi Awal';
        $permission->save();

        $submenu = Menu::firstOrNew(array(
            'name'=>'Informasi Awal',
            'parent_id'=>$menu->id,
            'permission_id'=>$permission->id,
            'ordinal'=>2,
            'parent_status'=>'Y',)
        );

        $submenu->save();

        $permission = Permission::firstOrNew(array(
            'name'=>'read-setup-awal-periode',
        ));

        $permission->display_name = 'Setup Awal Periode';
        $permission->save();

        $submenus = Menu::firstOrNew(array(
            'name'          =>'Setup Awal Periode',
            'parent_id'     =>$submenu->id,
            'permission_id' =>$permission->id,
            'ordinal'       =>3,
            'parent_status' =>'N',
            'url'           =>'setup-awal-periode',
            )
        );
        $submenus->save();

        $permission = Permission::firstOrNew(array(
            'name'=>'read-transaksi',
        ));

        $permission->display_name = 'Saldo Awal';
        $permission->save();

        $submenus = Menu::firstOrNew(array(
            'name'          =>'Saldo Awal',
            'parent_id'     =>$submenu->id,
            'permission_id' =>$permission->id,
            'ordinal'       =>3,
            'parent_status' =>'N',
            'url'           =>'transaksi',
            )
        );
        $submenus->save(); //tambah

        $permission = Permission::firstOrNew(array(
            'name'=>'read-periode-keuangan',
        ));

        $permission->display_name = 'Periode Akuntansi';
        $permission->save();

        $submenus = Menu::firstOrNew(array(
            'name'          =>'Periode Akuntasi',
            'parent_id'     =>$submenu->id,
            'permission_id' =>$permission->id,
            'ordinal'       =>3,
            'parent_status' =>'N',
            'url'           =>'periode-keuangan',)
        );
        $submenus->save(); //tambah

          //create SUBMENU master new

        $permission = Permission::firstOrNew(array(
            'name'=>'read-wilayah-menu',
        ));

        $permission->display_name = 'Read Menu Wilayah';
        $permission->save();

        $submenu = Menu::firstOrNew(
        array(
            'name'=>'Wilayah',
            'parent_id'=>$menu->id,
            'permission_id'=>$permission->id,
            'ordinal'=>2,
            'parent_status'=>'Y',)
        );

        $submenu->save();

        $permission = Permission::firstOrNew(array(
            'name'=>'read-provinsi',
        ));

        $permission->display_name = 'Read Provinsi';
        $permission->save();

        $subsubmenu = Menu::firstOrNew(array(
            'name'=>'Provinsi',
            'parent_id'=>$submenu->id,
            'permission_id'=>$permission->id,
            'ordinal'=>3,
            'parent_status'=>'N',
            'url'=>'provinsi',)
        );

        $subsubmenu->save();

        $permission = Permission::firstOrNew(array(
            'name'=>'read-kabupaten',
        ));

        $permission->display_name = 'Read Kabupaten';
        $permission->save();

        $subsubmenu = Menu::firstOrNew(array(
            'name'=>'Kabupaten',
            'parent_id'=>$submenu->id,
            'permission_id'=>$permission->id,
            'ordinal'=>3,
            'parent_status'=>'N',
            'url'=>'kabupaten',)
        );

        $subsubmenu->save();

        $permission = Permission::firstOrNew(array(
            'name'=>'read-kecamatan',
        ));

        $permission->display_name = 'Read Kecamatan';
        $permission->save();

        $subsubmenu = Menu::firstOrNew(array(
            'name'=>'Kecamatan',
            'parent_id'=>$submenu->id,
            'permission_id'=>$permission->id,
            'ordinal'=>3,
            'parent_status'=>'N',
            'url'=>'kecamatan',)
        );

        $subsubmenu->save();

        $permission = Permission::firstOrNew(array(
            'name'=>'read-kelurahan',
        ));

        $permission->display_name = 'Read Kelurahan';
        $permission->save();

        $subsubmenu = Menu::firstOrNew(array(
            'name'=>'Kelurahan',
            'parent_id'=>$submenu->id,
            'permission_id'=>$permission->id,
            'ordinal'=>3,
            'parent_status'=>'N',
            'url'=>'kelurahan',)
        );

        $subsubmenu->save();

        // sub menu supplier
        $permission = Permission::firstOrNew(array(
            'name'=>'read-supplier-menu',
        ));

        $permission->display_name = 'Read Menu Supplier';
        $permission->save();

        $submenu = Menu::firstOrNew(
        array(
            'name'=>'Master Supplier',
            'parent_id'=>$menu->id,
            'permission_id'=>$permission->id,
            'ordinal'=>2,
            'parent_status'=>'Y',)
        );

        $submenu->save();

        $permission = Permission::firstOrNew(array(
            'name'=>'read-jenis-instansi-relasi',
        ));

        $permission->display_name = 'Jenis Instansi Relasi';
        $permission->save();

        $submenus = Menu::firstOrNew(array(
            'name'          =>'Jenis Supplier',
            'parent_id'     =>$submenu->id,
            'permission_id' =>$permission->id,
            'ordinal'       =>3,
            'parent_status' =>'N',
            'url'           =>'jenis-instansi-relasi',)
        );
        $submenus->save(); //tambah

        $permission = Permission::firstOrNew(array(
            'name'=>'read-instansi-relasi',
        ));

        $permission->display_name = 'Read Pemasok';
        $permission->save();

        $submenus = Menu::firstOrNew(array(
            'name'          =>'Pemasok',
            'parent_id'     =>$submenu->id,
            'permission_id' =>$permission->id,
            'ordinal'       =>2,
            'parent_status' =>'N',
            'url'           =>'instansi-relasi',
            )
        );
        $submenus->save(); //tambah


        // sub menu customer
        $permission = Permission::firstOrNew(array(
            'name'=>'read-customer-menu',
        ));

        $permission->display_name = 'Read Data Customer';
        $permission->save();

        $submenu = Menu::firstOrNew(array(
            'name'          =>'Data Customer',
            'parent_id'     =>$menu->id,
            'permission_id' =>$permission->id,
            'ordinal'       =>2,
            'parent_status' =>'Y',)
        );
        $submenu->save(); //tambah

        $permission = Permission::firstOrNew(array(
            'name'=>'read-pelanggan',
        ));

        $permission->display_name = 'Read Pelanggan';
        $permission->save();

        $submenus = Menu::firstOrNew(array(
            'name'          =>'Pelanggan',
            'parent_id'     =>$submenu->id,
            'permission_id' =>$permission->id,
            'ordinal'       =>3,
            'parent_status' =>'N',
            'url'           =>'pelanggan',
            )
        );
        $submenus->save(); //tambah


        // sub menu jurnal
        $permission = Permission::firstOrNew(array(
            'name'=>'read-master-jurnal-menu',
        ));

        $permission->display_name = 'Read Data Jurnal';
        $permission->save();

        $submenu = Menu::firstOrNew(array(
            'name'          =>'Data Jurnal',
            'parent_id'     =>$menu->id,
            'permission_id' =>$permission->id,
            'ordinal'       =>2,
            'parent_status' =>'Y',)
        );
        $submenu->save(); //tambah

        $permission = Permission::firstOrNew(array(
            'name'=>'read-unit',
        ));

        $permission->display_name = 'Read Unit';
        $permission->save();

        $submenus = Menu::firstOrNew(array(
            'name'          =>'Cost Centre',
            'parent_id'     =>$submenu->id,
            'permission_id' =>$permission->id,
            'ordinal'       =>3,
            'parent_status' =>'N',
            'url'           =>'unit',)
        );
        $submenus->save(); //tambah

        $permission = Permission::firstOrNew(array(
            'name'=>'read-perkiraan',
        ));

        $permission->display_name = 'Rekening';
        $permission->save();

        $submenus = Menu::firstOrNew(array(
            'name'          =>'Rekening',
            'parent_id'     =>$submenu->id,
            'permission_id' =>$permission->id,
            'ordinal'       =>3,
            'parent_status' =>'N',
            'url'           =>'perkiraan',)
        );
        $submenus->save(); //tambah

        $permission = Permission::firstOrNew(array(
            'name'=>'read-kas-bank',
        ));

        $permission->display_name = 'Kas Bank';
        $permission->save();

        $submenus = Menu::firstOrNew(array(
            'name'          =>'Kas Bank',
            'parent_id'     =>$submenu->id,
            'permission_id' =>$permission->id,
            'ordinal'       =>3,
            'parent_status' =>'N',
            'url'           =>'kas-bank',
            )
        );
        $submenus->save(); //tambah

        $permission = Permission::firstOrNew(array(
            'name'=>'read-tipe-jurnal',
        ));

        $permission->display_name = 'Tipe Jurnal';
        $permission->save();

        $submenus = Menu::firstOrNew(array(
            'name'          =>'Tipe Jurnal',
            'parent_id'     =>$submenu->id,
            'permission_id' =>$permission->id,
            'ordinal'       =>3,
            'parent_status' =>'N',
            'url'           =>'tipe-jurnal',)
        );
        $submenus->save(); //tambah

        $permission = Permission::firstOrNew(array(
            'name'=>'read-tarif-pajak',
        ));

        $permission->display_name = 'Read Tarif pajak';
        $permission->save();

        $submenus = Menu::firstOrNew(array(
            'name'          =>'Tarif Pajak',
            'parent_id'     =>$submenu->id,
            'permission_id' =>$permission->id,
            'ordinal'       =>3,
            'parent_status' =>'N',
            'url'           =>'tarif-pajak',
            )
        );
        $submenus->save(); //tambah

        $permission = Permission::firstOrNew(array(
            'name'=>'read-termin-pembayaran',
        ));

        $permission->display_name = 'Read Termin pembayaran';
        $permission->save();

        $submenus = Menu::firstOrNew(array(
            'name'          =>'Termin pembayaran',
            'parent_id'     =>$submenu->id,
            'permission_id' =>$permission->id,
            'ordinal'       =>3,
            'parent_status' =>'N',
            'url'           =>'termin-pembayaran',)
        );
        $submenus->save(); //tambah

        $permission = Permission::firstOrNew(array(
            'name'=>'read-visit',
        ));

        $permission->display_name = 'Kunjungan';
        $permission->save();

        $submenus = Menu::firstOrNew(array(
            'name'          =>'Kunjungan',
            'parent_id'     =>$submenu->id,
            'permission_id' =>$permission->id,
            'ordinal'       =>3,
            'parent_status' =>'N',
            'url'           =>'visit',
            )
        );
        $submenus->save();


        // sub menu transaksi
        $permission = Permission::firstOrNew(array(
            'name'=>'read-transaksi-menus',
        ));

        $permission->display_name = 'Read Data Transaksi';
        $permission->save();

        $submenu = Menu::firstOrNew(array(
            'name'          =>'Transaksi',
            'parent_id'     =>$menu->id,
            'permission_id' =>$permission->id,
            'ordinal'       =>2,
            'parent_status' =>'Y',)
        );
        $submenu->save(); //tambah

        $permission = Permission::firstOrNew(array(
            'name'=>'read-layanan',
        ));

        $permission->display_name = 'Layanan';
        $permission->save();

        $submenus = Menu::firstOrNew(array(
            'name'          =>'Layanan',
            'parent_id'     =>$submenu->id,
            'permission_id' =>$permission->id,
            'ordinal'       =>3,
            'parent_status' =>'N',
            'url'           =>'layanan',
            )
        );
        $submenus->save(); //tambah

        $permission = Permission::firstOrNew(array(
            'name'=>'read-tarif',
        ));

        $permission->display_name = 'Tarif';
        $permission->save();

        $submenus = Menu::firstOrNew(array(
            'name'          =>'Tarif',
            'parent_id'     =>$submenu->id,
            'permission_id' =>$permission->id,
            'ordinal'       =>3,
            'parent_status' =>'N',
            'url'           =>'tarif',
            )
        );
        $submenus->save(); //tambah

        $permission = Permission::firstOrNew(array(
            'name'=>'read-nakes',
        ));

        $permission->display_name = 'Nakes';
        $permission->save();

        $submenus = Menu::firstOrNew(array(
            'name'          =>'Nakes',
            'parent_id'     =>$submenu->id,
            'permission_id' =>$permission->id,
            'ordinal'       =>3,
            'parent_status' =>'N',
            'url'           =>'nakes',
            )
        );
        $submenus->save();

        $permission = Permission::firstOrNew(array(
            'name'=>'read-produk-asuransi',
        ));

        $permission->display_name = 'Produk Asuransi';
        $permission->save();

        $submenus = Menu::firstOrNew(array(
            'name'          =>'Produk Asuransi',
            'parent_id'     =>$submenu->id,
            'permission_id' =>$permission->id,
            'ordinal'       =>3,
            'parent_status' =>'N',
            'url'           =>'produk-asuransi',
            )
        );
        $submenus->save();


        // sub menu master tutup buku
        $permission = Permission::firstOrNew(array(
            'name'=>'read-tutup-buku-menu',
        ));

        $permission->display_name = 'Read Data Tutup Buku';
        $permission->save();

        $submenu = Menu::firstOrNew(array(
            'name'          =>'Tutup Buku',
            'parent_id'     =>$menu->id,
            'permission_id' =>$permission->id,
            'ordinal'       =>2,
            'parent_status' =>'Y',)
        );
        $submenu->save(); //tambah

        $permission = Permission::firstOrNew(array(
            'name'=>'read-pindah-perkiraan',
        ));

        $permission->display_name = 'Tutup Buku';
        $permission->save();

        $submenus = Menu::firstOrNew(array(
            'name'          =>'Tutup Buku',
            'parent_id'     =>$submenu->id,
            'permission_id' =>$permission->id,
            'ordinal'       =>3,
            'parent_status' =>'N',
            'url'           =>'pindah-perkiraan/index',
            )
        );
        $submenus->save(); //tambah

        // sub menu profit loss
        $permission = Permission::firstOrNew(array(
            'name'=>'read-profit-loss-menu',
        ));

        $permission->display_name = 'Read Data Profit Loss';
        $permission->save();

        $submenu = Menu::firstOrNew(array(
            'name'          =>'Profit & Loss',
            'parent_id'     =>$menu->id,
            'permission_id' =>$permission->id,
            'ordinal'       =>2,
            'parent_status' =>'Y',)
        );
        $submenu->save(); //tambah

        $permission = Permission::firstOrNew(array(
            'name'=>'read-surplus-defisit',
        ));
        $permission->display_name = 'Read Surplus Defisit';
        $permission->save();

        $submenus = Menu::firstOrNew(array(
            'name'          =>'Surplus Defisit',
            'parent_id'     =>$submenu->id,
            'permission_id' =>$permission->id,
            'ordinal'       =>3,
            'parent_status' =>'N',
            'url'           =>'surplus-defisit',
        ));
        $submenus->save();

        $permission = Permission::firstOrNew(array(
            'name'=>'read-surplus-defisit-detail',
        ));
        $permission->display_name = 'Read Surplus Defisit Detail';
        $permission->save();

        $submenus = Menu::firstOrNew(array(
            'name'          =>'Surplus Defisit Detail',
            'parent_id'     =>$submenu->id,
            'permission_id' =>$permission->id,
            'ordinal'       =>3,
            'parent_status' =>'N',
            'url'           =>'surplus-defisit-detail',
        ));
        $submenus->save();

        $permission = Permission::firstOrNew(array(
            'name'=>'read-surplus-defisit-unit',
        ));
        $permission->display_name = 'Read Surplus Defisit Unit';
        $permission->save();

        $submenus = Menu::firstOrNew(array(
            'name'          =>'Setting Unit Profit & Loss',
            'parent_id'     =>$submenu->id,
            'permission_id' =>$permission->id,
            'ordinal'       =>3,
            'parent_status' =>'N',
            'url'           =>'surplus-defisit-unit',
        ));
        $submenus->save();

        $permission = Permission::firstOrNew(array(
            'name'=>'read-surplus-defisit-rek',
        ));
        $permission->display_name = 'Read Surplus Defisit Rekening';
        $permission->save();

        $submenus = Menu::firstOrNew(array(
            'name'          =>'Setting Rekening Profit & Loss',
            'parent_id'     =>$submenu->id,
            'permission_id' =>$permission->id,
            'ordinal'       =>3,
            'parent_status' =>'N',
            'url'           =>'surplus-defisit-rek',
        ));
        $submenus->save();

        $permission = Permission::firstOrNew(array(
            'name'=>'read-informasi-setting-rekening-pl',
        ));
        $permission->display_name = 'Read Informasi Rekening';
        $permission->save();

        $submenus = Menu::firstOrNew(array(
            'name'          =>'Informasi Setting Rekening P/L',
            'parent_id'     =>$submenu->id,
            'permission_id' =>$permission->id,
            'ordinal'       =>3,
            'parent_status' =>'N',
            'url'           =>'informasi-setting-rekening-pl/index',
        ));
        $submenus->save();

        $permission = Permission::firstOrNew(array(
            'name'=>'read-informasi-setting-unit-pl',
        ));
        $permission->display_name = 'Read Informasi Unit';
        $permission->save();

        $submenus = Menu::firstOrNew(array(
            'name'          =>'Informasi Setting Unit P/L',
            'parent_id'     =>$submenu->id,
            'permission_id' =>$permission->id,
            'ordinal'       =>3,
            'parent_status' =>'N',
            'url'           =>'informasi-setting-unit-pl/index',
        ));
        $submenus->save();

        //sub menu balance sheet

        $permission = Permission::firstOrNew(array(
            'name'=>'read-balance-sheet-menus',
        ));

        $permission->display_name = 'Read Data Balance Menus';
        $permission->save();

        $submenu = Menu::firstOrNew(array(
            'name'          =>'Balance Sheet',
            'parent_id'     =>$menu->id,
            'permission_id' =>$permission->id,
            'ordinal'       =>2,
            'parent_status' =>'Y',)
        );
        $submenu->save(); //tambah

        $permission = Permission::firstOrNew(array(
            'name'=>'read-set-neraca',
        ));
        $permission->display_name = 'Read Master Neraca';
        $permission->save();

        $submenus = Menu::firstOrNew(array(
            'name'          =>'Balance Sheet Master',
            'parent_id'     =>$submenu->id,
            'permission_id' =>$permission->id,
            'ordinal'       =>3,
            'parent_status' =>'N',
            'url'           =>'set-neraca',
            )
        );
        $submenus->save();  //tambah

        $permission = Permission::firstOrNew(array(
            'name'=>'read-set-neraca-detail',
        ));
        $permission->display_name = 'Read Master Neraca Detail';
        $permission->save();

        $submenus = Menu::firstOrNew(array(
            'name'          =>'Setting Neraca',
            'parent_id'     =>$submenu->id,
            'permission_id' =>$permission->id,
            'ordinal'       =>3,
            'parent_status' =>'N',
            'url'           =>'set-neraca-detail',
            )
        );
        $submenus->save();  //tambah

        $menu->icon = 'md-folder';
        $menu->save();

        $permission = Permission::firstOrNew(array(
            'name'=>'read-setting-rumus-neraca',
        ));
        $permission->display_name = 'Read Setting Rumus Neraca';
        $permission->save();

        $submenus = Menu::firstOrNew(array(
            'name'          =>'Balance Sheet Setting',
            'parent_id'     =>$submenu->id,
            'permission_id' =>$permission->id,
            'ordinal'       =>3,
            'parent_status' =>'N',
            'url'           =>'setting-rumus-neraca/index',
            )
        );
        $submenus->save();  //tambah

        $permission = Permission::firstOrNew(array(
            'name'=>'read-informasi-setting-pl',
        ));
        $permission->display_name = 'Read Informasi Setting PL';
        $permission->save();

        $submenus = Menu::firstOrNew(array(
            'name'          =>'Informasi Setting PL',
            'parent_id'     =>$submenu->id,
            'permission_id' =>$permission->id,
            'ordinal'       =>3,
            'parent_status' =>'N',
            'url'           =>'informasi-setting-pl/index',
            )
        );
        $submenus->save();  //tambah






        // sub menu cash flow

        $permission = Permission::firstOrNew(array(
            'name'=>'read-cash-flow-menus',
        ));

        $permission->display_name = 'Read Data Cash FLow Menus';
        $permission->save();

        $submenu = Menu::firstOrNew(array(
            'name'          =>'Cash Flow',
            'parent_id'     =>$menu->id,
            'permission_id' =>$permission->id,
            'ordinal'       =>2,
            'parent_status' =>'Y',)
        );
        $submenu->save(); //tambah

        $permission = Permission::firstOrNew(array(
            'name'=>'read-arus-kas',
        ));
        $permission->display_name = 'Read Arus Kas';
        $permission->save();

        $submenus = Menu::firstOrNew(array(
            'name'          =>'Arus Kas',
            'parent_id'     =>$submenu->id,
            'permission_id' =>$permission->id,
            'ordinal'       =>3,
            'parent_status' =>'N',
            'url'           =>'arus-ka',)
        );
        $submenus->save();  //tambah

        $permission = Permission::firstOrNew(array(
            'name'=>'read-arus-kas-detail',
        ));
        $permission->display_name = 'Read Arus Kas Detail';
        $permission->save();

        $submenus = Menu::firstOrNew(array(
            'name'          =>'Setting Arus Kas',
            'parent_id'     =>$submenu->id,
            'permission_id' =>$permission->id,
            'ordinal'       =>3,
            'parent_status' =>'N',
            'url'           =>'arus-kas-detail',)
        );
        $submenus->save();  //tambah

        $permission = Permission::firstOrNew(array(
            'name'=>'read-arus-kas-rumus',
        ));
        $permission->display_name = 'Read Arus Kas Rumus';
        $permission->save();

        $submenus = Menu::firstOrNew(array(
            'name'          =>'Setting Rumus Arus Kas',
            'parent_id'     =>$submenu->id,
            'permission_id' =>$permission->id,
            'ordinal'       =>3,
            'parent_status' =>'N',
            'url'           =>'arus-kas-rumus',
            )
        );
        $submenus->save();  //tambah

        $permission = Permission::firstOrNew(array(
            'name'=>'read-transaksi-arus-kas',
        ));
        $permission->display_name = 'Read Transaksi Arus Kas';
        $permission->save();

        $submenus = Menu::firstOrNew(array(
            'name'          =>'Transaksi Arus Kas',
            'parent_id'     =>$submenu->id,
            'permission_id' =>$permission->id,
            'ordinal'       =>3,
            'parent_status' =>'N',
            'url'           =>'transaksi-arus-kas/index',
            )
        );
        $submenus->save();  //tambah

        $permission = Permission::firstOrNew(array(
            'name'=>'read-setting-cash-flow',
        ));
        $permission->display_name = 'Read Setting Cash Flow';
        $permission->save();

        $submenus = Menu::firstOrNew(array(
            'name'          =>'Setting Cash Flow',
            'parent_id'     =>$submenu->id,
            'permission_id' =>$permission->id,
            'ordinal'       =>3,
            'parent_status' =>'N',
            'url'           =>'setting-cash-flow/index',
            )
        );
        $submenus->save();  //tambah










        /*$permission = Permission::firstOrNew(array(
            'name'=>'read-perusahaan',
        ));

        $permission->display_name = 'Read Perusahaan';
        $permission->save();

        $submenu = Menu::firstOrNew(array(
            'name'          =>'Perusahaan',
            'parent_id'     =>$menu->id,
            'permission_id' =>$permission->id,
            'ordinal'       =>2,
            'parent_status' =>'N',
            'url'           =>'perusahaan',
            )
        );
        $submenu->save(); //tambah */














        /*$permission = Permission::firstOrNew(array(
            'name'=>'read-fungsi',
        ));

        $permission->display_name = 'Group';
        $permission->save();

        $submenu = Menu::firstOrNew(array(
            'name'          =>'Group',
            'parent_id'     =>$menu->id,
            'permission_id' =>$permission->id,
            'ordinal'       =>2,
            'parent_status' =>'N',
            'url'           =>'fungsi',
            )
        );
        $submenu->save(); //tambah*/











        /*$permission = Permission::firstOrNew(array(
            'name'=>'read-kelas',
        ));

        $permission->display_name = 'Kelas';
        $permission->save();

        $submenu = Menu::firstOrNew(array(
            'name'          =>'Kelas',
			'parent_id'     =>$menu->id,
            'permission_id' =>$permission->id,
            'ordinal'       =>2,
            'parent_status' =>'N',
            'url'           =>'kelas',
            )
        );
        $submenu->save(); //tambah



        $permission = Permission::firstOrNew(array(
            'name'=>'read-spesialisasi',
        ));

        $permission->display_name = 'Spesialisasi';
        $permission->save();

        $submenu = Menu::firstOrNew(array(
            'name'          =>'Spesialisasi',
            'parent_id'     =>$menu->id,
            'permission_id' =>$permission->id,
            'ordinal'       =>2,
            'parent_status' =>'N',
            'url'           =>'spesialisasi',
            )
        );
        $submenu->save(); //tambah */

        /*$permission = Permission::firstOrNew(array(
            'name'=>'read-produk-asuransi',
        ));

         //tambah

        //tambah */

        /*$permission = Permission::firstOrNew(array(
            'name'=>'read-radiologi',
        ));

        $permission->display_name = 'Radiologi';
        $permission->save();

        $submenu = Menu::firstOrNew(array(
            'name'          =>'Radiologi',
            'parent_id'     =>$menu->id,
            'permission_id' =>$permission->id,
            'ordinal'       =>2,
            'parent_status' =>'N',
            'url'           =>'radiologi',
            )
        );
        $submenu->save();

        $permission = Permission::firstOrNew(array(
            'name'=>'read-laboratorium',
        ));

        $permission->display_name = 'Laboratorium';
        $permission->save();

        $submenu = Menu::firstOrNew(array(
            'name'          =>'Laboratorium',
            'parent_id'     =>$menu->id,
            'permission_id' =>$permission->id,
            'ordinal'       =>2,
            'parent_status' =>'N',
            'url'           =>'laboratorium',
            )
        );
        $submenu->save(); */

        /*$permission = Permission::firstOrNew(array(
            'name'=>'read-cabang-user',
        ));

		$permission->display_name = 'Cabang User';
        $permission->save();

        $submenu = Menu::firstOrNew(array(
            'name'          =>'Cabang User',
            'parent_id'     =>$menu->id,
            'permission_id' =>$permission->id,
            'ordinal'       =>2,
            'parent_status' =>'N',
            'url'           =>'cabang-user',
            )
        );
        $submenu->save();

        $permission = Permission::firstOrNew(array(
            'name'=>'read-kelompok-bisnis',
        ));

		$permission->display_name = 'Kelompok Bisnis';
        $permission->save();

        $submenu = Menu::firstOrNew(array(
            'name'          =>'Kelompok Bisnis',
            'parent_id'     =>$menu->id,
            'permission_id' =>$permission->id,
            'ordinal'       =>2,
            'parent_status' =>'N',
            'url'           =>'kelompok-bisnis',
            )
        );
        $submenu->save();


        /*$permission = Permission::firstOrNew(array(
            'name'=>'read-jenis-usaha',
        ));

        $permission->display_name = 'Badan Usaha';
        $permission->save();

        $submenu = Menu::firstOrNew(array(
            'name'          =>'Badan Usaha',
            'parent_id'     =>$menu->id,
            'permission_id' =>$permission->id,
            'ordinal'       =>2,
            'parent_status' =>'N',
            'url'           =>'jenis-usaha',
            )
        );
        $submenu->save();

        $permission = Permission::firstOrNew(array(
            'name'=>'read-sub-unit-usaha',
        ));

        $permission->display_name = 'Sub Unit Usaha';
        $permission->save();

        $submenu = Menu::firstOrNew(array(
            'name'          =>'Sub Unit Usaha',
            'parent_id'     =>$menu->id,
            'permission_id' =>$permission->id,
            'ordinal'       =>2,
            'parent_status' =>'N',
            'url'           =>'sub-unit-usaha',
            )
        );
        $submenu->save();

        $permission = Permission::firstOrNew(array(
            'name'=>'read-sub-jenis-usaha',
        ));

        $permission->display_name = 'Unit Usaha';
        $permission->save();

        $submenu = Menu::firstOrNew(array(
            'name'          =>'Unit Usaha',
            'parent_id'     =>$menu->id,
            'permission_id' =>$permission->id,
            'ordinal'       =>2,
            'parent_status' =>'N',
            'url'           =>'sub-jenis-usaha',
            )
        );
        $submenu->save(); */





        /*$permission = Permission::firstOrNew(array(
            'name'=>'read-arus-kas',
        ));

        $permission->display_name = 'Arus Kas';
        $permission->save();

        $submenu = Menu::firstOrNew(array(
            'name'          =>'Arus Kas',
            'parent_id'     =>$menu->id,
            'permission_id' =>$permission->id,
            'ordinal'       =>2,
            'parent_status' =>'N',
            'url'           =>'arus-ka',
            )
        );
        $submenu->save();*/

        /*$permission = Permission::firstOrNew(array(
            'name'=>'read-item',
        ));

        $permission->display_name = 'Item';
        $permission->save();

        $submenu = Menu::firstOrNew(array(
            'name'          =>'Item',
            'parent_id'     =>$menu->id,
            'permission_id' =>$permission->id,
            'ordinal'       =>2,
            'parent_status' =>'N',
            'url'           =>'item',
            )
        );
        $submenu->save();

        $permission = Permission::firstOrNew(array(
            'name'=>'read-bukti-bayar',
        ));

        $permission->display_name = 'bukti-bayar';
        $permission->save();

        $submenu = Menu::firstOrNew(array(
            'name'          =>'Bukti Pembayaran',
            'parent_id'     =>$menu->id,
            'permission_id' =>$permission->id,
            'ordinal'       =>2,
            'parent_status' =>'N',
            'url'           =>'bukti-bayar',
            )
        );
        $submenu->save();

        $permission = Permission::firstOrNew(array(
            'name'=>'read-info-pembayaran-invoice',
        ));

        $permission->display_name = 'info-pembayaran-invoice';
        $permission->save();

		$submenu = Menu::firstOrNew(array(
            'name'          =>'Info Pembayaran',
            'parent_id'     =>$menu->id,
            'permission_id' =>$permission->id,
            'ordinal'       =>2,
            'parent_status' =>'N',
            'url'           =>'info-pembayaran-invoice',
			)
        );
        $submenu->save(); //tambah */
    }

    private function menuRekeningKontrol()
	{
        $this->command->info('Menu Rekening Kontrol Seeder');

        $permission = Permission::firstOrNew(array(
		    'name'=>'read-rekening-kontrol-menu'
        ));
	    $permission->display_name = 'Read Setting COA Menus';
        $permission->save();

        $menu = Menu::firstOrNew(array(
		    'name'          =>'Setting COA',
            'permission_id' =>$permission->id,
            'ordinal'       =>1,
            'parent_status' =>'Y'
        ));

        $menu->icon = 'md-folder';
        $menu->save();

        //create SUBMENU rekening kontrol
        $permission = Permission::firstOrNew(array(
            'name'=>'read-setting-kas-bank',
        ));
        $permission->display_name = 'Read Setting Kas Bank';
        $permission->save();

        $submenu = Menu::firstOrNew(array(
            'name'          =>'Setting Kas Bank',
            'parent_id'     =>$menu->id,
            'permission_id' =>$permission->id,
            'ordinal'       =>2,
            'parent_status' =>'N',
            'url'           =>'setting-kas-bank/index',
            )
        );
        $submenu->save(); //tambah

        $permission = Permission::firstOrNew(array(
            'name'=>'read-akun-pendapatan-obat',
        ));

		$permission->display_name = 'Akun Pendapatan Obat';
        $permission->save();

        $submenu = Menu::firstOrNew(array(
            'name'          =>'Akun Pendapatan Obat',
            'parent_id'     =>$menu->id,
            'permission_id' =>$permission->id,
            'ordinal'       =>2,
            'parent_status' =>'N',
            'url'           =>'akun-penjualan-obat/index',
            )
        );
        $submenu->save(); //tambah

        $permission = Permission::firstOrNew(array(
            'name'=>'read-akun-pendapatan-jasa',
        ));

		$permission->display_name = 'Akun Pendapatan Jasa';
        $permission->save();

		$submenu = Menu::firstOrNew(array(
            'name'          =>'Akun Pendapatan Jasa',
            'parent_id'     =>$menu->id,
            'permission_id' =>$permission->id,
            'ordinal'       =>2,
            'parent_status' =>'N',
            'url'           =>'akun-pendapatan-jasa',
            )
        );
        $submenu->save(); //tambah

        $permission = Permission::firstOrNew(array(
            'name'=>'read-setting-akun-hutang',
        ));

        $permission->display_name = 'Setting Akun Hutang';
        $permission->save();

		$submenu = Menu::firstOrNew(array(
            'name'          =>'Setting Akun Hutang',
            'parent_id'     =>$menu->id,
            'permission_id' =>$permission->id,
            'ordinal'       =>2,
            'parent_status' =>'N',
            'url'           =>'setting-akun-hutang/index',
            )
        );
        $submenu->save(); //tambah

        $permission = Permission::firstOrNew(array(
            'name'=>'read-setting-akun-pajak',
        ));

        $permission->display_name = 'Pengaturan Akun Setting Pajak';
        $permission->save();

		$submenu = Menu::firstOrNew(array(
            'name'          =>'Akun Setting Pajak',
            'parent_id'     =>$menu->id,
            'permission_id' =>$permission->id,
            'ordinal'       =>2,
            'parent_status' =>'N',
            'url'           =>'setting-akun-pajak',
            )
        );
        $submenu->save(); //tambah

        $permission = Permission::firstOrNew(array(
            'name'=>'read-setting-akun-piutang',
        ));

        $permission->display_name = 'Akun Setting Piutang';
        $permission->save();

		$submenu = Menu::firstOrNew(array(
            'name'          =>'Akun Setting Piutang',
            'parent_id'     =>$menu->id,
            'permission_id' =>$permission->id,
            'ordinal'       =>2,
            'parent_status' =>'N',
            'url'           =>'setting-akun-piutang/index',
			)
        );
        $submenu->save(); //tambah

        $permission = Permission::firstOrNew(array(
            'name'=>'read-setting-coa-pembayaran-oleh-pasien',
        ));

        $permission->display_name = 'Setting Coa Pembayaran Oleh Pasien';
        $permission->save();

		$submenu = Menu::firstOrNew(array(
            'name'          =>'Setting Coa Pembayaran Oleh Pasien',
            'parent_id'     =>$menu->id,
            'permission_id' =>$permission->id,
            'ordinal'       =>2,
            'parent_status' =>'N',
            'url'           =>'setting-coa-pembayaran-oleh-pasien/index',
			)
        );
        $submenu->save(); //tambah

        $permission = Permission::firstOrNew(array(
            'name'=>'read-setting-coa-tarif',
        ));

        $permission->display_name = 'Setting Coa Tarif';
        $permission->save();

		$submenu = Menu::firstOrNew(array(
            'name'          =>'Setting Coa Tarif',
            'parent_id'     =>$menu->id,
            'permission_id' =>$permission->id,
            'ordinal'       =>2,
            'parent_status' =>'N',
            'url'           =>'setting-coa-tarif/index',
			)
        );
        $submenu->save(); //tambah

        $permission = Permission::firstOrNew(array(
            'name'=>'read-setting-coa-invoice',
        ));

        $permission->display_name = 'setting-coa-invoice';
        $permission->save();

		$submenu = Menu::firstOrNew(array(
            'name'          =>'Setting Coa Invoice',
            'parent_id'     =>$menu->id,
            'permission_id' =>$permission->id,
            'ordinal'       =>2,
            'parent_status' =>'N',
            'url'           =>'setting-coa-invoice',
			)
        );
        $submenu->save(); //tambah

        $permission = Permission::firstOrNew(array(
            'name'=>'read-setting-coa-jasa-pegawai',
        ));

        $permission->display_name = 'setting-coa-jasa-pegawai';
        $permission->save();

		$submenu = Menu::firstOrNew(array(
            'name'          =>'Setting Coa Jasa Pegawai',
            'parent_id'     =>$menu->id,
            'permission_id' =>$permission->id,
            'ordinal'       =>2,
            'parent_status' =>'N',
            'url'           =>'setting-coa-jasa-pegawai/index',
			)
        );
        $submenu->save(); //tambah

        $permission = Permission::firstOrNew(array(
            'name'=>'read-setting-coa-payroll',
        ));

        $permission->display_name = 'setting-coa-payroll';
        $permission->save();

		$submenu = Menu::firstOrNew(array(
            'name'          =>'Setting Coa Payroll',
            'parent_id'     =>$menu->id,
            'permission_id' =>$permission->id,
            'ordinal'       =>2,
            'parent_status' =>'N',
            'url'           =>'setting-coa-payroll/index',
			)
        );
        $submenu->save(); //tambah
    }

    private function menuTransaksi()
	{
        $this->command->info('Menu Transaksi Seeder');

        $permission = Permission::firstOrNew(array(
		    'name'=>'read-transaksi-menu'
        ));
	    $permission->display_name = 'Read Transaksi Menus';
        $permission->save();

        $menu = Menu::firstOrNew(array(
		    'name'          =>'Transaksi',
            'permission_id' =>$permission->id,
            'ordinal'       =>1,
            'parent_status' =>'Y'
        ));

        $menu->icon = 'md-folder';
        $menu->save();

        //create SUBMENU Transaksi
        $permission = Permission::firstOrNew(array(
            'name'=>'read-pendapatan-jasa',
        ));
        $permission->display_name = 'Read Pendapatan Jasa';
        $permission->save();

        $submenu = Menu::firstOrNew(array(
            'name'          =>'Pendapatan Jasa',
            'parent_id'     =>$menu->id,
            'permission_id' =>$permission->id,
            'ordinal'       =>2,
            'parent_status' =>'N',
            'url'           =>'pendapatan-jasa',
            )
        );
        $submenu->save(); //tambah

        $permission = Permission::firstOrNew(array(
            'name'=>'read-penagihan-piutang-pasien',
        ));
        $permission->display_name = 'Read Pendapatan Jasa';
        $permission->save();

        $submenu = Menu::firstOrNew(array(
            'name'          =>'Penagihan Piutang Pasien',
            'parent_id'     =>$menu->id,
            'permission_id' =>$permission->id,
            'ordinal'       =>2,
            'parent_status' =>'N',
            'url'           =>'penagihan-piutang-pasien/index',
            )
        );
        $submenu->save(); //tambah

        $permission = Permission::firstOrNew(array(
            'name'=>'read-discharge-pasien',
        ));
        $permission->display_name = 'Read Discharge Pasien';
        $permission->save();

        $submenu = Menu::firstOrNew(array(
            'name'          =>'Discharge Pasien',
            'parent_id'     =>$menu->id,
            'permission_id' =>$permission->id,
            'ordinal'       =>2,
            'parent_status' =>'N',
            'url'           =>'discharge_pasien/index',
            )
        );
        $submenu->save(); //tambah

        $permission = Permission::firstOrNew(array(
            'name'=>'read-penerimaan-piutang',
        ));
        $permission->display_name = 'Read Penerimaan Piutang';
        $permission->save();

        $submenu = Menu::firstOrNew(array(
            'name'          =>'Penerimaan Piutang',
            'parent_id'     =>$menu->id,
            'permission_id' =>$permission->id,
            'ordinal'       =>2,
            'parent_status' =>'N',
            'url'           =>'penerimaan-piutang',
            )
        );
        $submenu->save(); //tambah

        $permission = Permission::firstOrNew(array(
            'name'=>'read-penjualan-obat',
        ));
        $permission->display_name = 'Read Penjualan Obat';
        $permission->save();

        $submenu = Menu::firstOrNew(array(
            'name'          =>'Penjualan Obat',
            'parent_id'     =>$menu->id,
            'permission_id' =>$permission->id,
            'ordinal'       =>2,
            'parent_status' =>'N',
            'url'           =>'penjualan-obat/index',
            )
        );
        $submenu->save(); //tambah penjualan-oat

        $permission = Permission::firstOrNew(array(
            'name'=>'read-pembelian-logistik-farmasi',
        ));
        $permission->display_name = 'Read Pembelian Logistik Dan Farmasi';
        $permission->save();

        $submenu = Menu::firstOrNew(array(
            'name'          =>'Pembelian Logistik Dan Farmasi',
            'parent_id'     =>$menu->id,
            'permission_id' =>$permission->id,
            'ordinal'       =>2,
            'parent_status' =>'N',
            'url'           =>'pembelian-logistik-farmasi/index',
            )
        );
        $submenu->save();

        $permission = Permission::firstOrNew(array(
            'name'=>'read-jenis-pembelian',
        ));
        $permission->display_name = 'Read jenis Pembelian';
        $permission->save();

        $submenu = Menu::firstOrNew(array(
            'name'          =>'Jenis Pembelian',
            'parent_id'     =>$menu->id,
            'permission_id' =>$permission->id,
            'ordinal'       =>2,
            'parent_status' =>'N',
            'url'           =>'jenis-pembelian',
            )
        );
        $submenu->save();

        $permission = Permission::firstOrNew(array(
            'name'=>'read-pembayaran-hutang',
        ));
        $permission->display_name = 'Read Pembayaran Hutang';
        $permission->save();

        $submenu = Menu::firstOrNew(array(
            'name'          =>'Pembayaran Hutang',
            'parent_id'     =>$menu->id,
            'permission_id' =>$permission->id,
            'ordinal'       =>2,
            'parent_status' =>'N',
            'url'           =>'pembayaran-hutang/index',
            )
        );
        $submenu->save();

        $permission = Permission::firstOrNew(array(
            'name'=>'read-mutasi-kas',
        ));
        $permission->display_name = 'Read Mutasi Kas';
        $permission->save();

        $submenu = Menu::firstOrNew(array(
            'name'          =>'Penerimaan Kas',
            'parent_id'     =>$menu->id,
            'permission_id' =>$permission->id,
            'ordinal'       =>2,
            'parent_status' =>'N',
            'url'           =>'mutasi-kas',
            )
        );
        $submenu->save();

        $permission = Permission::firstOrNew(array(
            'name'=>'read-pengeluaran-kas',
        ));
        $permission->display_name = 'Read Pengeluaran Kas';
        $permission->save();

        $submenu = Menu::firstOrNew(array(
            'name'          =>'Pengeluaran Kas',
            'parent_id'     =>$menu->id,
            'permission_id' =>$permission->id,
            'ordinal'       =>2,
            'parent_status' =>'N',
            'url'           =>'pengeluaran-kas',
            )
        );
        $submenu->save();

        $permission = Permission::firstOrNew(array(
            'name'=>'read-deposit',
        ));
        $permission->display_name = 'Read Deposit';
        $permission->save();

        $submenu = Menu::firstOrNew(array(
            'name'          =>'Deposit',
            'parent_id'     =>$menu->id,
            'permission_id' =>$permission->id,
            'ordinal'       =>2,
            'parent_status' =>'N',
            'url'           =>'deposit',
            )
        );
        $submenu->save(); // transaksi

        $permission = Permission::firstOrNew(array(
            'name'=>'read-invoice',
        ));
        $permission->display_name = 'Read Invoice';
        $permission->save();

        $submenu = Menu::firstOrNew(array(
            'name'          =>'Invoice',
            'parent_id'     =>$menu->id,
            'permission_id' =>$permission->id,
            'ordinal'       =>2,
            'parent_status' =>'N',
            'url'           =>'invoice',
            )
        );
        $submenu->save(); // transaksi

        $permission = Permission::firstOrNew(array(
            'name'=>'read-pembayaran-invoice',
        ));
        $permission->display_name = 'Read Pembayaran Invoice';
        $permission->save();

        $submenu = Menu::firstOrNew(array(
            'name'          =>'Pembayaran Invoice',
            'parent_id'     =>$menu->id,
            'permission_id' =>$permission->id,
            'ordinal'       =>2,
            'parent_status' =>'N',
            'url'           =>'pembayaran-invoice',
            )
        );
        $submenu->save(); // transaksi
    }

	private function menuJurnal()
	{
        $this->command->info('Menu Jurnal Seeder');

        $permission = Permission::firstOrNew(array(
		    'name'=>'read-jurnal-menu'
        ));
	    $permission->display_name = 'Read Jurnal Menus';
        $permission->save();

        $menu = Menu::firstOrNew(array(
		    'name'          =>'Jurnal Umum',
            'permission_id' =>$permission->id,
            'ordinal'       =>1,
            'parent_status' =>'Y',
        ));

        $menu->icon = 'md-folder';
        $menu->save();

        //create SUBMENU Transaksi
        /*$permission = Permission::firstOrNew(array(
            'name'=>'read-jurnal-pendapatan-jasa',
        ));
        $permission->display_name = 'Read Pendapatan Jasa';
        $permission->save();

        $submenu = Menu::firstOrNew(array(
            'name'          =>'Jurnal Pendapatan Jasa',
            'parent_id'     =>$menu->id,
            'permission_id' =>$permission->id,
            'ordinal'       =>2,
            'parent_status' =>'N',
            'url'           =>'jurnal-pendapatan-jasa/index',
            )
        );
        $submenu->save(); //tambah



		$permission = Permission::firstOrNew(array(
            'name'=>'read-jurnal-penagihan-piutang',
        ));
        $permission->display_name = 'Read Jurnal Penagihan Piutang';
        $permission->save();

        $submenu = Menu::firstOrNew(array(
            'name'          =>'Jurnal Penagihan Piutang',
            'parent_id'     =>$menu->id,
            'permission_id' =>$permission->id,
            'ordinal'       =>2,
            'parent_status' =>'N',
            'url'           =>'jurnal-penagihan-piutang/index',
            )
        );
        $submenu->save(); //tambah

		$permission = Permission::firstOrNew(array(
            'name'=>'read-jurnal-pasien-ri-pulang-rawat',
        ));
        $permission->display_name = 'Read Jurnal Pasien RI Pulang Rawat';
        $permission->save();

        $submenu = Menu::firstOrNew(array(
            'name'          =>'Jurnal Pasien Pulang Rawat',
            'parent_id'     =>$menu->id,
            'permission_id' =>$permission->id,
            'ordinal'       =>2,
            'parent_status' =>'N',
            'url'           =>'jurnal-pasien-ri-pulang-rawat/index',
            )
        );
        $submenu->save(); //tambah

        $permission = Permission::firstOrNew(array(
            'name'=>'read-jurnal-penerimaan-piutang',
        ));
        $permission->display_name = 'Read Jurnal Penerimaan Piutang';
        $permission->save();

        $submenu = Menu::firstOrNew(array(
            'name'          =>'Jurnal Penerimaan Piutang',
            'parent_id'     =>$menu->id,
            'permission_id' =>$permission->id,
            'ordinal'       =>2,
            'parent_status' =>'N',
            'url'           =>'jurnal-penerimaan-piutang/index',
            )
        );
        $submenu->save(); //tambah

        $permission = Permission::firstOrNew(array(
            'name'=>'read-jurnal-penjualan-obat-tunai',
        ));
        $permission->display_name = 'Read Jurnal Penjualan Obat';
        $permission->save();

        $submenu = Menu::firstOrNew(array(
            'name'          =>'Jurnal Penjualan Obat',
            'parent_id'     =>$menu->id,
            'permission_id' =>$permission->id,
            'ordinal'       =>2,
            'parent_status' =>'N',
            'url'           =>'jurnal-penjualan-obat-tunai',
            )
        );
        $submenu->save(); //tambah

        $permission = Permission::firstOrNew(array(
            'name'=>'read-jurnal-pembelian-logistik-farmasi',
        ));
        $permission->display_name = 'Read Jurnal Pembelian Logistik Farmasi';
        $permission->save();

        $submenu = Menu::firstOrNew(array(
            'name'          =>'Jurnal Pembelian Logistik Dan Farmasi',
            'parent_id'     =>$menu->id,
            'permission_id' =>$permission->id,
            'ordinal'       =>2,
            'parent_status' =>'N',
            'url'           =>'jurnal-pembelian-logistik-farmasi/index',
            )
        );
        $submenu->save(); //tambah

        $permission = Permission::firstOrNew(array(
            'name'=>'read-jurnal-pembayaran-hutang',
        ));
        $permission->display_name = 'Read Jurnal Pembayaran Hutang';
        $permission->save();

        $submenu = Menu::firstOrNew(array(
            'name'          =>'Jurnal Pembayaran Hutang',
            'parent_id'     =>$menu->id,
            'permission_id' =>$permission->id,
            'ordinal'       =>2,
            'parent_status' =>'N',
            'url'           =>'jurnal-pembayaran-hutang/index',
            )
        );
        $submenu->save(); //tambah

        $permission = Permission::firstOrNew(array(
            'name'=>'read-jurnal-penerimaan-kas',
        ));
        $permission->display_name = 'Read Jurnal Penerimaan Kas';
        $permission->save();

        $submenu = Menu::firstOrNew(array(
            'name'          =>'Jurnal Penerimaan Kas',
            'parent_id'     =>$menu->id,
            'permission_id' =>$permission->id,
            'ordinal'       =>2,
            'parent_status' =>'N',
            'url'           =>'jurnal-penerimaan-kas',
            )
        );
        $submenu->save(); //tambah

        $permission = Permission::firstOrNew(array(
            'name'=>'read-jurnal-pengeluaran-kas',
        ));
        $permission->display_name = 'Read Jurnal Pengeluaran Kas';
        $permission->save();

        $submenu = Menu::firstOrNew(array(
            'name'          =>'Jurnal Pengeluaran Kas',
            'parent_id'     =>$menu->id,
            'permission_id' =>$permission->id,
            'ordinal'       =>2,
            'parent_status' =>'N',
            'url'           =>'jurnal-pengeluaran-kas',
            )
        );
        $submenu->save(); //tambah */

        $permission = Permission::firstOrNew(array(
            'name'=>'read-jurnal-umum',
        ));

        $permission->display_name = 'Read Jurnal Umum';
        $permission->save();

        $submenu = Menu::firstOrNew(array(
            'name'          =>'Jurnal Umum',
            'parent_id'     =>$menu->id,
            'permission_id' =>$permission->id,
            'ordinal'       =>2,
            'parent_status' =>'N',
            'url'           =>'jurnal-umum/index',
            )
        );
        $submenu->save(); //tambah

        /*$permission = Permission::firstOrNew(array(
            'name'=>'read-jurnal-deposit',
        ));

        $permission->display_name = 'Read Jurnal Deposit';
        $permission->save();

        $submenu = Menu::firstOrNew(array(
            'name'          =>'Jurnal Deposit',
            'parent_id'     =>$menu->id,
            'permission_id' =>$permission->id,
            'ordinal'       =>2,
            'parent_status' =>'N',
            'url'           =>'jurnal-deposit/index',
            )
        );
        $submenu->save(); //tambah

        $permission = Permission::firstOrNew(array(
            'name'=>'read-jurnal-invoice',
        ));

        $permission->display_name = 'Read Jurnal Invoice';
        $permission->save();
        $submenu = Menu::firstOrNew(array(
            'name'          =>'Jurnal Invoice',
            'parent_id'     =>$menu->id,
            'permission_id' =>$permission->id,
            'ordinal'       =>2,
            'parent_status' =>'N',
            'url'           =>'jurnal-invoice',
            )
        );
        $submenu->save(); //tambah

        $permission = Permission::firstOrNew(array(
            'name'=>'read-jurnal-pembayaran-invoice',
        ));

        $permission->display_name = 'Read Jurnal Pembayaran Invoice';
        $permission->save();
        $submenu = Menu::firstOrNew(array(
            'name'          =>'Jurnal Pembayaran Invoice',
            'parent_id'     =>$menu->id,
            'permission_id' =>$permission->id,
            'ordinal'       =>2,
            'parent_status' =>'N',
            'url'           =>'jurnal-pembayaran-invoice/index',
            )
        );
        $submenu->save(); //tambah
    }

    private function menuSistemInformasi()
	{
        $this->command->info('Menu Sistem Informasi Seeder');

        $permission = Permission::firstOrNew(array(
		    'name'=>'read-dobol',
        ));
	    $permission->display_name = 'Read Sistem Informasi';
        $permission->save();

        $menu = Menu::firstOrNew(array(
		    'name'          =>'Sistem Informasi',
            'permission_id' =>$permission->id,
            'ordinal'       =>1,
            'parent_status' =>'Y'
        ));

        $menu->icon = 'md-folder';
        $menu->save();

        $permission = Permission::firstOrNew(array(
            'name'=>'read-sistem-informasi-piutang',
        ));
        $permission->display_name = 'Read Sistem Informasi Piutang';
        $permission->save();

        $submenu = Menu::firstOrNew(array(
            'name'          =>'Sistem Informasi Piutang',
            'parent_id'     =>$menu->id,
            'permission_id' =>$permission->id,
            'ordinal'       =>2,
            'parent_status' =>'N',
            'url'           =>'sistem-informasi-piutang',
            )
        );
        $submenu->save(); //tambah

        $permission = Permission::firstOrNew(array(
            'name'=>'read-sistem-informasi-hutang',
        ));
        $permission->display_name = 'Read Sistem Informasi Hutang';
        $permission->save();

        $submenu = Menu::firstOrNew(array(
            'name'          =>'Sistem Informasi Hutang',
            'parent_id'     =>$menu->id,
            'permission_id' =>$permission->id,
            'ordinal'       =>2,
            'parent_status' =>'N',
            'url'           =>'sistem-informasi-hutang',
            )
        );
        $submenu->save(); */ //tambah
    }

    private function menuLaporanKeuangan()
	{
        $this->command->info('Menu Laporan Keuangan Seeder');

        $permission = Permission::firstOrNew(array(
		    'name'=>'read-laporan-keuangan',
        ));
	    $permission->display_name = 'Read Laporan Keuangan';
        $permission->save();

        $menu = Menu::firstOrNew(array(
		    'name'          =>'Laporan',
            'permission_id' =>$permission->id,
            'ordinal'       =>1,
            'parent_status' =>'Y'
        ));

        $menu->icon = 'md-folder';
        $menu->save();

        $permission = Permission::firstOrNew(array(
            'name'=>'read-laporan-keuangan-submenu',
        ));
        $permission->display_name = 'Read Menu Laporan Keuangan';
        $permission->save();

        $submenu = Menu::firstOrNew(array(
            'name'          =>'Laporan Keuangan',
            'parent_id'     =>$menu->id,
            'permission_id' =>$permission->id,
            'ordinal'       =>2,
            'parent_status' =>'Y',
            )
        );
        $submenu->save(); //tambah

        $permission = Permission::firstOrNew(array(
            'name'=>'read-laporan-jurnal-umum',
        ));
        $permission->display_name = 'Read Laporan Jurnal Umum';
        $permission->save();

        $submenus = Menu::firstOrNew(array(
            'name'          =>'Journal',
            'parent_id'     =>$submenu->id,
            'permission_id' =>$permission->id,
            'ordinal'       =>3,
            'parent_status' =>'N',
            'url'           =>'laporan-jurnal-umum/index',
            )
        );
        $submenus->save(); //tambah

        $permission = Permission::firstOrNew(array(
            'name'=>'read-laporan-buku-besar',
        ));
        $permission->display_name = 'Read Laporan Buku Besar';
        $permission->save();

        $submenus = Menu::firstOrNew(array(
            'name'          =>'General Ledger',
            'parent_id'     =>$submenu->id,
            'permission_id' =>$permission->id,
            'ordinal'       =>3,
            'parent_status' =>'N',
            'url'           =>'laporan-buku-besar/index',
            )
        );
        $submenus->save();  //tambah

        $permission = Permission::firstOrNew(array(
            'name'=>'read-laporan-neraca-saldo',
        ));
        $permission->display_name = 'Read Laporan Neraca Saldo';
        $permission->save();

        $submenus = Menu::firstOrNew(array(
            'name'          =>'Trial Balance',
            'parent_id'     =>$submenu->id,
            'permission_id' =>$permission->id,
            'ordinal'       =>3,
            'parent_status' =>'N',
            'url'           =>'laporan-neraca-saldo/index',
            )
        );
        $submenus->save();  //tambah

        $permission = Permission::firstOrNew(array(
            'name'=>'read-worksheet',
        ));
        $permission->display_name = 'Read Worksheet';
        $permission->save();

        $submenus = Menu::firstOrNew(array(
            'name'          =>'Worksheet',
            'parent_id'     =>$submenu->id,
            'permission_id' =>$permission->id,
            'ordinal'       =>3,
            'parent_status' =>'N',
            'url'           =>'worksheet/index',
            )
        );
        $submenus->save();  //tambah

        /*$permission = Permission::firstOrNew(array(
            'name'=>'read-laporan-laba-rugi',
        ));
        $permission->display_name = 'Read Laporan Laba Rugi';
        $permission->save();

        $submenu = Menu::firstOrNew(array(
            'name'          =>'Laporan Laba Rugi',
            'parent_id'     =>$menu->id,
            'permission_id' =>$permission->id,
            'ordinal'       =>2,
            'parent_status' =>'N',
            'url'           =>'laporan-laba-rugi/index',
            )
        );
        $submenu->save();  //tambah
        */

        $permission = Permission::firstOrNew(array(
            'name'=>'read-perubahan-ekuitas',
        ));
        $permission->display_name = 'Read Laporan Perubahan Ekuitas';
        $permission->save();

        $submenus = Menu::firstOrNew(array(
            'name'          =>'Perubahan Ekuitas',
            'parent_id'     =>$submenu->id,
            'permission_id' =>$permission->id,
            'ordinal'       =>3,
            'parent_status' =>'N',
            'url'           =>'perubahan-ekuitas/index',
            )
        );
        $submenus->save();

        /*$permission = Permission::firstOrNew(array(
            'name'=>'read-laporan-perubahan-ekuitas',
        ));
        $permission->display_name = 'Read Laporan Perubahan Ekuitas';
        $permission->save();

        $submenu = Menu::firstOrNew(array(
            'name'          =>'Perubahan Ekuitas',
            'parent_id'     =>$menu->id,
            'permission_id' =>$permission->id,
            'ordinal'       =>2,
            'parent_status' =>'N',
            'url'           =>'laporan-perubahan-ekuitas/index',
            )
        );
        $submenu->save();  *///tambah

        /*
        $permission = Permission::firstOrNew(array(
            'name'=>'read-laporan-neraca',
        ));
        $permission->display_name = 'Read Laporan Neraca';
        $permission->save();

        $submenu = Menu::firstOrNew(array(
            'name'          =>'Laporan Neraca',
            'parent_id'     =>$menu->id,
            'permission_id' =>$permission->id,
            'ordinal'       =>2,
            'parent_status' =>'N',
            'url'           =>'laporan-neraca/index',
            )
        );
        $submenu->save(); //tambah

        $permission = Permission::firstOrNew(array(
            'name'=>'read-laporan-neraca-saldo-setelah-penyesuaian',
        ));
        $permission->display_name = 'Read Laporan Neraca Saldo Setelah Penyesuaian';
        $permission->save();

        $submenus = Menu::firstOrNew(array(
            'name'          =>'Post Closing Trial Balance',
            'parent_id'     =>$submenu->id,
            'permission_id' =>$permission->id,
            'ordinal'       =>3,
            'parent_status' =>'N',
            'url'           =>'laporan-neraca-saldo-setelah-penutupan/index',
            )
        );
        $submenus->save();  //tambah

        /*$permission = Permission::firstOrNew(array(
            'name'=>'read-laporan-arus-kas',
        ));
        $permission->display_name = 'Read Laporan Arus Kas';
        $permission->save();

        $submenu = Menu::firstOrNew(array(
            'name'          =>'Laporan Arus Kas',
            'parent_id'     =>$menu->id,
            'permission_id' =>$permission->id,
            'ordinal'       =>2,
            'parent_status' =>'N',
            'url'           =>'laporan-arus-kas',
            )
        );
        $submenu->save();  //tambah


        $permission = Permission::firstOrNew(array(
            'name'=>'read-set-lap-ekuitas',
        ));
        $permission->display_name = 'Read Set Lap Ekuitas';
        $permission->save();

        $submenu = Menu::firstOrNew(array(
            'name'          =>'Master Perubahan Modal',
            'parent_id'     =>$menu->id,
            'permission_id' =>$permission->id,
            'ordinal'       =>2,
            'parent_status' =>'N',
            'url'           =>'set-lap-ekuita',
            )
        );
        $submenu->save();  //tambah

        $permission = Permission::firstOrNew(array(
            'name'=>'read-setting-perubahan-ekuitas',
        ));
        $permission->display_name = 'Read Setting Perubahan Ekuitas';
        $permission->save();

        $submenu = Menu::firstOrNew(array(
            'name'          =>'Setting Perubahan Ekuitas',
            'parent_id'     =>$menu->id,
            'permission_id' =>$permission->id,
            'ordinal'       =>2,
            'parent_status' =>'N',
            'url'           =>'setting-perubahan-ekuitas/index',
            )
        );
        $submenu->save(); */ //tambah

        $menu->icon = 'md-folder';
        $menu->save();

        $permission = Permission::firstOrNew(array(
            'name'=>'read-mutasi-jurnal',
        ));
        $permission->display_name = 'Read Mutasi Jurnal';
        $permission->save();

        $submenus = Menu::firstOrNew(array(
            'name'          =>'Mutasi Jurnal',
            'parent_id'     =>$submenu->id,
            'permission_id' =>$permission->id,
            'ordinal'       =>3,
            'parent_status' =>'N',
            'url'           =>'mutasi-jurnal/index',
            )
        );
        $submenus->save();  //tambah

        $permission = Permission::firstOrNew(array(
            'name'=>'read-laporan-payroll',
        ));
        $permission->display_name = 'Read Laporan Payroll';
        $permission->save();

        $submenus = Menu::firstOrNew(array(
            'name'          =>'Laporan Payroll',
            'parent_id'     =>$submenu->id,
            'permission_id' =>$permission->id,
            'ordinal'       =>3,
            'parent_status' =>'N',
            'url'           =>'laporan-payroll/index',
            )
        );
        $submenus->save();  //tambah

        $permission = Permission::firstOrNew(array(
            'name'=>'read-laporan-surplus-defisit',
        ));
        $permission->display_name = 'Read Laporan Surplus Defisit';
        $permission->save();

        $submenus = Menu::firstOrNew(array(
            // 'name'          =>'Laporan Surplus Defisit',
            'name'          =>'Laporan Profit & Loss',
            'parent_id'     =>$submenu->id,
            'permission_id' =>$permission->id,
            'ordinal'       =>3,
            'parent_status' =>'N',
            'url'           =>'laporan-surplus-defisit/index',
            )
        );
        $submenus->save();  //tambah

        $permission = Permission::firstOrNew(array(
            'name'=>'read-laporan-pl-cc',
        ));
        $permission->display_name = 'Read Laporan PL CC';
        $permission->save();

        $submenus = Menu::firstOrNew(array(
            // 'name'          =>'Laporan Surplus Defisit',
            'name'          =>'Laporan Profit & Loss CC',
            'parent_id'     =>$submenu->id,
            'permission_id' =>$permission->id,
            'ordinal'       =>2,
            'parent_status' =>'N',
            'url'           =>'laporan-pl-cc/index',
            )
        );
        $submenus->save();  //tambah

        $permission = Permission::firstOrNew(array(
            'name'=>'read-laporan-cash-flow',
        ));
        $permission->display_name = 'Read Laporan Cash Flow';
        $permission->save();

        $submenus = Menu::firstOrNew(array(
            'name'          =>'Laporan Cash Flow',
            'parent_id'     =>$submenu->id,
            'permission_id' =>$permission->id,
            'ordinal'       =>2,
            'parent_status' =>'N',
            'url'           =>'laporan-cash-flow/index',
            )
        );
        $submenus->save();  //tambah

        $permission = Permission::firstOrNew(array(
            'name'=>'read-sales-report-submenu',
        ));
        $permission->display_name = 'Read Sales Report';
        $permission->save();

        $submenu = Menu::firstOrNew(array(
            'name'          =>'Sales Report',
            'parent_id'     =>$menu->id,
            'permission_id' =>$permission->id,
            'ordinal'       =>2,
            'parent_status' =>'Y',)
        );
        $submenu->save();  //tambah

        $permission = Permission::firstOrNew(array(
            'name'=>'read-sales-report',
        ));
        $permission->display_name = 'Read Sales Report';
        $permission->save();

        $submenus = Menu::firstOrNew(array(
            'name'          =>'Sales Report',
            'parent_id'     =>$submenu->id,
            'permission_id' =>$permission->id,
            'ordinal'       =>3,
            'parent_status' =>'N',
            'url'           =>'sales-report/index',
        ));
        $submenus->save();

        $permission = Permission::firstOrNew(array(
            'name'=>'read-profit-center-submenu',
        ));
        $permission->display_name = 'Read Sales Report';
        $permission->save();

        $submenu = Menu::firstOrNew(array(
            'name'          =>'Profit Center',
            'parent_id'     =>$menu->id,
            'permission_id' =>$permission->id,
            'ordinal'       =>2,
            'parent_status' =>'Y',)
        );
        $submenu->save();  //tambah

        $permission = Permission::firstOrNew(array(
            'name'=>'read-laporan-profit-center',
        ));
        $permission->display_name = 'Read Laporan Profit Center';
        $permission->save();

        $submenus = Menu::firstOrNew(array(
            'name'          =>'Laporan Profit Center',
            'parent_id'     =>$submenu->id,
            'permission_id' =>$permission->id,
            'ordinal'       =>3,
            'parent_status' =>'N',
            'url'           =>'laporan-profit-center/index',
        ));
        $submenus->save();

        $permission = Permission::firstOrNew(array(
            'name'=>'read-summary-profit-center',
        ));
        $permission->display_name = 'Read Summary Profit Center';
        $permission->save();

        $submenus = Menu::firstOrNew(array(
            'name'          =>'Summary Profit Center',
            'parent_id'     =>$submenu->id,
            'permission_id' =>$permission->id,
            'ordinal'       =>3,
            'parent_status' =>'N',
            'url'           =>'summary-profit-center/index',
        ));
        $submenus->save();

        $permission = Permission::firstOrNew(array(
            'name'=>'read-balance-sheet-submenu',
        ));
        $permission->display_name = 'Read Balance Sheet';
        $permission->save();

        $submenu = Menu::firstOrNew(array(
            'name'          =>'Balance Sheet',
            'parent_id'     =>$menu->id,
            'permission_id' =>$permission->id,
            'ordinal'       =>2,
            'parent_status' =>'Y',)
        );
        $submenu->save();  //tambah

        $permission = Permission::firstOrNew(array(
            'name'=>'read-neraca-laporan',
        ));
        $permission->display_name = 'Read Neraca Laporan';
        $permission->save();

        $submenus = Menu::firstOrNew(array(
            'name'          =>'Balance Sheet',
            'parent_id'     =>$submenu->id,
            'permission_id' =>$permission->id,
            'ordinal'       =>2,
            'parent_status' =>'N',
            'url'           =>'neraca-laporan/index',
            )
        );
        $submenus->save();  //tambah


    }
    private function menuPersediaan()
    {
        $this->command->info('Menu Persediaan');

        $permission = Permission::firstOrNew(array(
		    'name'=>'read-menu-persediaan',
        ));
	    $permission->display_name = 'Read Menu Persediaan';
        $permission->save();

        $menu = Menu::firstOrNew(array(
		    'name'          =>'Persediaan',
            'permission_id' =>$permission->id,
            'ordinal'       =>1,
            'parent_status' =>'Y'
        ));

        $menu->icon = 'md-folder';
        $menu->save();

        $permission = Permission::firstOrNew(array(
            'name'=>'read-kategori-barang',
        ));
        $permission->display_name = 'Read Kategori Persediaan';
        $permission->save();

        $submenu = Menu::firstOrNew(array(
            'name'          =>'Kategori Persediaan',
            'parent_id'     =>$menu->id,
            'permission_id' =>$permission->id,
            'ordinal'       =>2,
            'parent_status' =>'N',
            'url'           =>'kategori-barang',
            )
        );
        $submenu->save();  //tambah

        $permission = Permission::firstOrNew(array(
            'name'=>'read-sub-kategori-barang',
        ));
        $permission->display_name = 'Read Sub Kategori Persediaan';
        $permission->save();

        $submenu = Menu::firstOrNew(array(
            'name'          =>'Sub Kategori Persediaan',
            'parent_id'     =>$menu->id,
            'permission_id' =>$permission->id,
            'ordinal'       =>2,
            'parent_status' =>'N',
            'url'           =>'sub-kategori-barang',
            )
        );
        $submenu->save();  //tambah

        $permission = Permission::firstOrNew(array(
            'name'=>'read-barang',
        ));
        $permission->display_name = 'Read Barang';
        $permission->save();

        $submenu = Menu::firstOrNew(array(
            'name'          =>'Barang',
            'parent_id'     =>$menu->id,
            'permission_id' =>$permission->id,
            'ordinal'       =>2,
            'parent_status' =>'N',
            'url'           =>'barang',
            )
        );
        $submenu->save();  //tambah

        $permission = Permission::firstOrNew(array(
            'name'=>'read-packing-barang',
        ));
        $permission->display_name = 'Read Packing Barang';
        $permission->save();

        $submenu = Menu::firstOrNew(array(
            'name'          =>'Packing Barang',
            'parent_id'     =>$menu->id,
            'permission_id' =>$permission->id,
            'ordinal'       =>2,
            'parent_status' =>'N',
            'url'           =>'packing-barang',
            )
        );
        $submenu->save();  //tambah

        $permission = Permission::firstOrNew(array(
            'name'=>'read-log-stok',
        ));
        $permission->display_name = 'Read Input Saldo Awal Persediaan';
        $permission->save();

        $submenu = Menu::firstOrNew(array(
            'name'          =>'Saldo Awal Persediaan',
            'parent_id'     =>$menu->id,
            'permission_id' =>$permission->id,
            'ordinal'       =>2,
            'parent_status' =>'N',
            'url'           =>'log-stok',
            )
        );
        $submenu->save();  //tambah

    }



    private function menuAnggaran ()
    {
        $this->command->info('Menu Anggaran');

        $permission = Permission::firstOrNew(array(
		    'name'=>'read-menu-anggaran',
        ));
	    $permission->display_name = 'Read Menu Anggaran';
        $permission->save();

        $menu = Menu::firstOrNew(array(
		    'name'          =>'Anggaran',
            'permission_id' =>$permission->id,
            'ordinal'       =>1,
            'parent_status' =>'Y'
        ));

        $menu->icon = 'md-folder';
        $menu->save();

        $permission = Permission::firstOrNew(array(
            'name'=>'read-syarat-penggajuan-anggaran',
        ));
        $permission->display_name = 'Read Syarat Penggajuan Anggaran';
        $permission->save();

        $submenu = Menu::firstOrNew(array(
            'name'          =>'Syarat Penggajuan Anggaran',
            'parent_id'     =>$menu->id,
            'permission_id' =>$permission->id,
            'ordinal'       =>2,
            'parent_status' =>'N',
            'url'           =>'syarat-penggajuan-anggaran/index',
            )
        );
        $submenu->save();  //tambah

        $permission = Permission::firstOrNew(array(
            'name'=>'read-akun-anggaran',
        ));
        $permission->display_name = 'Read Akun Anggaran';
        $permission->save();

        $submenu = Menu::firstOrNew(array(
            'name'          =>'Akun Anggaran',
            'parent_id'     =>$menu->id,
            'permission_id' =>$permission->id,
            'ordinal'       =>2,
            'parent_status' =>'N',
            'url'           =>'akun-anggaran',
            )
        );
        $submenu->save();  //tambah

        $permission = Permission::firstOrNew(array(
            'name'=>'read-manajemen-anggaran',
        ));
        $permission->display_name = 'Read Manajemen Anggaran';
        $permission->save();

        $submenu = Menu::firstOrNew(array(
            'name'          =>'Manajemen Anggaran',
            'parent_id'     =>$menu->id,
            'permission_id' =>$permission->id,
            'ordinal'       =>2,
            'parent_status' =>'N',
            'url'           =>'manajemen-anggaran/index',
            )
        );
        $submenu->save();  //tambah


    }

    private function menuPayroll ()
    {
        $this->command->info('Menu Payroll');

        $permission = Permission::firstOrNew(array(
		    'name'=>'read-menu-payroll',
        ));
	    $permission->display_name = 'Read Menu Payroll';
        $permission->save();

        $menu = Menu::firstOrNew(array(
		    'name'          =>'Payroll',
            'permission_id' =>$permission->id,
            'ordinal'       =>1,
            'parent_status' =>'Y'
        ));

        $menu->icon = 'md-folder';
        $menu->save();



        $permission = Permission::firstOrNew(array(
            'name'=>'read-transaksi-payroll',
        ));
        $permission->display_name = 'Read Transaksi Payroll';
        $permission->save();

        $submenu = Menu::firstOrNew(array(
            'name'          =>'Transaksi Payroll',
            'parent_id'     =>$menu->id,
            'permission_id' =>$permission->id,
            'ordinal'       =>2,
            'parent_status' =>'N',
            'url'           =>'transaksi-payroll/index',
            )
        );
        $submenu->save();  //tambah

        $permission = Permission::firstOrNew(array(
            'name'=>'read-sinkronasi-data-payroll',
        ));
        $permission->display_name = 'Read Sinkronisasi Data Payroll';
        $permission->save();

        $submenu = Menu::firstOrNew(array(
            'name'          =>'Sinkronisasi Data Payroll',
            'parent_id'     =>$menu->id,
            'permission_id' =>$permission->id,
            'ordinal'       =>2,
            'parent_status' =>'N',
            'url'           =>'sinkronasi-data-payroll',
            )
        );
        $submenu->save();  //tambah
    }

    private function menuNeraca ()
    {
        $this->command->info('Menu Neraca');

        $permission = Permission::firstOrNew(array(
		    'name'=>'read-menu-neraca',
        ));
	    $permission->display_name = 'Read Menu Neraca';
        $permission->save();

        $menu = Menu::firstOrNew(array(
		    'name'          =>'Balance Sheet',
            'permission_id' =>$permission->id,
            'ordinal'       =>1,
            'parent_status' =>'Y'
        ));

        $menu->icon = 'md-folder';
        $menu->save();






        $permission = Permission::firstOrNew(array(
            'name'=>'read-informasi-setting-coa-neraca',
        ));
        $permission->display_name = 'Read Informasi Setting Coa Neraca';
        $permission->save();

        $submenu = Menu::firstOrNew(array(
            'name'          =>'Informasi Setting Coa Neraca',
            'parent_id'     =>$menu->id,
            'permission_id' =>$permission->id,
            'ordinal'       =>2,
            'parent_status' =>'N',
            'url'           =>'informasi-setting-coa-neraca/index',
        ));
        $submenu->save();
    }

    private function menuSaldoAwal ()
    {
        $this->command->info('Menu Saldo Awal');

        $permission = Permission::firstOrNew(array(
		    'name'=>'read-menu-saldo-awal',
        ));
	    $permission->display_name = 'Read Saldo Awal';
        $permission->save();

        $menu = Menu::firstOrNew(array(
		    'name'          =>'Saldo Awal',
            'permission_id' =>$permission->id,
            'ordinal'       =>1,
            'parent_status' =>'Y'
        ));

        $menu->icon = 'md-folder';
        $menu->save();

        $permission = Permission::firstOrNew(array(
            'name'=>'read-saldo-awal',
        ));
        $permission->display_name = 'Read Master Saldo Awal';
        $permission->save();

        $submenu = Menu::firstOrNew(array(
            'name'          =>'Master Saldo Awal',
            'parent_id'     =>$menu->id,
            'permission_id' =>$permission->id,
            'ordinal'       =>2,
            'parent_status' =>'N',
            'url'           =>'saldo-awal/index',
            )
        );
        $submenu->save();  //tambah
    }

    private function menuUploadFile ()
    {
        $this->command->info('Menu Upload File');

        $permission = Permission::firstOrNew(array(
		    'name'=>'read-upload-file',
        ));
	    $permission->display_name = 'Read Import File';
        $permission->save();

        $menu = Menu::firstOrNew(array(
		    'name'          =>'Import File',
            'permission_id' =>$permission->id,
            'ordinal'       =>1,
            'parent_status' =>'Y'
        ));

        $menu->icon = 'md-folder';
        $menu->save();

        $permission = Permission::firstOrNew(array(
            'name'=>'read-upload-jurnal',
        ));
        $permission->display_name = 'Read Upload Jurnal';
        $permission->save();

        $submenu = Menu::firstOrNew(array(
            'name'          =>'Upload Jurnal',
            'parent_id'     =>$menu->id,
            'permission_id' =>$permission->id,
            'ordinal'       =>2,
            'parent_status' =>'N',
            'url'           =>'upload-jurnal/index',
            )
        );
        $submenu->save();  //tambah

        $permission = Permission::firstOrNew(array(
            'name'=>'read-upload-aktiva-tetap',
        ));
        $permission->display_name = 'Read Aktiva Tetap';
        $permission->save();

        $submenu = Menu::firstOrNew(array(
            'name'          =>'Upload Aktiva Tetap',
            'parent_id'     =>$menu->id,
            'permission_id' =>$permission->id,
            'ordinal'       =>2,
            'parent_status' =>'N',
            'url'           =>'upload-aktiva-tetap/index',
            )
        );
        $submenu->save();  //tambah
    }

    private function menuArusKas ()
    {
        $this->command->info('Menu Arus Kas');

        $permission = Permission::firstOrNew(array(
		    'name'=>'read-menu-arus-kas',
        ));
	    $permission->display_name = 'Read Menu Arus Kas';
        $permission->save();

        $menu = Menu::firstOrNew(array(
		    'name'          =>'Cash Flow',
            'permission_id' =>$permission->id,
            'ordinal'       =>1,
            'parent_status' =>'Y'
        ));

        $menu->icon = 'md-folder';
        $menu->save();





    }

    private function menuSurplusDefisit ()
    {
        $this->command->info('Menu Surplus Defisit');

        $permission = Permission::firstOrNew(array(
		    'name'=>'read-menu-surplus-defisit',
        ));
	    $permission->display_name = 'Read Menu Surplus Defisit';
        $permission->save();

        $menu = Menu::firstOrNew(array(
		    // 'name'          =>'Surplus Defisit',
		    'name'          =>'Profit & Loss',
            'permission_id' =>$permission->id,
            'ordinal'       =>1,
            'parent_status' =>'Y'
        ));

        $menu->icon = 'md-folder';
        $menu->save();

        $permission = Permission::firstOrNew(array(
            'name'=>'read-setting-surplus-defisit',
        ));
        $permission->display_name = 'Read Setting Surplus Defisit';
        $permission->save();

        $submenu = Menu::firstOrNew(array(
            // 'name'          =>'Master Surplus Deposit',
            'name'          =>'Master Profit & Loss',
            'parent_id'     =>$menu->id,
            'permission_id' =>$permission->id,
            'ordinal'       =>2,
            'parent_status' =>'N',
            'url'           =>'setting-surplus-defisit',
            )
        );
        $submenu->save();  //tambah

        $permission = Permission::firstOrNew(array(
            'name'=>'read-set-surplus-defisit-detail',
        ));
        $permission->display_name = 'Read Set Surplus Defisit Detail';
        $permission->save();

        $submenu = Menu::firstOrNew(array(
            // 'name'          =>'Setting Surplus Defisit',
            'name'          =>'Setting Profit & Loss',
            'parent_id'     =>$menu->id,
            'permission_id' =>$permission->id,
            'ordinal'       =>2,
            'parent_status' =>'N',
            'url'           =>'set-surplus-defisit-detail',
            )
        );
        $submenu->save();  //tambah

    }

    private function menuMutasiKas ()
    {
        $this->command->info('Menu Mutasi Kas');

        $permission = Permission::firstOrNew(array(
		    'name'=>'read-menu-mutasi-kas',
        ));
	    $permission->display_name = 'Read Menu Mutasi Kas';
        $permission->save();

        $menu = Menu::firstOrNew(array(
		    'name'          =>'Mutasi Kas',
            'permission_id' =>$permission->id,
            'ordinal'       =>1,
            'parent_status' =>'Y'
        ));

        $menu->icon = 'md-folder';
        $menu->save();

        $permission = Permission::firstOrNew(array(
            'name'=>'read-mutasi-penerimaan-kas',
        ));
        $permission->display_name = 'Read Mutasi Penerimaan Kas';
        $permission->save();

        $submenu = Menu::firstOrNew(array(
            'name'          =>'Mutasi Penerimaan Kas',
            'parent_id'     =>$menu->id,
            'permission_id' =>$permission->id,
            'ordinal'       =>2,
            'parent_status' =>'N',
            'url'           =>'mutasi-penerimaan-kas/index',
            )
        );
        $submenu->save();

        $permission = Permission::firstOrNew(array(
            'name'=>'read-mutasi-pengeluaran-kas',
        ));
        $permission->display_name = 'Read Mutasi Pengeluaran Kas';
        $permission->save();

        $submenu = Menu::firstOrNew(array(
            'name'          =>'Mutasi Pengeluaran Kas',
            'parent_id'     =>$menu->id,
            'permission_id' =>$permission->id,
            'ordinal'       =>2,
            'parent_status' =>'N',
            'url'           =>'mutasi-pengeluaran-kas/index',
            )
        );
        $submenu->save();
    }

    private function menuProfitCenter ()
    {
        $this->command->info('Menu Profit Center');

        $permission = Permission::firstOrNew(array(
		    'name'=>'read-menu-profit-center',
        ));
	    $permission->display_name = 'Read Menu Profit Center';
        $permission->save();

        $menu = Menu::firstOrNew(array(
		    'name'          =>'Profit Center',
            'permission_id' =>$permission->id,
            'ordinal'       =>1,
            'parent_status' =>'Y'
        ));

        $menu->icon = 'md-folder';
        $menu->save();


    }

    private function menuLaporanSales()
    {
        $this->command->info('Menu Laporan Sales');

        $permission = Permission::firstOrNew(array(
		    'name'=>'read-menu-laporan-sales',
        ));
	    $permission->display_name = 'Read Menu Laporan Sales';
        $permission->save();

        $menu = Menu::firstOrNew(array(
		    'name'          =>'Sales Report',
            'permission_id' =>$permission->id,
            'ordinal'       =>1,
            'parent_status' =>'Y'
        ));

        $menu->icon = 'md-folder';
        $menu->save();


    }

    private function menuMasterDataPl()
    {
        $this->command->info('Menu Master Data PL');

        $permission = Permission::firstOrNew(array(
		    'name'=>'read-menu-master-data-pl',
        ));
	    $permission->display_name = 'Read Menu Master Data PL';
        $permission->save();

        $menu = Menu::firstOrNew(array(
		    'name'          =>'Profit & Loss',
            'permission_id' =>$permission->id,
            'ordinal'       =>1,
            'parent_status' =>'Y'
        ));

        $menu->icon = 'md-folder';
        $menu->save();






    }

    private function menuInformasiSettingPl()
    {
        $this->command->info('Menu Informasi Setting PL');

        $permission = Permission::firstOrNew(array(
		    'name'=>'read-menu-informasi-setting-pl',
        ));
	    $permission->display_name = 'Read Menu Informasi Setting PL';
        $permission->save();

        $menu = Menu::firstOrNew(array(
		    'name'          =>'Menu Informasi Setting PL',
            'permission_id' =>$permission->id,
            'ordinal'       =>1,
            'parent_status' =>'Y'
        ));

        $menu->icon = 'md-folder';
        $menu->save();


    }

    private function menuInformasiSettingCoa()
    {
        $this->command->info('Menu Informasi Setting COa');

        $permission = Permission::firstOrNew(array(
		    'name'=>'read-menu-informasi-setting-coa',
        ));
	    $permission->display_name = 'Read Menu Informasi Setting Coa Neraca';
        $permission->save();

        $menu = Menu::firstOrNew(array(
		    'name'          =>'Menu Informasi Setting Coa Neraca',
            'permission_id' =>$permission->id,
            'ordinal'       =>1,
            'parent_status' =>'Y'
        ));

        $menu->icon = 'md-folder';
        $menu->save();


    }

    private function menuAktivaTetap()
    {
        $this->command->info('Menu Aktiva Tetap');

        $permission = Permission::firstOrNew(array(
		    'name'=>'read-menu-aktiva-tetap',
        ));
	    $permission->display_name = 'Read Menu Aktiva Tetap';
        $permission->save();

        $menu = Menu::firstOrNew(array(
		    'name'          =>'Penyusutan',
            'permission_id' =>$permission->id,
            'ordinal'       =>1,
            'parent_status' =>'Y'
        ));

        $menu->icon = 'md-folder';
        $menu->save();

        $permission = Permission::firstOrNew(array(
            'name'=>'read-kelompok-aktiva',
        ));

        $permission->display_name = 'Kelompok Aktiva Tetap';
        $permission->save();

        $submenu = Menu::firstOrNew(array(
            'name'          =>'Kelompok Aktiva Tetap',
            'parent_id'     =>$menu->id,
            'permission_id' =>$permission->id,
            'ordinal'       =>2,
            'parent_status' =>'N',
            'url'           =>'kelompok-aktiva',
            )
        );
        $submenu->save(); //tambah

        $permission->display_name = 'Daftar Aktiva Tetap';
        $permission->save();

        $permission = Permission::firstOrNew(array(
            'name'=>'read-aktiva-tetap',
        ));

        $submenu = Menu::firstOrNew(array(
            'name'          =>'Daftar Aktiva Tetap',
            'parent_id'     =>$menu->id,
            'permission_id' =>$permission->id,
            'ordinal'       =>2,
            'parent_status' =>'N',
            'url'           =>'aktiva-tetap',
            )
        );
        $submenu->save(); //tambah

        $permission = Permission::firstOrNew(array(
            'name'=>'read-penyusutan-aktiva-tetap',
        ));
        $permission->display_name = 'Read Penyusutan Aktiva Tetap';
        $permission->save();

        $submenu = Menu::firstOrNew(array(
            'name'          =>'Penyusutan Aktiva Tetap',
            'parent_id'     =>$menu->id,
            'permission_id' =>$permission->id,
            'ordinal'       =>2,
            'parent_status' =>'N',
            'url'           =>'penyusutan-aktiva-tetap/index',
            )
        );
        $submenu->save(); // Penyusutan Aktiva Tetap

        $permission = Permission::firstOrNew(array(
            'name'=>'read-jurnal-penyusutan-aktiva-tetap',
        ));
        $permission->display_name = 'Read Penyusutan Aktiva Tetap';
        $permission->save();

        $submenu = Menu::firstOrNew(array(
            'name'          =>'Jurnal Penyusutan Aktiva Tetap',
            'parent_id'     =>$menu->id,
            'permission_id' =>$permission->id,
            'ordinal'       =>2,
            'parent_status' =>'N',
            'url'           =>'jurnal-penyusutan-aktiva-tetap/index',
            )
        );
        $submenu->save(); //tambah


    }
}
