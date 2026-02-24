<?php

namespace App\Observers;

use App\Models\Order;
use App\Models\CustomerAsset;
use App\Services\PointSystemService;
use App\Services\WhatsAppService;
use Carbon\Carbon;

class OrderObserver
{
    protected $pointSystemService;
    protected $whatsAppService;

    public function __construct(PointSystemService $pointSystemService, WhatsAppService $whatsAppService)
    {
        $this->pointSystemService = $pointSystemService;
        $this->whatsAppService = $whatsAppService;
    }

    /**
     * Handle the Order "created" event.
     */
    public function created(Order $order): void
    {
        // If order is created with 'completed' status, process it immediately
        if ($order->status === 'completed') {
            $this->handleCompletedOrder($order);
        }
    }

    /**
     * Handle the Order "updated" event.
     */
    public function updated(Order $order): void
    {
        // If status changed to 'completed', process it
        if ($order->isDirty('status') && $order->status === 'completed') {
            $this->handleCompletedOrder($order);
        }
    }

    /**
     * Core logic to handle a completed order (Points, Assets, Notifications)
     */
    protected function handleCompletedOrder(Order $order): void
    {
        // 1. Award loyalty points and update membership
        $this->pointSystemService->processCompletedOrder($order);

        // 2. Create customer assets from the order items
        foreach ($order->items as $item) {
            $productCategory = $item->product->category->name ?? '';

            if (in_array($productCategory, ['PC Rakitan', 'Laptop Gaming', 'Komputer', 'Laptop', 'Processor', 'Motherboard', 'Graphics Card'])) {
                
                $specifications = [
                    'brand' => $item->product->brand,
                    'price' => $item->price_at_purchase,
                ];

                CustomerAsset::create([
                    'user_id' => $order->user_id,
                    'order_id' => $order->id,
                    'device_name' => $item->product->name,
                    'specifications' => $specifications,
                    'purchase_date' => Carbon::now(),
                    'warranty_expiry' => Carbon::now()->addYear(),
                ]);
            }
        }

        // 3. Send WhatsApp notification
        $user = $order->user;
        if ($user && $user->phone_number) {
            $message = "Halo {$user->name}, pesanan Anda #{$order->id} telah selesai diproses. Terima kasih telah berbelanja di Pitocom!";
            $this->whatsAppService->sendMessage($user->phone_number, $message);
        }
    }

    /**
     * Handle the Order "deleted" event.
     */
    public function deleted(Order $order): void
    {
        //
    }

    /**
     * Handle the Order "restored" event.
     */
    public function restored(Order $order): void
    {
        //
    }

    /**
     * Handle the Order "force deleted" event.
     */
    public function forceDeleted(Order $order): void
    {
        //
    }
}
