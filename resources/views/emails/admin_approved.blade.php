<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <style>
        body { font-family: Arial, sans-serif; background: #f4f6fb; margin: 0; padding: 0; }
        .container { max-width: 560px; margin: 30px auto; background: #fff; border-radius: 12px; box-shadow: 0 4px 20px rgba(0,0,0,.08); overflow: hidden; }
        .header { background: linear-gradient(135deg, #1e3c72, #2a5298); padding: 32px 30px; text-align: center; }
        .header h1 { color: #fff; margin: 0; font-size: 26px; letter-spacing: 2px; }
        .header p  { color: #a8c0e8; margin: 6px 0 0; font-size: 13px; }
        .body { padding: 30px 32px; color: #333; }
        .body h2 { color: #1e3c72; margin-top: 0; }
        .info-box { background: #f1f5fb; border-left: 4px solid #2563eb; border-radius: 6px; padding: 16px 20px; margin: 20px 0; }
        .info-box p { margin: 5px 0; font-size: 14px; }
        .info-box strong { color: #1e3c72; }
        .cred-box { background: #fff7ed; border: 1px solid #f59e0b; border-radius: 8px; padding: 16px 20px; margin: 20px 0; }
        .cred-box p { margin: 6px 0; font-size: 14px; }
        .cred-box .label { color: #92400e; font-size: 11px; text-transform: uppercase; letter-spacing: .8px; }
        .cred-box .value { font-size: 18px; font-weight: bold; color: #1e3c72; letter-spacing: 1px; font-family: monospace; }
        .btn { display: block; width: fit-content; margin: 24px auto 0; padding: 13px 32px; background: #1e3c72; color: #fff; text-decoration: none; border-radius: 8px; font-weight: bold; font-size: 14px; }
        .warning { background: #fef3c7; border-radius: 6px; padding: 12px 16px; margin: 20px 0; font-size: 13px; color: #92400e; }
        .footer { text-align: center; padding: 20px; font-size: 11px; color: #aaa; border-top: 1px solid #eee; }
    </style>
</head>
<body>
<div class="container">
    <div class="header">
        <h1>NEXPOOL</h1>
        <p>Sistem Manajemen Wisata Kolam Renang</p>
    </div>
    <div class="body">
        <h2>✅ Selamat! Pengajuan Anda Disetujui</h2>
        <p>Halo <strong>{{ $application->full_name }}</strong>,</p>
        <p>Pengajuan akun admin Anda untuk NEXPOOL telah <strong>disetujui</strong> oleh Admin Master. Berikut detail informasi akun Anda:</p>

        <div class="info-box">
            <p><strong>Nama Lengkap:</strong> {{ $application->full_name }}</p>
            <p><strong>Jabatan:</strong> {{ $application->position_label }}</p>
            <p><strong>Wisata Kolam:</strong> {{ $application->pool->name ?? $application->pool_id }}</p>
        </div>

        <p><strong>Gunakan kredensial berikut untuk login:</strong></p>
        <div class="cred-box">
            <p class="label">Username</p>
            <p class="value">{{ $application->username }}</p>
            @if($plainPassword)
                <p class="label" style="margin-top:14px;">Password Sementara</p>
                <p class="value">{{ $plainPassword }}</p>
            @else
                <p>Password: gunakan password yang Anda daftarkan.</p>
            @endif
        </div>

        @if($plainPassword)
            <div class="warning">
                ⚠️ <strong>Penting:</strong> Anda akan diminta mengganti password ini saat pertama kali login. Jaga kerahasiaan password Anda.
            </div>
        @endif

        <a href="{{ route('login') }}" class="btn">Login ke Dashboard Admin</a>
    </div>
    <div class="footer">
        Email ini dikirim otomatis oleh sistem NEXPOOL. Harap tidak membalas email ini.<br>
        &copy; {{ date('Y') }} NEXPOOL
    </div>
</div>
</body>
</html>
