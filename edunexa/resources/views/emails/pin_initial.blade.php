<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PIN Awal - Bayn</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Segoe UI', Arial, sans-serif; background-color: #f4f6f9; color: #333; }
        .wrapper { max-width: 600px; margin: 40px auto; background: #ffffff; border-radius: 12px; overflow: hidden; box-shadow: 0 4px 20px rgba(0,0,0,0.08); }
        .header { background: linear-gradient(135deg, #0f172a, #1e40af); padding: 40px 30px; text-align: center; }
        .header h1 { color: #ffffff; font-size: 28px; font-weight: 700; letter-spacing: 2px; }
        .header p { color: rgba(255,255,255,0.85); font-size: 13px; margin-top: 6px; }
        .body { padding: 40px 36px; }
        .greeting { font-size: 16px; color: #444; margin-bottom: 16px; }
        .message { font-size: 15px; color: #555; line-height: 1.7; margin-bottom: 28px; }
        .pin-box { background: #f0f4ff; border: 2px dashed #1e40af; border-radius: 10px; text-align: center; padding: 24px; margin-bottom: 28px; }
        .pin-box .label { font-size: 13px; color: #888; margin-bottom: 10px; text-transform: uppercase; letter-spacing: 1px; }
        .pin-box .code { font-size: 42px; font-weight: 800; letter-spacing: 12px; color: #1e40af; }
        .expiry { background: #fff8e1; border-left: 4px solid #f59e0b; padding: 12px 16px; border-radius: 6px; font-size: 14px; color: #92400e; margin-bottom: 28px; }
        .warning { font-size: 13px; color: #888; line-height: 1.6; }
        .footer { background: #f8f8f8; text-align: center; padding: 20px; font-size: 12px; color: #aaa; border-top: 1px solid #eee; }
        .footer span { color: #1e40af; font-weight: 600; }
    </style>
</head>
<body>
    <div class="wrapper">
        <div class="header">
            <h1>BAYN</h1>
            <p>Sistem Manajemen Internal</p>
        </div>
        <div class="body">
            <p class="greeting">Halo,</p>
            <p class="message">
                Selamat datang di sistem internal <strong>Bayn</strong>!
                Akun Anda telah berhasil dibuat. Berikut adalah PIN awal Anda untuk masuk ke sistem.
                Anda <strong>wajib mengganti PIN</strong> ini setelah login pertama kali.
            </p>

            <div class="pin-box">
                <div class="label">PIN Awal Anda</div>
                <div class="code">{{ $otp }}</div>
            </div>

            <div class="expiry">
                ⚠️ PIN ini bersifat <strong>sementara</strong>. Segera ganti PIN Anda setelah login untuk keamanan akun.
            </div>

            <p class="warning">
                Jika Anda tidak merasa mendaftar di sistem Bayn, segera hubungi administrator.
            </p>
        </div>
        <div class="footer">
            &copy; {{ date('Y') }} <span>Bayn</span>. Seluruh hak cipta dilindungi.
        </div>
    </div>
</body>
</html>
