<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verifikasi Email - Bayn</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Segoe UI', Arial, sans-serif; background-color: #f4f6f9; color: #333; }
        .wrapper { max-width: 600px; margin: 40px auto; background: #ffffff; border-radius: 12px; overflow: hidden; box-shadow: 0 4px 20px rgba(0,0,0,0.08); }
        .header { background: linear-gradient(135deg, #6366f1, #8b5cf6); padding: 40px 30px; text-align: center; }
        .header h1 { color: #ffffff; font-size: 28px; font-weight: 700; letter-spacing: 2px; }
        .header p { color: rgba(255,255,255,0.85); font-size: 13px; margin-top: 6px; }
        .body { padding: 40px 36px; }
        .greeting { font-size: 16px; color: #444; margin-bottom: 16px; }
        .message { font-size: 15px; color: #555; line-height: 1.7; margin-bottom: 28px; }
        .otp-box { background: #f0f0ff; border: 2px dashed #6366f1; border-radius: 10px; text-align: center; padding: 24px; margin-bottom: 28px; }
        .otp-box .label { font-size: 13px; color: #888; margin-bottom: 10px; text-transform: uppercase; letter-spacing: 1px; }
        .otp-box .code { font-size: 42px; font-weight: 800; letter-spacing: 12px; color: #6366f1; }
        .expiry { background: #fff8e1; border-left: 4px solid #f59e0b; padding: 12px 16px; border-radius: 6px; font-size: 14px; color: #92400e; margin-bottom: 28px; }
        .warning { font-size: 13px; color: #888; line-height: 1.6; }
        .footer { background: #f8f8f8; text-align: center; padding: 20px; font-size: 12px; color: #aaa; border-top: 1px solid #eee; }
        .footer span { color: #6366f1; font-weight: 600; }
    </style>
</head>
<body>
    <div class="wrapper">
        <div class="header">
            <h1>BAYN</h1>
            <p>Platform Terpercaya Anda</p>
        </div>
        <div class="body">
            <p class="greeting">Halo,</p>
            <p class="message">
                Selamat datang di <strong>Bayn</strong>! 
                Untuk menyelesaikan proses pendaftaran, masukkan kode OTP berikut untuk memverifikasi alamat email Anda.
            </p>

            <div class="otp-box">
                <div class="label">Kode Verifikasi Email</div>
                <div class="code">{{ $otp }}</div>
            </div>

            <div class="expiry">
                ⏱ Kode ini hanya berlaku selama <strong>{{ $expiryMinutes }} menit</strong>. Segera gunakan sebelum kedaluwarsa.
            </div>

            <p class="warning">
                Jika Anda tidak merasa mendaftar di Bayn, abaikan email ini.
                Akun Anda tidak akan dibuat tanpa verifikasi.
            </p>
        </div>
        <div class="footer">
            &copy; {{ date('Y') }} <span>Bayn</span>. Seluruh hak cipta dilindungi.
        </div>
    </div>
</body>
</html>
