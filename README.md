# Sistem Informasi Manajemen Akuntansi


## Getting Started

These instructions will get you a copy of the project up and running on your local machine for development and testing purposes. See deployment for notes on how to deploy the project on a live system.

### Requirements

What things you need to install the project and how to install them

* [Xampp / Apache Server](https://www.apachefriends.org/download.html) - Compatible with php 7.3 version
* [MySQL](https://www.mysql.org/) - Compatible with Phpmyadmin / Mysql
* [Composer](https://getcomposer.org/) - Composer with Latest Version


### Installing

A step by step series of examples that tell you how to get a development env running

* Clone this project

```
git clone git@219.83.123.134:akuntansi_stable5.git
```
* Install with Composer

```
composer install
```
```
composer dump-autoload
```

* Setting your environment ( Copy your .env.example to .env )

```
cp .env.example .env 
```
* Run Artisan
```
php artisan key:generate
```
```
php artisan config:clear
```
```
php artisan config:cache
```


End with an example of getting some data out of the system or using it for a little demo

## Running the tests

Explain how to run the automated tests for this system

### Setup Tables,Menus and Permission Role


* Run Artisan

```
php artisan migrate
```
```
php artisan db:seed --class=LaratrustSeeder
```
```
php artisan db:seed --class=MenuSeeder
```

### Setup with dummy data

* Run Artisan

```
php artisan db:seed
```

## Deployment

Add additional notes about how to deploy this on a live system

## Built With

* Build with Laravel 5.7
* Use MySql Database
* Use Bootstrap 4.x
* Compatible Browser with IE10, IE11, Firefox, Safari, Opera, Chrome, Edge
* Responsive Layout
* Use Datatables Latest Version


## Version

We have version 1.0.0 . For the versions available, use branch Master for Production.

## Authors

* **Morbis / Medika Digital Nusantara** -  [Show Website](http://morbis.id)

Show the contributor list with redmine ,  [Show Project Task](http://support.mbi.biz.id/issues/7210)


