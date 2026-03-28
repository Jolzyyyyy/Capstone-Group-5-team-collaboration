<x-mail::message>
# Security Verification

Hello! We received a request for security verification for your account. Please use the 6-digit code below to proceed with your request:

<x-mail::panel>
<h1 style="text-align: center; letter-spacing: 10px; color: #111827; margin: 0;">
    {{ $otp }}
</h1>
</x-mail::panel>

This code is **valid for 10 minutes** only. For your security, do not share this code with anyone.

If you did not request this or did not attempt to log in, please ignore this email. No changes will be made to your account settings.

Thanks,<br>
**{{ config('app.name') }}** Team

<x-slot:subcopy>
If you're having trouble verifying your account, please reply to this email for assistance.
</x-slot:subcopy>
</x-mail::message>