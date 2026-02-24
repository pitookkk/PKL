<?php

namespace App\Observers;

use App\Models\Review;
use App\Models\Product;

class ReviewObserver
{
    /**
     * Handle the Review "created" event.
     */
    public function created(Review $review): void
    {
        $this->updateProductReviewStats($review->product);
    }

    /**
     * Handle the Review "deleted" event.
     */
    public function deleted(Review $review): void
    {
        $this->updateProductReviewStats($review->product);
    }

    /**
     * Handle the Review "restored" event.
     */
    public function restored(Review $review): void
    {
        $this->updateProductReviewStats($review->product);
    }

    /**
     * Handle the Review "force deleted" event.
     */
    public function forceDeleted(Review $review): void
    {
        $this->updateProductReviewStats($review->product);
    }

    /**
     * Recalculate and update the review stats for a product.
     */
    protected function updateProductReviewStats(Product $product): void
    {
        $product->total_reviews = $product->reviews()->count();
        $product->average_rating = $product->reviews()->avg('rating') ?? 0;
        $product->save();
    }
}
