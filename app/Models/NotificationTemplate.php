<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Ramsey\Uuid\Uuid;

class NotificationTemplate extends Model
{
    use HasFactory;

    protected $fillable = [
        'code',
        'name',
        'arabic_content',
        'english_content',
        'default_language',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    protected static function booted(): void
    {
        static::creating(function ($template) {
            $template->id = Uuid::uuid4()->toString();
        });
    }

    /**
     * Get the notifications for this template.
     */
    public function notifications(): HasMany
    {
        return $this->hasMany(Notification::class, 'template_code', 'code');
    }

    /**
     * Scope a query to only include active templates.
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope a query to find template by code.
     */
    public function scopeByCode($query, $code)
    {
        return $query->where('code', $code);
    }

    /**
     * Get template content for specified language.
     */
    public function getTemplate(string $language): ?string
    {
        $content = match ($language) {
            'ar' => $this->arabic_content,
            'en' => $this->english_content,
            default => null,
        };

        return $content ?? ($language === 'en' ? $this->english_content : $this->arabic_content);
    }
}
