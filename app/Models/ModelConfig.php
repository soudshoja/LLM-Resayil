<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class ModelConfig extends Model
{
    protected $table = 'models';

    protected $primaryKey = 'model_id';

    public $incrementing = false;

    protected $keyType = 'string';

    protected $fillable = [
        'model_id',
        'is_active',
        'credit_multiplier_override',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'credit_multiplier_override' => 'float',
    ];

    protected static function booted(): void
    {
        static::creating(function ($model) {
            if (empty($model->model_id)) {
                $model->model_id = Str::uuid()->toString();
            }
        });
    }
}
