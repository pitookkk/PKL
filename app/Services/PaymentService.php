<?php

namespace App\Services;

use Midtrans\Config;
use Midtrans\Snap;
use App\Models\Order;

class PaymentService
{
    public function __construct()
    {
        // Set Midtrans configuration
        Config::$serverKey = config('services.midtrans.server_key');
        Config::$isProduction = config('services.midtrans.is_production');
        Config::$isSanitized = config('services.midtrans.is_sanitized');
        Config::$is3ds = config('services.midtrans.is_3ds');
    }

    /**
     * Get Midtrans Snap Token for an order.
     */
    public function getSnapToken(Order $order)
    {
        $params = [
            'transaction_details' => [
                'order_id' => $order->id . '-' . time(), // Unique order ID for Midtrans
                'gross_amount' => (int) ($order->total_amount + $order->shipping_cost),
            ],
            'customer_details' => [
                'first_name' => $order->user->name,
                'email' => $order->user->email,
                'phone' => $order->user->phone_number,
            ],
            // You can add item_details here if needed for more detailed receipts
        ];

        return Snap::getSnapToken($params);
    }
}
