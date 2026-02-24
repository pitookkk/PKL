<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Auth;

class InvoiceController extends Controller
{
    /**
     * Generate and download the invoice PDF.
     */
    public function download(Order $order)
    {
        // Security check: Only the owner of the order or an admin can download
        if (Auth::id() !== $order->user_id && !Auth::user()->isAdmin()) {
            abort(403, 'Unauthorized action.');
        }

        // Only allow download for completed orders
        if ($order->status !== 'completed') {
            return back()->with('error', 'Invoice can only be generated for completed orders.');
        }

        $order->load(['user', 'items.product', 'items.variation']);

        // Load the view and pass the data
        $pdf = Pdf::loadView('pdf.invoice', compact('order'));

        // Return the PDF for download
        return $pdf->download("Pitocom_Invoice_{$order->id}.pdf");
    }
}
