<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Reset Your Password</title>
    <style>
        body { font-family: Arial, sans-serif; background-color: #f4f4f4; padding: 20px; }
        .container { max-width: 600px; margin: 0 auto; background: #ffffff; padding: 30px; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.1); }
        .btn { display: inline-block; padding: 12px 24px; background-color: #f97316; color: #ffffff; text-decoration: none; border-radius: 6px; font-weight: bold; margin-top: 20px; }
        .footer { margin-top: 30px; font-size: 12px; color: #666; }
    </style>
</head>
<body>
    <div class="container">
        <h2>Password Reset Request</h2>
        <p>Hello,</p>
        <p>You are receiving this email because we received a password reset request for your account.</p>
        <a href="{{ $resetUrl }}" class="btn">Reset Password</a>
        <p style="margin-top: 20px;">If you did not request a password reset, no further action is required.</p>
        
        <div class="footer">
            <p>If you're having trouble clicking the "Reset Password" button, copy and paste the URL below into your web browser:</p>
            <p><a href="{{ $resetUrl }}">{{ $resetUrl }}</a></p>
        </div>
    </div>
</body>
</html>
