<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Ramsey\Uuid\Uuid;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    public $incrementing = false;
    protected $keyType = 'string';

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'id',
        'email',
        'phone',
        'password',
        'name',
        'credits',
        'subscription_tier',
        'subscription_expiry',
        'trial_started_at',
        'trial_credits_remaining',
        'auto_billed',
        'myfatoorah_payment_profile_id',
        'myfatoorah_subscription_id',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'password' => 'hashed',
        'credits' => 'integer',
        'email_verified_at' => 'datetime',
        'subscription_expiry' => 'datetime',
        'trial_started_at' => 'datetime',
        'trial_credits_remaining' => 'integer',
        'auto_billed' => 'boolean',
    ];

    /**
     * The "booted" method of the model.
     */
    protected static function booted(): void
    {
        static::creating(function ($user) {
            $user->id = Uuid::uuid4()->toString();
        });
    }

    /**
     * Get the API keys for the user.
     */
    public function apiKeys()
    {
        return $this->hasMany(ApiKeys::class);
    }

    /**
     * Get the subscriptions for the user.
     */
    public function subscriptions()
    {
        return $this->hasMany(Subscriptions::class);
    }

    /**
     * Get trial subscription for the user.
     */
    public function trialSubscription()
    {
        return $this->hasOne(Subscriptions::class)->where('is_trial', true);
    }

    /**
     * Get the user's recurring payment methods.
     */
    public function paymentMethods()
    {
        return $this->hasMany(PaymentMethod::class);
    }
}
