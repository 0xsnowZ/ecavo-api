<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>تم استلام طلبك — ECAVO</title>
  <style>
    body { margin: 0; padding: 0; background: #f4f4f5; font-family: 'Segoe UI', Arial, sans-serif; direction: rtl; }
    .wrapper { max-width: 600px; margin: 32px auto; background: #ffffff; border-radius: 12px; overflow: hidden; box-shadow: 0 2px 12px rgba(0,0,0,.08); }
    .header  { background: #111827; padding: 28px 32px; text-align: center; }
    .header h1 { margin: 0; color: #ffffff; font-size: 24px; letter-spacing: 1px; }
    .header h1 span { color: #f59e0b; }
    .body    { padding: 32px; }
    .body h2 { margin: 0 0 8px; color: #111827; font-size: 20px; }
    .body p  { color: #4b5563; line-height: 1.7; }
    .info-box { background: #f9fafb; border: 1px solid #e5e7eb; border-radius: 8px; padding: 16px 20px; margin: 20px 0; }
    .info-box p { margin: 6px 0; font-size: 14px; color: #374151; }
    .info-box strong { color: #111827; }
    table  { width: 100%; border-collapse: collapse; margin: 20px 0; }
    th     { background: #f3f4f6; text-align: right; padding: 10px 14px; font-size: 13px; color: #6b7280; }
    td     { padding: 12px 14px; border-bottom: 1px solid #f3f4f6; font-size: 14px; color: #374151; }
    .total-row td { font-weight: 700; color: #111827; border-top: 2px solid #e5e7eb; border-bottom: none; }
    .footer  { background: #f9fafb; border-top: 1px solid #e5e7eb; padding: 20px 32px; text-align: center; }
    .footer p { margin: 4px 0; font-size: 12px; color: #9ca3af; }
  </style>
</head>
<body>
  <div class="wrapper">
    <div class="header">
      <h1>E<span>CAVO</span></h1>
    </div>

    <div class="body">
      <h2>شكراً على طلبك! 🎉</h2>
      <p>مرحباً <strong>{{ $order->guest_name }}</strong>،</p>
      <p>لقد تلقّينا طلبك بنجاح وهو الآن قيد المعالجة. سيصلك تحديث عند تغيير الحالة.</p>

      <div class="info-box">
        <p><strong>رقم الطلب:</strong> #{{ $order->id }}</p>
        <p><strong>تاريخ الطلب:</strong> {{ $order->created_at->format('Y-m-d H:i') }}</p>
        <p><strong>الحالة:</strong> تم الاستلام</p>
        <p><strong>عنوان التوصيل:</strong> {{ $order->guest_address }}</p>
        <p><strong>رقم الهاتف:</strong> {{ $order->guest_phone }}</p>
      </div>

      <table>
        <thead>
          <tr>
            <th>المنتج</th>
            <th>الكمية</th>
            <th>السعر</th>
          </tr>
        </thead>
        <tbody>
          @foreach ($order->items as $item)
          <tr>
            <td>{{ $item->product_name }}</td>
            <td>{{ $item->qty }}</td>
            <td>${{ number_format($item->total, 2) }}</td>
          </tr>
          @endforeach
        </tbody>
        <tfoot>
          <tr>
            <td colspan="2">رسوم التوصيل</td>
            <td>${{ number_format($order->delivery_fee, 2) }}</td>
          </tr>
          @if ($order->discount > 0)
          <tr>
            <td colspan="2">خصم ({{ $order->coupon_code }})</td>
            <td>-${{ number_format($order->discount, 2) }}</td>
          </tr>
          @endif
          <tr class="total-row">
            <td colspan="2">الإجمالي</td>
            <td>${{ number_format($order->total, 2) }}</td>
          </tr>
        </tfoot>
      </table>
    </div>

    <div class="footer">
      <p>ECAVO — متجرك الإلكتروني الموثوق</p>
      <p>هذا البريد أُرسل تلقائياً، يُرجى عدم الرد عليه.</p>
    </div>
  </div>
</body>
</html>
