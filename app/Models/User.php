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

    const ROLE_ADMIN = 'admin';
    const ROLE_ADMIN_CLIENT = 'admin_client';
    const ROLE_CUSTOMER = 'customer';
    const ROLE_DEVELOPER = 'developer';
    const EMAIL_OTP_TTL_MINUTES = 5;
    const EMAIL_OTP_RESEND_COOLDOWN_SECONDS = 60;
    const EMAIL_OTP_LOCKOUT_SECONDS = 900;

    /**
     * The attributes that are mass assignable.
     * FIXED: Sinigurado na lahat ng security fields ay accessible para sa registration at update.
     */
    protected $fillable = [
        'name',
        'first_name',
        'last_name',
        'email',
        'password',
        'role',               // 'admin' o 'customer'
        'admin_client_id',
        'otp_code',           // 6-digit code
        'otp_expires_at',     // Expiration timestamp
        'email_verified_at',
        'preregistered_by',
        'approved_at',
        'approved_by',
        'invite_token',
        'invite_expires_at',
        'invitation_accepted_at',
        'google2fa_secret',   // Para sa Admin Google Authenticator
        'google2fa_enabled',  // 2FA toggle para sa Admin
        'recovery_codes',     // Backup codes para sa 2FA
        
        // --- GOOGLE AUTH ADDITIONS ---
        'google_id',          // Unique ID mula sa Google
        'google_token',       // Access token mula sa Google

        // --- SECURITY UPDATE: BACKUP EMAIL ---
        'backup_email',       // Secondary email para sa account recovery

        // --- PASSWORD STATUS (OPTION B) ---
        'has_set_password',   // Flag kung nakapag-manual set na ng password ang Google user
    ];

    /**
     * Attributes hidden from serialization.
     */
    protected $hidden = [
        'password',
        'remember_token',
        'google_token',
        'invite_token',
        'google2fa_secret',
        'otp_code',
    ];

    /**
     * Attribute casting.
     * FIXED: Sinigurado na ang otp_expires_at ay 'datetime' para gumana ang Carbon helpers.
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
        'has_set_password'  => 'boolean', 
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

    public function isAdminClient(): bool
    {
        return $this->role === self::ROLE_ADMIN_CLIENT;
    }

    public function isCustomer(): bool
    {
        return $this->role === self::ROLE_CUSTOMER;
    }

    public function hasGoogleLogin(): bool
    {
        return filled($this->google_id);
    }

    public function isDeveloper(): bool
    {
        return $this->role === self::ROLE_DEVELOPER;
    }

    public function canManageAdminClients(): bool
    {
        return $this->isDeveloper();
    }

    public function canAccessAdminPortal(): bool
    {
        return $this->isAdmin() || $this->isAdminClient() || $this->isDeveloper();
    }

    public function isInvitedAdminPendingApproval(): bool
    {
        return $this->isAdmin()
            && $this->preregistered_by !== null
            && $this->approved_at === null;
    }

    public function canViewAllPortalRecords(): bool
    {
        return $this->isAdmin() || $this->isDeveloper();
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
     * Check kung expired na ang OTP (10 mins limit).
     */
    public function isOtpExpired(): bool
    {
        // Kung walang date o nakalipas na ang date, true (expired).
        return !$this->otp_expires_at || $this->otp_expires_at->isPast();
    }

    /**
     * Validate ang pinadalang OTP mula sa input.
     */
    public function isOtpValid(string $otp): bool
    {
        $storedOtp = trim((string)$this->otp_code);
        $providedOtp = trim((string)$otp);
        
        // Match check + Expiration check
        return !empty($storedOtp) && $storedOtp === $providedOtp && !$this->isOtpExpired();
    }

    /**
     * I-trigger ang SendOTP Notification (Email).
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
     * FIXED: I-set ang session flag para sa Middleware access.
     * Eto ang tumutugma sa 'customer_otp' middleware natin.
     */
    public function markOtpPassedSession(Request $request): void
    {
        if ($this->isCustomer()) {
            // Ito ang session key na hinahanap ng middleware mo
            $request->session()->put('customer_otp_passed', true);
            
            // Mahalaga ang regenerate para sa security laban sa session fixation
            $request->session()->regenerate();
        }
    }

    /*
    |--------------------------------------------------------------------------
    | PASSWORD SETUP HELPERS (Google Users)
    |--------------------------------------------------------------------------
    */

    /**
     * Check kung kailangan pa ng user mag-setup ng password.
     */
    public function needsPasswordSetup(): bool
    {
        return $this->hasGoogleLogin() && !$this->has_set_password;
    }

    /*
    |--------------------------------------------------------------------------
    | BOOT METHOD
    |--------------------------------------------------------------------------
    */
    protected static function boot()
    {
        parent::boot();

        // Default role assignment sa pag-create ng user
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
