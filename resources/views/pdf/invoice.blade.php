<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Invoice #{{ $order->id }} - Pitocom</title>
    <style>
        body { font-family: 'Helvetica', sans-serif; color: #333; line-height: 1.5; margin: 0; padding: 0; }
        .container { padding: 40px; }
        .header { margin-bottom: 40px; }
        .header table { width: 100%; border-collapse: collapse; }
        .logo { font-size: 32px; font-weight: 900; color: #0ea5e9; letter-spacing: -1px; }
        .invoice-title { text-align: right; font-size: 24px; font-weight: bold; color: #94a3b8; text-transform: uppercase; }
        
        .info-section { margin-bottom: 40px; }
        .info-table { width: 100%; border-collapse: collapse; }
        .info-table td { vertical-align: top; width: 50%; }
        .label { font-size: 10px; font-weight: bold; color: #94a3b8; text-transform: uppercase; margin-bottom: 5px; }
        .value { font-size: 14px; font-weight: bold; color: #1e293b; }
        
        .items-table { width: 100%; border-collapse: collapse; margin-bottom: 40px; }
        .items-table th { background: #f8fafc; border-bottom: 2px solid #e2e8f0; padding: 12px 10px; text-align: left; font-size: 11px; font-weight: bold; color: #64748b; text-transform: uppercase; }
        .items-table td { padding: 15px 10px; border-bottom: 1px solid #f1f5f9; font-size: 13px; }
        
        .total-section { text-align: right; }
        .total-table { width: 300px; margin-left: auto; border-collapse: collapse; }
        .total-table td { padding: 8px 10px; font-size: 14px; }
        .total-row { font-size: 20px; font-weight: 900; color: #0ea5e9; }
        
        .warranty-box { background: #f0f9ff; border: 1px solid #bae6fd; padding: 20px; border-radius: 10px; margin-top: 40px; }
        .warranty-title { font-size: 14px; font-weight: bold; color: #0369a1; margin-bottom: 10px; }
        .warranty-text { font-size: 11px; color: #0c4a6e; }
        
        .footer { position: fixed; bottom: 40px; left: 40px; right: 40px; text-align: center; border-top: 1px solid #f1f5f9; padding-top: 20px; font-size: 10px; color: #94a3b8; }
    </style>
</head>
<body>
    <div class="container">
        {{-- Header --}}
        <div class="header">
            <table>
                <tr>
                    <td class="logo">PITOCOM</td>
                    <td class="invoice-title">Official Invoice</td>
                </tr>
            </table>
        </div>

        {{-- Info Info --}}
        <div class="info-section">
            <table class="info-table">
                <tr>
                    <td>
                        <div class="label">Billed To:</div>
                        <div class="value">{{ $order->user->name }}</div>
                        <div class="value" style="font-weight: normal; font-size: 12px; margin-top: 5px;">
                            {{ $order->address }}
                        </div>
                    </td>
                    <td style="text-align: right;">
                        <div class="label">Invoice Details:</div>
                        <div class="value">Order #{{ $order->id }}</div>
                        <div class="value" style="font-weight: normal; font-size: 12px;">Date: {{ $order->order_date->format('d F Y') }}</div>
                        <div class="value" style="font-weight: normal; font-size: 12px; color: #10b981; margin-top: 5px;">Status: PAID / COMPLETED</div>
                    </td>
                </tr>
            </table>
        </div>

        {{-- Items --}}
        <table class="items-table">
            <thead>
                <tr>
                    <th>Item Description</th>
                    <th style="text-align: center;">Qty</th>
                    <th style="text-align: right;">Price</th>
                    <th style="text-align: right;">Total</th>
                </tr>
            </thead>
            <tbody>
                @foreach($order->items as $item)
                <tr>
                    <td>
                        <div style="font-weight: bold; color: #1e293b;">{{ $item->product->name }}</div>
                        @if($item->variation)
                            <div style="font-size: 10px; color: #64748b;">Variation: {{ $item->variation->variation_name }}</div>
                        @endif
                    </td>
                    <td style="text-align: center;">{{ $item->quantity }}</td>
                    <td style="text-align: right;">Rp {{ number_format($item->price_at_purchase, 0, ',', '.') }}</td>
                    <td style="text-align: right; font-weight: bold;">Rp {{ number_format($item->price_at_purchase * $item->quantity, 0, ',', '.') }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>

        {{-- Totals --}}
        <div class="total-section">
            <table class="total-table">
                <tr>
                    <td style="color: #64748b;">Subtotal</td>
                    <td style="text-align: right; font-weight: bold;">Rp {{ number_format($order->total_amount, 0, ',', '.') }}</td>
                </tr>
                <tr>
                    <td style="color: #64748b;">Shipping ({{ $order->courier }})</td>
                    <td style="text-align: right; font-weight: bold;">Rp {{ number_format($order->shipping_cost, 0, ',', '.') }}</td>
                </tr>
                <tr>
                    <td colspan="2" style="padding: 10px 0;"><div style="height: 1px; background: #e2e8f0;"></div></td>
                </tr>
                <tr class="total-row">
                    <td>Grand Total</td>
                    <td style="text-align: right;">Rp {{ number_format($order->total_amount + $order->shipping_cost, 0, ',', '.') }}</td>
                </tr>
            </table>
        </div>

        {{-- Warranty Card Section --}}
        <div class="warranty-box">
            <div class="warranty-title">🛡️ Official Warranty Card</div>
            <p class="warranty-text">
                Simpan invoice ini sebagai kartu garansi resmi. Produk yang tertera di atas dilindungi garansi selama 1 tahun (atau sesuai kebijakan brand masing-masing) terhitung sejak tanggal invoice ini diterbitkan.
                <br><br>
                <strong>Syarat & Ketentuan:</strong>
                1. Garansi berlaku untuk cacat pabrik, bukan kesalahan penggunaan / fisik.
                2. Segel toko/brand harus dalam keadaan utuh.
                3. Klaim dapat dilakukan melalui portal Pitocom Care di website kami.
            </p>
        </div>

        <div class="footer">
            Pitocom High Performance Hardware - Jakarta, Indonesia <br>
            www.pitocom.com | +62 822-8143-9842 | support@pitocom.com
        </div>
    </div>
</body>
</html>
