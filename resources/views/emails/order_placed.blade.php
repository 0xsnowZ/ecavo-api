<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Order Confirmed — ECAVO</title>
  <style>
    * { box-sizing: border-box; }
    body {
      margin: 0; padding: 0;
      background: #f0f2f5;
      font-family: 'Segoe UI', Arial, sans-serif;
      color: #374151;
    }
    .wrapper {
      max-width: 620px;
      margin: 32px auto;
      background: #ffffff;
      border-radius: 16px;
      overflow: hidden;
      box-shadow: 0 4px 24px rgba(0,0,0,.10);
    }

    /* ── Header ── */
    .header {
      background: linear-gradient(135deg, #1D3557 0%, #0f2240 100%);
      padding: 32px;
      text-align: center;
    }
    .header .badge {
      display: inline-block;
      vertical-align: middle;
      margin-left: 16px;
      background: #16a34a;
      color: #fff;
      font-size: 13px;
      font-weight: 600;
      padding: 6px 18px;
      border-radius: 999px;
      letter-spacing: .5px;
    }

    /* ── Hero ── */
    .hero { padding: 32px 32px 0; }
    .hero h2 { margin: 0 0 8px; font-size: 22px; color: #111827; }
    .hero p  { margin: 0; color: #6b7280; line-height: 1.7; font-size: 15px; }

    /* ── Info Box ── */
    .section { padding: 24px 32px 0; }
    .section-title {
      font-size: 11px; font-weight: 700; text-transform: uppercase;
      letter-spacing: 1px; color: #9ca3af; margin-bottom: 12px;
    }
    .info-grid {
      background: #f9fafb;
      border: 1px solid #e5e7eb;
      border-radius: 10px;
      padding: 16px 20px;
      display: grid;
      gap: 8px;
    }
    .info-row { display: flex; justify-content: space-between; align-items: center; font-size: 14px; }
    .info-row .label { color: #6b7280; }
    .info-row .value { color: #111827; font-weight: 600; text-align: right; max-width: 60%; }
    .info-row .value.highlight { color: #f59e0b; }
    .info-row .value.green { color: #16a34a; }

    /* ── Divider ── */
    .divider { height: 1px; background: #f3f4f6; margin: 24px 32px 0; }

    /* ── Items Table ── */
    .items-section { padding: 24px 32px 0; }
    table { width: 100%; border-collapse: collapse; }
    thead tr { background: #f3f4f6; }
    th {
      padding: 10px 12px; font-size: 12px; font-weight: 700;
      text-transform: uppercase; letter-spacing: .5px; color: #6b7280;
      text-align: left;
    }
    th:last-child { text-align: right; }
    td { padding: 14px 12px; border-bottom: 1px solid #f3f4f6; font-size: 14px; vertical-align: top; }
    td:last-child { text-align: right; white-space: nowrap; font-weight: 600; color: #111827; }
    .product-name { font-weight: 600; color: #111827; display: block; margin-bottom: 3px; }
    .variant-tag {
      display: inline-block;
      background: #eff6ff;
      color: #2563eb;
      border: 1px solid #bfdbfe;
      font-size: 11px;
      font-weight: 600;
      padding: 2px 8px;
      border-radius: 999px;
    }
    .unit-price { display: block; font-size: 12px; color: #9ca3af; margin-top: 2px; }

    /* ── Totals ── */
    .totals {
      background: #f9fafb;
      border-top: 2px solid #e5e7eb;
      padding: 16px 12px;
    }
    .totals-row { display: flex; justify-content: space-between; font-size: 14px; padding: 4px 0; color: #6b7280; }
    .totals-row.discount { color: #16a34a; }
    .totals-row.grand-total {
      margin-top: 10px; padding-top: 12px;
      border-top: 1px solid #e5e7eb;
      font-size: 18px; font-weight: 800; color: #111827;
    }

    /* ── CTA Button ── */
    .cta-section { padding: 28px 32px; text-align: center; }
    .cta-btn {
      display: inline-block;
      background: linear-gradient(135deg, #f59e0b, #d97706);
      color: #fff;
      text-decoration: none;
      padding: 14px 36px;
      border-radius: 10px;
      font-size: 15px;
      font-weight: 700;
      letter-spacing: .3px;
    }

    /* ── Footer ── */
    .footer {
      background: linear-gradient(135deg, #1D3557, #0f2240);
      padding: 24px 32px;
      text-align: center;
    }
    .footer p { margin: 4px 0; font-size: 12px; color: #6b7280; }
    .footer .brand { font-size: 16px; font-weight: 700; color: #E63946; letter-spacing: 2px; margin-bottom: 8px; }
  </style>
</head>
<body>
<div class="wrapper">

  {{-- ── Header ── --}}
  <div class="header">
    <div style="display:inline-block; vertical-align:middle; background:#ffffff; border-radius:10px; padding:10px 22px 10px 18px; line-height:1;">
      <span style="font-family:Arial Black,Arial,sans-serif; font-weight:900; font-size:30px; color:#1D3557; letter-spacing:-1px;">E</span><span style="font-family:Arial Black,Arial,sans-serif; font-weight:900; font-size:30px; color:#E63946; letter-spacing:-1px;">CAVO</span>
    </div>
    <div class="badge">✓ Order Confirmed</div>
  </div>

  {{-- ── Hero ── --}}
  <div class="hero">
    <h2>Thank you, {{ $order->guest_name }}! 🎉</h2>
    <p>
      Your order <strong>#{{ $order->id }}</strong> has been received and is now being processed.
      You'll receive another email when it ships.
    </p>
  </div>

  {{-- ── Order Info ── --}}
  <div class="section">
    <div class="section-title">Order Details</div>
    <div class="info-grid">
      <div class="info-row">
        <span class="label">Order Number</span>
        <span class="value highlight">#{{ $order->id }}</span>
      </div>
      <div class="info-row">
        <span class="label">Date</span>
        <span class="value">{{ $order->created_at->format('d M Y, H:i') }}</span>
      </div>
      <div class="info-row">
        <span class="label">Status</span>
        <span class="value green">✓ Placed</span>
      </div>
      <div class="info-row">
        <span class="label">Payment Method</span>
        <span class="value">{{ ucfirst($order->payment_method ?? 'Cash on Delivery') }}</span>
      </div>
    </div>
  </div>

  {{-- ── Shipping Info ── --}}
  <div class="section">
    <div class="section-title">Shipping Information</div>
    <div class="info-grid">
      <div class="info-row">
        <span class="label">Name</span>
        <span class="value">{{ $order->guest_name }}</span>
      </div>
      <div class="info-row">
        <span class="label">Phone</span>
        <span class="value">{{ $order->guest_phone }}</span>
      </div>
      @if($order->guest_email)
      <div class="info-row">
        <span class="label">Email</span>
        <span class="value">{{ $order->guest_email }}</span>
      </div>
      @endif
      <div class="info-row">
        <span class="label">Delivery Address</span>
        <span class="value">{{ $order->guest_address }}</span>
      </div>
    </div>
  </div>

  {{-- ── Items ── --}}
  <div class="items-section">
    <div class="section-title">Items Ordered</div>
    <table>
      <thead>
        <tr>
          <th>Product</th>
          <th style="text-align:center">Qty</th>
          <th>Total</th>
        </tr>
      </thead>
      <tbody>
        @foreach ($order->items as $item)
        @php
          // Split "Product Name (Variant)" back apart for display
          preg_match('/^(.*?)(?:\s*\((.+)\))?$/', $item->product_name, $m);
          $pName   = trim($m[1] ?? $item->product_name);
          $variant = trim($m[2] ?? '');
        @endphp
        <tr>
          <td>
            <span class="product-name">{{ $pName }}</span>
            @if($variant)
              <span class="variant-tag">{{ $variant }}</span>
            @endif
            <span class="unit-price">Unit price: ${{ number_format($item->unit_price, 2) }}</span>
          </td>
          <td style="text-align:center; font-weight:600; color:#111827;">{{ $item->qty }}</td>
          <td>${{ number_format($item->total, 2) }}</td>
        </tr>
        @endforeach
      </tbody>
      <tfoot>
        <tr>
          <td colspan="3" style="padding:0">
            <div class="totals">
              <div class="totals-row">
                <span>Subtotal</span>
                <span>${{ number_format($order->subtotal, 2) }}</span>
              </div>
              <div class="totals-row">
                <span>Delivery Fee</span>
                <span>${{ number_format($order->delivery_fee, 2) }}</span>
              </div>
              @if ($order->discount > 0)
              <div class="totals-row discount">
                <span>Discount ({{ $order->coupon_code }})</span>
                <span>-${{ number_format($order->discount, 2) }}</span>
              </div>
              @endif
              <div class="totals-row grand-total">
                <span>Total</span>
                <span>${{ number_format($order->total, 2) }}</span>
              </div>
            </div>
          </td>
        </tr>
      </tfoot>
    </table>
  </div>

  @if($order->notes)
  {{-- ── Notes ── --}}
  <div class="section" style="padding-bottom:0">
    <div class="section-title">Your Notes</div>
    <div class="info-grid">
      <p style="margin:0; font-size:14px; color:#374151; font-style:italic;">{{ $order->notes }}</p>
    </div>
  </div>
  @endif

  {{-- ── CTA ── --}}
  <div class="cta-section">
    <a href="{{ config('app.frontend_url', 'http://localhost:5173') }}/orders" class="cta-btn">
      Track My Order
    </a>
    <p style="margin-top:16px; font-size:13px; color:#9ca3af;">
      Questions? Reply to this email or contact our support team.
    </p>
  </div>

  {{-- ── Footer ── --}}
  <div class="footer">
    <div class="brand">ECAVO</div>
    <p>Your trusted online marketplace.</p>
    <p>This is an automated email — please do not reply directly.</p>
  </div>

</div>
</body>
</html>
