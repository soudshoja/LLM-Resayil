<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Ramsey\Uuid\Uuid;

class TeamMember extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'team_members';

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'team_owner_id',
        'member_user_id',
        'role',
        'added_by_id',
        'joined_at',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'joined_at' => 'datetime',
    ];

    /**
     * The "booted" method of the model.
     */
    protected static function booted(): void
    {
        static::creating(function ($teamMember) {
            $teamMember->id = Uuid::uuid4()->toString();
        });
    }

    /**
     * Get the team owner (the Enterprise account that owns the team).
     */
    public function teamOwner()
    {
        return $this->belongsTo(User::class, 'team_owner_id');
    }

    /**
     * Get the member user (the user added to the team).
     */
    public function member()
    {
        return $this->belongsTo(User::class, 'member_user_id');
    }

    /**
     * Get the user who added this team member.
     */
    public function addedBy()
    {
        return $this->belongsTo(User::class, 'added_by_id');
    }

    /**
     * Scope a query to only include admin role members.
     */
    public function scopeAdmin($query)
    {
        return $query->where('role', 'admin');
    }

    /**
     * Scope a query to only include member role members.
     */
    public function scopeMember($query)
    {
        return $query->where('role', 'member');
    }

    /**
     * Scope a query to only include members for a specific team owner.
     */
    public function scopeForOwner($query, $ownerId)
    {
        return $query->where('team_owner_id', $ownerId);
    }

    /**
     * Check if this team member has admin role.
     */
    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    /**
     * Check if this team member has member role.
     */
    public function isMember(): bool
    {
        return $this->role === 'member';
    }
}
