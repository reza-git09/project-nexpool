<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="color-scheme" content="light">
    <meta name="supported-color-schemes" content="light">
    <title>Kode OTP NEXPOOL</title>
    <style>
        @media only screen and (max-width: 520px) {
            .wrapper { padding: 16px 12px !important; }
            .card-body { padding: 36px 24px !important; }
            .code { font-size: 32px !important; letter-spacing: 8px !important; padding: 20px 12px 20px 20px !important; }
        }
    </style>
</head>
<body style="margin:0;padding:0;background:#f5f6f8;font-family:'Segoe UI',Roboto,Helvetica,Arial,sans-serif;color:#1f2937;-webkit-text-size-adjust:100%;">

    <!-- Preheader -->
    <div style="display:none;max-height:0;overflow:hidden;opacity:0;color:transparent;font-size:1px;line-height:1px;">
        Kode OTP kamu: {{ $code }}. Berlaku {{ $minutes }} menit.
        &zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;
    </div>

    <table role="presentation" width="100%" cellpadding="0" cellspacing="0" border="0" style="background:#f5f6f8;">
        <tr>
            <td align="center" class="wrapper" style="padding:48px 16px;">

                <table role="presentation" width="100%" cellpadding="0" cellspacing="0" border="0"
                       style="max-width:460px;background:#ffffff;border:1px solid #e8eaee;border-radius:14px;">
                    <tr>
                        <td class="card-body" style="padding:44px 40px;">

                            <!-- Wordmark -->
                            <div style="font-size:13px;font-weight:700;letter-spacing:4px;color:#1e3a7a;text-align:center;">
                                NEXPOOL
                            </div>
                            <div style="width:24px;height:2px;background:#1e3a7a;margin:14px auto 36px;font-size:0;line-height:0;">&nbsp;</div>

                            <!-- Judul -->
                            <h1 style="margin:0 0 12px;font-size:20px;line-height:1.4;font-weight:600;color:#111827;text-align:center;">
                                Kode verifikasi kamu
                            </h1>
                            <p style="margin:0 0 32px;font-size:14px;line-height:1.7;color:#6b7280;text-align:center;">
                                Gunakan kode di bawah untuk masuk ke akun<br>
                                <span style="color:#374151;font-weight:600;">Admin Master</span>.
                            </p>

                            <!-- Kode -->
                            <table role="presentation" cellpadding="0" cellspacing="0" border="0" align="center" style="margin:0 auto;">
                                <tr>
                                    <td class="code" align="center"
                                        style="background:#f7f9fc;border:1px solid #e3e8f0;border-radius:10px;
                                               padding:22px 14px 22px 24px;
                                               font-family:'SF Mono',Menlo,Consolas,'Courier New',monospace;
                                               font-size:36px;font-weight:700;letter-spacing:12px;color:#1e3a7a;">
                                        {{ $code }}
                                    </td>
                                </tr>
                            </table>

                            <p style="margin:20px 0 0;font-size:13px;line-height:1.6;color:#9ca3af;text-align:center;">
                                Berlaku {{ $minutes }} menit &middot; hanya bisa dipakai sekali
                            </p>

                            <!-- Pemisah -->
                            <div style="height:1px;background:#eef0f3;margin:36px 0 24px;font-size:0;line-height:0;">&nbsp;</div>

                            <!-- Catatan keamanan -->
                            <p style="margin:0 0 8px;font-size:12px;line-height:1.7;color:#6b7280;text-align:center;">
                                <strong style="color:#374151;">Jangan berikan kode ini kepada siapa pun</strong>,
                                termasuk yang mengaku dari NEXPOOL.
                            </p>
                            <p style="margin:0;font-size:12px;line-height:1.7;color:#9ca3af;text-align:center;">
                                Bukan kamu yang mencoba login? Abaikan email ini dan segera ganti password akun.
                            </p>
                        </td>
                    </tr>
                </table>

                <!-- Footer -->
                <p style="margin:24px 0 0;font-size:11px;line-height:1.6;color:#a0a7b4;text-align:center;">
                    &copy; {{ date('Y') }} NEXPOOL &middot; Admin Panel Wisata
                </p>

            </td>
        </tr>
    </table>
</body>
</html>