<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>تحديث حالة طلبك — ECAVO</title>
  <style>
    body { margin: 0; padding: 0; background: #f4f4f5; font-family: 'Segoe UI', Arial, sans-serif; direction: rtl; }
    .wrapper { max-width: 600px; margin: 32px auto; background: #ffffff; border-radius: 12px; overflow: hidden; box-shadow: 0 2px 12px rgba(0,0,0,.08); }
    .header  { background: #111827; padding: 28px 32px; text-align: center; }
    .header h1 { margin: 0; color: #ffffff; font-size: 24px; letter-spacing: 1px; }
    .header h1 span { color: #f59e0b; }
    .body    { padding: 32px; }
    .body h2 { margin: 0 0 8px; color: #111827; font-size: 20px; }
    .body p  { color: #4b5563; line-height: 1.7; }
    .status-badge { display: inline-block; background: #f0fdf4; color: #16a34a; border: 1px solid #bbf7d0; padding: 6px 16px; border-radius: 999px; font-size: 14px; font-weight: 600; margin: 16px 0; }
    .info-box { background: #f9fafb; border: 1px solid #e5e7eb; border-radius: 8px; padding: 16px 20px; margin: 20px 0; }
    .info-box p { margin: 6px 0; font-size: 14px; color: #374151; }
    .info-box strong { color: #111827; }
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
      <h2>تحديث حالة طلبك 📦</h2>
      <p>مرحباً <strong>{{ $order->guest_name }}</strong>،</p>
      <p>نُعلمك بأن حالة طلبك قد تغيّرت.</p>

      <div class="info-box">
        <p><strong>رقم الطلب:</strong> #{{ $order->id }}</p>
        <p><strong>الحالة السابقة:</strong> {{ $oldStatus }}</p>
        <p><strong>الحالة الجديدة:</strong> <span class="status-badge">{{ $order->status }}</span></p>
        <p><strong>الإجمالي:</strong> ${{ number_format($order->total, 2) }}</p>
      </div>

      <p>يمكنك متابعة طلبك من خلال الرابط التالي في حسابك على المتجر.</p>
    </div>

    <div class="footer">
      <p>ECAVO — متجرك الإلكتروني الموثوق</p>
      <p>هذا البريد أُرسل تلقائياً، يُرجى عدم الرد عليه.</p>
    </div>
  </div>
</body>
</html>
