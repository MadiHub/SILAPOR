<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <style>
        body { font-family: 'Inter', Arial, sans-serif; background: #f3f4f6; margin: 0; padding: 32px 16px; }
        .card { background: #fff; max-width: 480px; margin: 0 auto; border-radius: 16px; overflow: hidden; border: 1px solid #e5e7eb; }
        .header { background: #0b0e14; padding: 32px; text-align: center; }
        .header-icon { font-size: 28px; color: #fff; }
        .header h1 { color: #fff; font-size: 18px; margin: 12px 0 0; font-weight: 700; }
        .body { padding: 32px; }
        .body p { color: #6b7280; font-size: 14px; line-height: 1.6; margin: 0 0 16px; }
        .otp-box { background: #f3f4f6; border: 2px dashed #d1d5db; border-radius: 12px; text-align: center; padding: 24px; margin: 24px 0; }
        .otp-code { font-size: 36px; font-weight: 800; letter-spacing: 12px; color: #0b0e14; }
        .otp-note { font-size: 12px; color: #9ca3af; margin-top: 8px; }
        .footer { background: #f9fafb; padding: 20px 32px; border-top: 1px solid #f3f4f6; text-align: center; }
        .footer p { font-size: 11px; color: #9ca3af; margin: 0; }
    </style>
</head>
<body>
    <div class="card">
        <div class="header">
            <div class="header-icon">✦</div>
            <h1>SILAPOR</h1>
        </div>
        <div class="body">
            <p>Halo,</p>
            <p>Kami menerima permintaan reset password untuk akun <strong>{{ $email }}</strong>. Gunakan kode OTP berikut:</p>
            <div class="otp-box">
                <div class="otp-code">{{ $otp }}</div>
                <div class="otp-note">Berlaku selama <strong>10 menit</strong></div>
            </div>
            <p>Jika Anda tidak meminta reset password, abaikan email ini. Akun Anda tetap aman.</p>
        </div>
        <div class="footer">
            <p>&copy; {{ date('Y') }} SILAPOR. Jangan balas email ini.</p>
        </div>
    </div>
</body>
</html>