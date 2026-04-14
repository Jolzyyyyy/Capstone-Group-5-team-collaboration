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

    /*
    |--------------------------------------------------------------------------
    | ROLE CONSTANTS
    |--------------------------------------------------------------------------
    */
    const ROLE_ADMIN = 'admin';
    const ROLE_CUSTOMER = 'customer';

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'otp_code',
        'otp_expires_at',
        'email_verified_at',
        'google2fa_secret',
        'google2fa_enabled',
        'recovery_codes',
    ];

    /**
     * Attributes hidden from serialization.
     */
    protected $hidden = [
        'password',
        'remember_token',
        'google2fa_secret',
        'otp_code',
    ];

    /**
     * Attribute casting.
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
        return $this->role === self::ROLE_ADMIN;
    }

    public function isCustomer(): bool
    {
        return $this->role === self::ROLE_CUSTOMER;
    }

    /*
    |--------------------------------------------------------------------------
    | OTP & SECURITY HELPERS (Para sa Customers)
    |--------------------------------------------------------------------------
    */

    /**
     * Check kung expired na ang OTP.
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

        // Default role for new users
        static::creating(function ($user) {
            if (empty($user->role)) {
                $user->role = self::ROLE_CUSTOMER;
            }
        });
    }
}