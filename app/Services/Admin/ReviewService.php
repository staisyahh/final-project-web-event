<?php

namespace App\Services\Admin;

use App\Models\Review;

class ReviewService
{
    /**
     * Delete a review.
     *
     * @param int $reviewId
     * @return void
     */
    public function delete(int $reviewId): void
    {
        $review = Review::findOrFail($reviewId);
        $review->delete();
    }
}
