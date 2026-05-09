<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AdminClientProfile extends Model
{
    protected $fillable = [
        'user_id',
        'business_name',
        'contact_person',
        'contact_number',
        'business_address',
        'reference_notes',
        'profile_completed_at',
    ];

    protected $casts = [
        'profile_completed_at' => 'datetime',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function isComplete(): bool
    {
        return $this->profile_completed_at !== null
            && filled($this->business_name)
            && filled($this->contact_person)
            && filled($this->contact_number)
            && filled($this->business_address);
    }
}
