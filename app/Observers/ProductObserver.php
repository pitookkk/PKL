<?php

namespace App\Observers;

use App\Models\Product;
use Illuminate\Support\Facades\Log;

class ProductObserver
{
    /**
     * Handle the Product "updated" event.
     *
     * @param  \App\Models\Product  $product
     * @return void
     */
    public function updated(Product $product): void
    {
        // Check if a flash sale was just activated for this product.
        // We check if the flash sale price was changed and if the sale is currently active.
        $justActivated = $product->isDirty('flash_sale_price') || $product->isDirty('flash_sale_start');

        if ($justActivated && $product->is_flash_sale_active) {
            
            // Eager load users who have this product in their wishlist.
            $product->load('wishlists.user');

            if ($product->wishlists->isNotEmpty()) {
                Log::info("[Notification Simulation] Flash sale detected for product #{$product->id} '{$product->name}'. Starting to notify users.");

                foreach ($product->wishlists as $wishlist) {
                    if ($wishlist->user) {
                        // Simulate sending a notification by writing to the log file.
                        Log::info("[Notification Simulation] Sent flash sale alert to user #{$wishlist->user->id} ('{$wishlist->user->name}') for product '{$product->name}'.");
                    }
                }
                 Log::info("[Notification Simulation] Finished notifying users for product #{$product->id}.");
            }
        }
    }

    /**
     * Handle the Product "created" event.
     */
    public function created(Product $product): void
    {
        //
    }

    /**
     * Handle the Product "deleted" event.
     */
    public function deleted(Product $product): void
    {
        //
    }

    /**
     * Handle the Product "restored" event.
     */
    public function restored(Product $product): void
    {
        //
    }

    /**
     * Handle the Product "force deleted" event.
     */
    public function forceDeleted(Product $product): void
    {
        //
    }
}
