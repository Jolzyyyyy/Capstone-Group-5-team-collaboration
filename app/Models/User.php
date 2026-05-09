<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use App\Notifications\SendOTP;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class User extends Authenticatable implements MustVerifyEmail
{
    use HasFactory, Notifiable;

    /*
    |--------------------------------------------------------------------------
    | ROLE CONSTANTS
    |--------------------------------------------------------------------------
    */
    const ROLE_ADMIN = 'admin';
    const ROLE_ADMIN_CLIENT = 'admin_client';
    const ROLE_CUSTOMER = 'customer';
    const ROLE_DEVELOPER = 'developer';
    const EMAIL_OTP_TTL_MINUTES = 5;
    const EMAIL_OTP_RESEND_COOLDOWN_SECONDS = 60;
    const EMAIL_OTP_LOCKOUT_SECONDS = 900;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'name',
        'first_name',
        'last_name',
        'email',
        'password',
        'role',
        'admin_client_id',
        'otp_code',
        'otp_expires_at',
        'email_verified_at',
        'preregistered_by',
        'approved_at',
        'approved_by',
        'invite_token',
        'invite_expires_at',
        'invitation_accepted_at',
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
        'invite_token',
        'google2fa_secret',
        'otp_code',
    ];

    /**
     * Attribute casting.
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'otp_expires_at'    => 'datetime',
        'approved_at'       => 'datetime',
        'invite_expires_at'  => 'datetime',
        'invitation_accepted_at' => 'datetime',
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
        return $this->canAccessAdminPortal();
    }

    public function isAdminClient(): bool
    {
        return $this->role === self::ROLE_ADMIN_CLIENT;
    }

    public function isCustomer(): bool
    {
        return $this->role === self::ROLE_CUSTOMER;
    }

    public function isDeveloper(): bool
    {
        return in_array($this->role, [self::ROLE_DEVELOPER, self::ROLE_ADMIN], true);
    }

    public function canManageAdminClients(): bool
    {
        return $this->isDeveloper();
    }

    public function canAccessAdminPortal(): bool
    {
        return $this->isAdminClient() || $this->isDeveloper();
    }

    public function isApprovedAdminClient(): bool
    {
        return $this->isAdminClient() && $this->approved_at !== null;
    }

    public function hasAcceptedInvitation(): bool
    {
        return $this->invitation_accepted_at !== null;
    }

    public function hasCompletedAdminClientProfile(): bool
    {
        if (!$this->isAdminClient()) {
            return true;
        }

        return $this->adminClientProfile?->isComplete() ?? false;
    }

    public function adminClientProfile(): HasOne
    {
        return $this->hasOne(AdminClientProfile::class);
    }

    public function assignedAdminClient(): BelongsTo
    {
        return $this->belongsTo(self::class, 'admin_client_id');
    }

    public function assignedCustomers(): HasMany
    {
        return $this->hasMany(self::class, 'admin_client_id')
            ->where('role', self::ROLE_CUSTOMER);
    }

    public function managedOrders(): HasMany
    {
        return $this->hasMany(Order::class, 'admin_client_id');
    }

    public function auditLogsAsActor(): HasMany
    {
        return $this->hasMany(AuditLog::class, 'actor_id');
    }

    public function auditLogsAsTarget(): HasMany
    {
        return $this->hasMany(AuditLog::class, 'target_user_id');
    }

    public function orders(): HasMany
    {
        return $this->hasMany(Order::class);
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

        static::saving(function ($user) {
            $user->syncNameParts();
        });
    }

    public function syncNameParts(): void
    {
        $firstName = trim((string) ($this->first_name ?? ''));
        $lastName = trim((string) ($this->last_name ?? ''));

        if ($firstName !== '' || $lastName !== '') {
            $this->first_name = $firstName !== '' ? $firstName : null;
            $this->last_name = $lastName !== '' ? $lastName : null;
            $this->name = trim(implode(' ', array_filter([$firstName, $lastName])));
            return;
        }

        $fullName = trim((string) ($this->name ?? ''));

        if ($fullName === '') {
            $this->first_name = null;
            $this->last_name = null;
            $this->name = null;
            return;
        }

        $parts = preg_split('/\s+/', $fullName, 2);
        $this->first_name = $parts[0] ?? null;
        $this->last_name = $parts[1] ?? null;
        $this->name = trim(implode(' ', array_filter([$this->first_name, $this->last_name])));
    }
}
