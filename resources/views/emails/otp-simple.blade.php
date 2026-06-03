<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>{{ $appName }} Security Verification Code</title>
</head>
<body style="margin:0; padding:0; background:#f6f3ef; font-family:Arial, sans-serif; color:#22201f;">
    <table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="background:#f6f3ef; padding:28px 12px;">
        <tr>
            <td align="center">
                <table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="max-width:560px; background:#ffffff; border:1px solid #eadfd2;">
                    <tr>
                        <td style="padding:28px 28px 10px;">
                            <p style="margin:0 0 8px; font-size:12px; font-weight:700; letter-spacing:3px; text-transform:uppercase; color:#ff8d2a;">Security Verification</p>
                            <h1 style="margin:0; font-size:24px; line-height:1.25; color:#22201f;">Your {{ $appName }} code</h1>
                        </td>
                    </tr>
                    <tr>
                        <td style="padding:10px 28px 0;">
                            <p style="margin:0 0 16px; font-size:15px; line-height:1.6; color:#5f5750;">Hello {{ $name }}, use this 6-digit code to continue signing in or verifying your account.</p>
                        </td>
                    </tr>
                    <tr>
                        <td style="padding:8px 28px;">
                            <div style="background:#fff8ef; border:1px solid #ffcf99; padding:22px; text-align:center;">
                                <div style="font-size:34px; line-height:1; font-weight:800; letter-spacing:8px; color:#111827;">{{ $otp }}</div>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td style="padding:14px 28px 28px;">
                            <p style="margin:0 0 12px; font-size:14px; line-height:1.6; color:#5f5750;">This code expires in {{ $ttlMinutes }} minutes. Do not share it with anyone.</p>
                            <p style="margin:0; font-size:13px; line-height:1.6; color:#8a8178;">If you did not request this code, you can ignore this email.</p>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>
</html>
