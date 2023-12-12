# Sistem Informasi Manajemen Akuntansi


### Help Desk

A step by step series of examples that tell you how to get a development env running


* Clearing Cache

```
php artisan config:clear
```
```
php artisan config:cache
```

* Migrasi Tabel

```
php artisan migrate
```


* Seeding Permission

```
php artisan db:seed --class=LaratrustSeeder
```
* Seeding Menu

```
php artisan db:seed --class=MenuSeeder
```

* Buat Tabel

```
php artisan make:migration create_nama_table
```


* Tambah Kolom Tabel

```
php artisan make:migration add_column_nama_table
```

* Buat CRUD

```
php artisan generate:crud nama_table```
