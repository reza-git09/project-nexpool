<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kode OTP NEXPOOL</title>
</head>
<body style="margin:0;padding:24px;background:#eef2f7;font-family:Arial,Helvetica,sans-serif;color:#1f2937;">
    <table role="presentation" width="100%" cellpadding="0" cellspacing="0">
        <tr>
            <td align="center">
                <table role="presentation" width="100%" cellpadding="0" cellspacing="0"
                       style="max-width:480px;background:#ffffff;border-radius:16px;padding:32px;">
                    <tr>
                        <td align="center" style="padding-bottom:8px;">
                            <div style="font-size:26px;font-weight:800;color:#1e3a7a;letter-spacing:1px;">NEXPOOL</div>
                            <div style="font-size:13px;color:#6b7280;margin-top:2px;">Admin Panel Wisata</div>
                        </td>
                    </tr>
                    <tr>
                        <td style="padding-top:20px;font-size:15px;line-height:1.6;">
                            Ada permintaan login ke akun <strong>Admin Master</strong>.
                            Masukkan kode berikut untuk melanjutkan:
                        </td>
                    </tr>
                    <tr>
                        <td align="center" style="padding:24px 0;">
                            <div style="display:inline-block;background:#e8f0fe;border-radius:12px;padding:16px 28px;
                                        font-size:34px;font-weight:800;letter-spacing:10px;color:#1e3a7a;">
                                {{ $code }}
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td style="font-size:13px;line-height:1.6;color:#4b5563;">
                            Kode berlaku <strong>{{ $minutes }} menit</strong> dan hanya bisa dipakai sekali.<br>
                            <strong>Jangan berikan kode ini kepada siapa pun</strong>, termasuk yang mengaku dari NEXPOOL.
                        </td>
                    </tr>
                    <tr>
                        <td style="padding-top:20px;font-size:12px;line-height:1.6;color:#9ca3af;">
                            Kalau bukan kamu yang mencoba login, abaikan email ini dan segera ganti password akun.
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>
</html>