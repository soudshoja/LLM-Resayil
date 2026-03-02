<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Ramsey\Uuid\Uuid;

class Subscriptions extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'subscriptions';

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'user_id',
        'tier',
        'status',
        'MyFatoorah_invoice_id',
        'starts_at',
        'ends_at',
        'credits_purchased',
        'credits_used',
        'auto_renew',
        'is_trial',
        'trial_started_at',
        'trial_expiry_at',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'starts_at' => 'datetime',
        'ends_at' => 'datetime',
        'trial_started_at' => 'datetime',
        'trial_expiry_at' => 'datetime',
        'auto_renew' => 'boolean',
    ];

    /**
     * The "booted" method of the model.
     */
    protected static function booted(): void
    {
        static::creating(function ($subscription) {
            $subscription->id = Uuid::uuid4()->toString();
        });
    }

    /**
     * Get the user that owns the subscription.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Scope a query to only include trial subscriptions.
     */
    public function scopeTrial($query)
    {
        return $query->where('is_trial', true);
    }

    /**
     * Scope a query to only include active subscriptions.
     */
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    /**
     * Scope a query to only include recurring subscriptions.
     */
    public function scopeRecurring($query)
    {
        return $query->where('auto_renew', true);
    }
}
