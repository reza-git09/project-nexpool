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
        .body h2 { color: #dc2626; margin-top: 0; }
        .info-box { background: #f1f5fb; border-left: 4px solid #6b7280; border-radius: 6px; padding: 16px 20px; margin: 20px 0; }
        .info-box p { margin: 5px 0; font-size: 14px; }
        .reason-box { background: #fff1f2; border: 1px solid #fecaca; border-radius: 8px; padding: 16px 20px; margin: 20px 0; }
        .reason-box .label { color: #991b1b; font-size: 11px; text-transform: uppercase; letter-spacing: .8px; margin-bottom: 6px; }
        .reason-box p { font-size: 14px; color: #7f1d1d; margin: 0; }
        .info { background: #eff6ff; border-radius: 6px; padding: 12px 16px; margin: 20px 0; font-size: 13px; color: #1e40af; }
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
        <h2>❌ Pengajuan Anda Tidak Disetujui</h2>
        <p>Halo <strong>{{ $application->full_name }}</strong>,</p>
        <p>Kami informasikan bahwa pengajuan akun admin Anda untuk NEXPOOL <strong>tidak dapat disetujui</strong> pada saat ini.</p>

        <div class="info-box">
            <p><strong>Username yang diajukan:</strong> {{ $application->username }}</p>
            <p><strong>Jabatan:</strong> {{ $application->position_label }}</p>
            <p><strong>Wisata Kolam:</strong> {{ $application->pool->name ?? $application->pool_id }}</p>
        </div>

        <p><strong>Alasan penolakan:</strong></p>
        <div class="reason-box">
            <p class="label">Catatan dari Admin Master</p>
            <p>{{ $application->rejection_reason }}</p>
        </div>

        <div class="info">
            ℹ️ Jika Anda memiliki pertanyaan lebih lanjut, silakan hubungi Admin Master NEXPOOL melalui WhatsApp atau email resmi.
        </div>

        <p style="font-size:13px; color:#666;">Terima kasih atas minat Anda bergabung dengan tim NEXPOOL.</p>
    </div>
    <div class="footer">
        Email ini dikirim otomatis oleh sistem NEXPOOL. Harap tidak membalas email ini.<br>
        &copy; {{ date('Y') }} NEXPOOL
    </div>
</div>
</body>
</html>

