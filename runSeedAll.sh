
clear;
php artisan config:cache;
php artisan migrate:fresh --seed;
