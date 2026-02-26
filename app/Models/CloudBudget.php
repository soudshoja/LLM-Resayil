<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CloudBudget extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'cloud_budgets';

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'date',
        'requests_today',
        'daily_limit',
        'last_reset_at',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'date' => 'date',
        'requests_today' => 'integer',
        'daily_limit' => 'integer',
        'last_reset_at' => 'datetime',
    ];

    /**
     * Scope a query to only include today's budget.
     */
    public function scopeToday($query)
    {
        return $query->where('date', now()->toDateString());
    }

    /**
     * Get today's cloud budget record.
     */
    public static function today(): ?self
    {
        return self::today()->first();
    }
}
