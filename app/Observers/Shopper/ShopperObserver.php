<?php

namespace App\Observers\Shopper;

use Carbon\Carbon;
use App\Models\Shopper\Shopper;
use App\Observers\BaseObserver;

/**
 * Class ShopperObserver
 * @package App\Observers\Shopper
 */
class ShopperObserver extends BaseObserver
{

    /**
     * @desc Set unique UUID for model
     * @param $model
     * @return mixed|void
     */
    public function creating($model)
    {
        parent::creating($model);
        $model->check_in = now();
    }

    /**
     * Handle the Shopper "created" event.
     *
     * @param  \App\Models\Shopper\Shopper  $shopper
     * @return void
     */
    public function created(Shopper $shopper): void
    {
        //
    }

    /**
     * Handle the Shopper "updated" event.
     *
     * @param  \App\Models\Shopper\Shopper  $shopper
     * @return void
     */
    public function updated(Shopper $shopper): void
    {
        //
    }

    /**
     * Handle the Shopper "deleted" event.
     *
     * @param  \App\Models\Shopper\Shopper  $shopper
     * @return void
     */
    public function deleted(Shopper $shopper): void
    {
        //
    }

    /**
     * Handle the Shopper "restored" event.
     *
     * @param  \App\Models\Shopper\Shopper  $shopper
     * @return void
     */
    public function restored(Shopper $shopper): void
    {
        //
    }

    /**
     * Handle the Shopper "force deleted" event.
     *
     * @param  \App\Models\Shopper\Shopper  $shopper
     * @return void
     */
    public function forceDeleted(Shopper $shopper): void
    {
        //
    }
}
