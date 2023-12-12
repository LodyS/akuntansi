<?php
  namespace App\Traits;

  trait CacheUpdater
  {
    protected static function boot()
    {
        parent::boot();

        static::saved(function ($model) {
          $model->updateCache();
        });

        static::deleted(function ($model) {
          $model->updateCache();
        });
    }

  }
?>
