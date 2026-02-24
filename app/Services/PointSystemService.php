<?php

namespace App\Services;

use App\Models\User;
use App\Models\Order;

class PointSystemService
{
    // Constants for business logic
    private const POINTS_CONVERSION_RATE = 10000; // 1 point for every Rp 10,000 spent
    private const MEMBERSHIP_GOLD_THRESHOLD = 10000000;  // 10 million
    private const MEMBERSHIP_PLATINUM_THRESHOLD = 50000000; // 50 million

    /**
     * Updates user's total spent, awards points, and updates membership level based on a completed order.
     *
     * @param Order $order
     * @return void
     */
    public function processCompletedOrder(Order $order): void
    {
        $user = $order->user;

        if (!$user) {
            return;
        }

        // 1. Update Total Spent
        $user->total_spent += $order->total_amount;

        // 2. Calculate and award points
        $pointsEarned = floor($order->total_amount / self::POINTS_CONVERSION_RATE);
        if ($pointsEarned > 0) {
            $user->points += $pointsEarned;
        }

        // 3. Update membership level
        $this->updateMembershipLevel($user);

        // 4. Save all changes to the user
        $user->save();
    }

    /**
     * Updates the membership level of a user based on their total spending.
     *
     * @param User $user
     * @return void
     */
    protected function updateMembershipLevel(User $user): void
    {
        $totalSpent = $user->total_spent;
        $newLevel = 'Silver'; // Default

        if ($totalSpent >= self::MEMBERSHIP_PLATINUM_THRESHOLD) {
            $newLevel = 'Platinum';
        } elseif ($totalSpent >= self::MEMBERSHIP_GOLD_THRESHOLD) {
            $newLevel = 'Gold';
        }

        $user->membership_level = $newLevel;
    }

    /**
     * A wrapper function to be called from the observer for clarity.
     * This might seem redundant now but allows for more complex logic in the future.
     *
     * @deprecated use processCompletedOrder instead
     * @param Order $order
     * @return void
     */
    public function calculateAndAwardPoints(Order $order): void
    {
        $this->processCompletedOrder($order);
    }
}
