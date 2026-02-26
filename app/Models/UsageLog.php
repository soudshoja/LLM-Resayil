<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UsageLog extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'usage_logs';

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'user_id',
        'api_key_id',
        'model',
        'tokens_used',
        'credits_deducted',
        'provider',
        'response_time_ms',
        'status_code',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'tokens_used' => 'integer',
        'credits_deducted' => 'integer',
        'response_time_ms' => 'integer',
        'status_code' => 'integer',
        'created_at' => 'datetime',
    ];

    /**
     * Get the user that owns the usage log.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the API key that owns the usage log.
     */
    public function apiKey()
    {
        return $this->belongsTo(ApiKeys::class);
    }

    /**
     * Scope a query to include only today's logs.
     */
    public function scopeToday($query)
    {
        return $query->whereDate('created_at', now()->toDateString());
    }

    /**
     * Scope a query by user ID.
     */
    public function scopeForUser($query, $userId)
    {
        return $query->where('user_id', $userId);
    }

    /**
     * Scope a query by provider.
     */
    public function scopeForProvider($query, string $provider)
    {
        return $query->where('provider', $provider);
    }
}
