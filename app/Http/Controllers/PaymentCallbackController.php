<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class PaymentCallbackController extends Controller
{
    public function handle(Request $request)
    {
        // 1. Verify Signature Key (Security check)
        $serverKey = config('services.midtrans.server_key');
        $hashed = hash("sha512", $request->order_id . $request->status_code . $request->gross_amount . $serverKey);
        
        if ($hashed !== $request->signature_key) {
            return response()->json(['message' => 'Invalid signature'], 403);
        }

        // Midtrans order_id looks like "5-1740322582" (id-timestamp)
        $orderId = explode('-', $request->order_id)[0];
        $order = Order::find($orderId);

        if (!$order) {
            return response()->json(['message' => 'Order not found'], 404);
        }

        // 2. Handle status
        $transactionStatus = $request->transaction_status;
        $type = $request->payment_type;
        $fraudStatus = $request->fraud_status;

        if ($transactionStatus == 'capture') {
            if ($type == 'credit_card') {
                if ($fraudStatus == 'challenge') {
                    $order->update(['status' => 'pending']);
                } else {
                    $order->update(['status' => 'processing']);
                }
            }
        } elseif ($transactionStatus == 'settlement') {
            $order->update(['status' => 'processing']);
        } elseif ($transactionStatus == 'pending') {
            $order->update(['status' => 'pending']);
        } elseif ($transactionStatus == 'deny' || $transactionStatus == 'expire' || $transactionStatus == 'cancel') {
            $order->update(['status' => 'cancelled']);
        }

        return response()->json(['message' => 'Callback handled successfully']);
    }
}
