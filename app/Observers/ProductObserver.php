<?php

namespace App\Observers;

use App\Models\Product;

class ProductObserver
{
    /**
     * Handle the Order "created" event.
     */
    public function created(Product $product): void
    {
        \Cache::forget('products');
    }

    /**
     * Handle the Order "updated" event.
     */
    public function updated(Product $product): void
    {
        \Cache::forget('products');
    }

    /**
     * Handle the Order "deleted" event.
     */
    public function deleted(Product $product): void
    {
        \Cache::forget('products');
    }

    /**
     * Handle the Order "restored" event.
     */
    public function restored(Product $product): void
    {
        \Cache::forget('products');
    }

    /**
     * Handle the Order "force deleted" event.
     */
    public function forceDeleted(Product $product): void
    {
    }
}
