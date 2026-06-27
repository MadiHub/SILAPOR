<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
     protected $fillable = [
        'name',
        'email',
        'phone',
        'password',
        'google_id',
        'avatar',
        'role',
        'status',
        'last_login_at'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function reports()
    {
        return $this->hasMany(Report::class);
    }

    public function votes()
    {
        return $this->hasMany(ReportVote::class);
    }

    public function departments()
    {
        return $this->belongsToMany(Department::class, 'user_departments');
    }

    public function comments()
    {
        return $this->hasMany(ReportComment::class);
    }

     public function reportUpdates()
    {
        return $this->hasMany(ReportUpdate::class, 'updated_by');
    }

    public function getAvatarUrlAttribute(): string
    {
        $path = $this->avatar;
 
        if (!$path) {
            return 'https://ui-avatars.com/api/?name=' . urlencode($this->name) . '&background=133a68&color=fff';
        }
 
        if (str_contains($path, 'http')) {
            return $path;
        }
 
        return asset('storage/' . ltrim($path, '/'));
    }

    public function getRolePriorityAttribute()
    {
        return match($this->role) {
            'admin' => 1,
            'pemda' => 2,
            'warga' => 3,
            default => 99,
        };
    }
 
    public function isAdmin(): bool   { return $this->role === 'admin'; }
    public function isPemda(): bool   { return $this->role === 'pemda'; }
    public function isWarga(): bool   { return $this->role === 'warga'; }
    public function isBanned(): bool  { return $this->status === 'banned'; }
    public function isActive(): bool  { return $this->status === 'active'; }
}
