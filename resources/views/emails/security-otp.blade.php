<!DOCTYPE html>
<html>
<head>
    <title>Security Verification</title>
</head>
<body style="font-family: sans-serif; padding: 20px; text-align: center;">
    
    <h2 style="color: #374151; text-transform: uppercase;">
        Security Verification
    </h2>

    <p>
        Pakisulat ang 6-digit security code sa aming website upang makumpleto ang iyong login.
    </p>

    <div style="background: #f3f4f6; padding: 20px; border-radius: 8px; margin: 20px 0;">
        <h1 style="letter-spacing: 10px; color: #1f2937; font-size: 32px;">
            {{ $otp }}
        </h1>
    </div>

    <p style="font-size: 12px; color: #6b7280;">
        Mage-expire ang code na ito sa loob ng 10 minuto.
    </p>

</body>
</html>