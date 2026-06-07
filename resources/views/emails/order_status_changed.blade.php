<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Order Status Update — ECAVO</title>
  <style>
    * { box-sizing: border-box; }
    body { margin: 0; padding: 0; background: #f0f2f5; font-family: 'Segoe UI', Arial, sans-serif; color: #374151; }
    .wrapper { max-width: 600px; margin: 32px auto; background: #ffffff; border-radius: 16px; overflow: hidden; box-shadow: 0 4px 24px rgba(0,0,0,.10); }
    .header { background: linear-gradient(135deg, #1D3557 0%, #0f2240 100%); padding: 32px; text-align: center; }
    .header .badge { display: inline-block; vertical-align: middle; margin-left: 16px; background: #E63946; color: #fff; font-size: 13px; font-weight: 600; padding: 6px 18px; border-radius: 999px; letter-spacing: .5px; }
    .body { padding: 32px; }
    .body h2 { margin: 0 0 8px; color: #1D3557; font-size: 20px; font-weight: 800; }
    .body p  { color: #4b5563; line-height: 1.7; font-size: 15px; }
    .status-box { background: linear-gradient(135deg, #fff7ed, #fff); border: 1px solid #F4A261; border-radius: 12px; padding: 20px 24px; margin: 20px 0; }
    .status-from { font-size: 13px; color: #9ca3af; margin-bottom: 8px; }
    .status-arrow { font-size: 24px; color: #F4A261; text-align: center; padding: 8px 0; }
    .status-to { display: flex; align-items: center; gap: 10px; }
    .status-badge { display: inline-block; background: #E63946; color: #fff; padding: 7px 18px; border-radius: 999px; font-size: 14px; font-weight: 700; letter-spacing: .5px; }
    .info-grid { background: #f9fafb; border: 1px solid #e5e7eb; border-radius: 10px; padding: 16px 20px; margin: 20px 0; }
    .info-row { display: flex; justify-content: space-between; align-items: center; font-size: 14px; padding: 5px 0; border-bottom: 1px solid #f3f4f6; }
    .info-row:last-child { border-bottom: none; }
    .info-row .label { color: #6b7280; }
    .info-row .value { color: #111827; font-weight: 600; }
    .cta-section { padding: 24px 32px; text-align: center; }
    .cta-btn { display: inline-block; background: linear-gradient(135deg, #E63946, #C1121F); color: #fff; text-decoration: none; padding: 13px 34px; border-radius: 10px; font-size: 15px; font-weight: 700; }
    .footer { background: linear-gradient(135deg, #1D3557, #0f2240); padding: 24px 32px; text-align: center; }
    .footer p { margin: 4px 0; font-size: 12px; color: #94a3b8; }
    .footer .brand { font-size: 15px; font-weight: 700; color: #E63946; letter-spacing: 2px; margin-bottom: 6px; }
  </style>
</head>
<body>
<div class="wrapper">

  {{-- Header --}}
  <div class="header">
    <div style="display:inline-block; vertical-align:middle; background:#ffffff; border-radius:10px; padding:10px 22px 10px 18px; line-height:1;">
      <span style="font-family:Arial Black,Arial,sans-serif; font-weight:900; font-size:30px; color:#1D3557; letter-spacing:-1px;">E</span><span style="font-family:Arial Black,Arial,sans-serif; font-weight:900; font-size:30px; color:#E63946; letter-spacing:-1px;">CAVO</span>
    </div>
    <div class="badge">📦 Order Status Update</div>
  </div>

  {{-- Body --}}
  <div class="body">
    <h2>Your order status has changed!</h2>
    <p>Hello <strong>{{ $order->guest_name }}</strong>, we have an update on your order.</p>

    {{-- Status Change --}}
    <div class="status-box">
      <div class="status-from">Previous status: <strong>{{ $oldStatus }}</strong></div>
      <div class="status-arrow">↓</div>
      <div class="status-to">
        <span>New status:</span>
        <span class="status-badge">{{ ucfirst(str_replace('_', ' ', $order->status)) }}</span>
      </div>
    </div>

    {{-- Order Details --}}
    <div class="info-grid">
      <div class="info-row">
        <span class="label">Order Number</span>
        <span class="value">#{{ $order->id }}</span>
      </div>
      <div class="info-row">
        <span class="label">Date Placed</span>
        <span class="value">{{ $order->created_at->format('d M Y') }}</span>
      </div>
      <div class="info-row">
        <span class="label">Delivery Address</span>
        <span class="value">{{ $order->guest_address }}</span>
      </div>
      <div class="info-row">
        <span class="label">Order Total</span>
        <span class="value">${{ number_format($order->total, 2) }}</span>
      </div>
    </div>

    <p style="font-size:14px; color:#6b7280;">
      You can track the latest status of your order by visiting your order history on our store.
    </p>
  </div>

  {{-- CTA --}}
  <div class="cta-section">
    <a href="{{ config('app.frontend_url', 'http://localhost:5173') }}/orders" class="cta-btn">
      Track My Order
    </a>
  </div>

  {{-- Footer --}}
  <div class="footer">
    <div class="brand">ECAVO</div>
    <p>Your trusted online marketplace.</p>
    <p>This is an automated email — please do not reply directly.</p>
  </div>

</div>
</body>
</html>
