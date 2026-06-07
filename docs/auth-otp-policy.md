# Authentication And OTP Policy

This document records the current role-based authentication and OTP behavior for Printify & Co.

## Active Roles

| Role | Portal | OTP Required | Notes |
| --- | --- | --- | --- |
| Customer | Customer portal | Yes | Required for protected customer pages and account verification flows. |
| Admin-client | Staff portal | Yes | Required on every fresh staff sign-in after credentials are accepted. |
| Admin | Staff portal | Yes | Required on every fresh staff sign-in after credentials are accepted. |
| Developer | Staff portal | No | Trusted system-owner role. Developer access bypasses staff OTP by policy. |

## OTP Rules

The shared OTP timing constants live in `app/Models/User.php`.

| Rule | Current Value |
| --- | --- |
| Failed OTP attempts before lockout | 3 attempts |
| OTP expiry | 5 minutes |
| Resend cooldown | 60 seconds |
| Lockout/cooling period after failed attempts | 15 minutes |

## Active Customer OTP Flow

Customer OTP behavior is handled by:

- `app/Http/Controllers/Auth/AuthenticatedSessionController.php`
- `app/Http/Controllers/Auth/RegisteredUserController.php`
- `app/Http/Controllers/Auth/GoogleAuthController.php`
- `app/Http/Controllers/Auth/PasswordResetLinkController.php`
- `app/Http/Controllers/Auth/VerifyOtpController.php`
- `app/Http/Controllers/Auth/VerifyEmailController.php`
- `app/Http/Controllers/Auth/EmailVerificationNotificationController.php`
- `app/Http/Middleware/CustomerOtpMiddleware.php`
- `app/Http/Middleware/EnsureCustomerOtpIsVerified.php`

Customer sessions use `customer_otp_passed` after successful OTP verification.

## Active Staff OTP Flow

Admin-client and admin OTP behavior is handled by:

- `app/Http/Controllers/Admin/Auth/AdminAuthController.php`
- `app/Http/Middleware/AdminMiddleware.php`

Staff sessions use `staff_otp_passed` after successful OTP verification.

Fresh staff sign-in clears stale staff OTP session flags before issuing a new OTP. Developer sign-in sets `staff_otp_passed` without an OTP challenge because developer is the trusted system-owner role.

## Removed Legacy Code

The following old duplicate controllers were removed because they were not routed and could reintroduce stale auth behavior if accidentally revived:

- `app/Http/Controllers/Admin/Auth/AdminOtpController.php`
- `app/Http/Controllers/Admin/Auth/AdminRegistrationController.php`
- `app/Http/Controllers/GoogleController.php`

The active behavior remains in the controllers listed above.

## Test Coverage

Current auth and OTP behavior is covered mainly by:

- `tests/Feature/Auth/AuthenticationTest.php`
- `tests/Feature/Auth/AdminClientAccessTest.php`
- `tests/Feature/Auth/EmailVerificationTest.php`
- `tests/Feature/Auth/GoogleLoginTest.php`
- `tests/Feature/Auth/PasswordResetTest.php`
- `tests/Feature/Auth/DashboardAccessTest.php`
