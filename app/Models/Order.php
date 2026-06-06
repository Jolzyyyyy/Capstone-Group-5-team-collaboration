<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Builder;

class Order extends Model
{
    use HasFactory;

    public const PAYMENT_UNPAID = 'unpaid';
    public const PAYMENT_PENDING = 'pending';
    public const PAYMENT_PAID = 'paid';
    public const PAYMENT_FAILED = 'failed';
    public const PAYMENT_CANCELLED = 'cancelled';

    protected $fillable = [
        'user_id',
        'admin_client_id',
        'admin_client_id',
        'customer_name',
        'customer_email',
        'customer_phone',
        'fulfillment_method',
        'delivery_address',
        'customer_note',
        'status',
        'payment_status',
        'payment_method',
        'paymongo_checkout_session_id',
        'payment_reference',
        'paid_at',
        'total_price',
    ];

    protected $casts = [
        'paid_at' => 'datetime',
        'total_price' => 'decimal:2',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function adminClient()
    {
        return $this->belongsTo(User::class, 'admin_client_id');
    }

    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function files()
    {
        return $this->hasMany(\App\Models\OrderFile::class);
    }

    public function recomputeTotal(): void
    {
        $total = $this->items()->sum('subtotal');
        $this->update(['total_price' => $total]);
    }

    public function scopeVisibleToPortalUser(Builder $query, User $user): Builder
    {
        if ($user->canViewAllPortalRecords()) {
            return $query;
        }

        if ($user->isAdminClient()) {
            return $query->where(function (Builder $scope) use ($user) {
                $scope->where('admin_client_id', $user->id)
                    ->orWhereHas('user', function (Builder $customer) use ($user) {
                        $customer->where('admin_client_id', $user->id);
                    });
            });
        }

        return $query->whereRaw('1 = 0');
    }

    public function isVisibleToPortalUser(User $user): bool
    {
        if ($user->canViewAllPortalRecords()) {
            return true;
        }

        if (!$user->isAdminClient()) {
            return false;
        }

        return (int) $this->admin_client_id === (int) $user->id
            || (int) optional($this->user)->admin_client_id === (int) $user->id;
    }
}
