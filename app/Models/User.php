<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use App\Notifications\SendOTP;
use Carbon\Carbon;
use Illuminate\Http\Request;

class User extends Authenticatable implements MustVerifyEmail
{
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     * Idinagdag natin ang role, otp, at google2fa columns.
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',               // 'admin' o 'customer'
        'otp_code',           // 6-digit code
        'otp_expires_at',     // Expiration timestamp
        'email_verified_at',
        'google2fa_secret',   // Para sa Admin Google Authenticator
        'google2fa_enabled',  // 2FA toggle para sa Admin
        'recovery_codes',     // Backup codes
    ];

    /**
     * Attributes hidden from serialization.
     * Sinisiguro nating hindi lalabas ang OTP at 2FA secrets sa logs o API.
     */
    protected $hidden = [
        'password',
        'remember_token',
        'google2fa_secret',
        'otp_code',
    ];

    /**
     * Attribute casting.
     * Ginagawa nating Carbon instances ang dates para madaling i-manipulate.
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'otp_expires_at'    => 'datetime',
        'password'          => 'hashed',
        'google2fa_enabled' => 'boolean',
        'recovery_codes'    => 'json',
    ];

    /*
    |--------------------------------------------------------------------------
    | ROLE HELPERS
    |--------------------------------------------------------------------------
    */

    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    public function isCustomer(): bool
    {
        return $this->role === 'customer';
    }

    /*
    |--------------------------------------------------------------------------
    | OTP & SECURITY HELPERS (Para sa Customers)
    |--------------------------------------------------------------------------
    */

    /**
     * Check kung expired na ang OTP (10 mins limit).
     */
    public function isOtpExpired(): bool
    {
        return !$this->otp_expires_at || $this->otp_expires_at->isPast();
    }

    /**
     * Validate ang pinadalang OTP.
     */
    public function isOtpValid(string $otp): bool
    {
        return trim((string)$this->otp_code) === trim($otp) && !$this->isOtpExpired();
    }

    /**
     * I-trigger ang SendOTP Notification.
     */
    public function sendOtpNotification(string $otp)
    {
        $this->notify(new SendOTP($otp));
    }

    /**
     * Linisin ang DB pagkatapos ng successful verification.
     */
    public function clearOtp(): void
    {
        $this->update([
            'otp_code' => null,
            'otp_expires_at' => null,
        ]);
    }

    /**
     * I-set ang session flag na 'customer_otp_passed'.
     */
    public function markOtpPassedSession(Request $request): void
    {
        if ($this->isCustomer()) {
            $request->session()->put('customer_otp_passed', true);
            $request->session()->regenerate();
        }
    }

    /*
    |--------------------------------------------------------------------------
    | BOOT METHOD
    |--------------------------------------------------------------------------
    */
    protected static function boot()
    {
        parent::boot();

        // Awtomatikong 'customer' ang role ng sinumang mag-register sa front-end.
        static::creating(function ($user) {
            if (empty($user->role)) {
                $user->role = 'customer';
            }
        });
    }
}