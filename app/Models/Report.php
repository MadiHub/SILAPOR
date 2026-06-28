<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Report extends Model
{
    protected $fillable = [
        'user_id',
        'category_id',
        'department_id',
        'title',
        'description',
        'latitude',
        'longitude',
        'address',
        'status',
        'votes_count'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function category()
    {
        return $this->belongsTo(ProblemCategory::class, 'category_id');
    }

    public function department()
    {
        return $this->belongsTo(Department::class);
    }

    public function votes()
    {
        return $this->hasMany(ReportVote::class);
    }

    public function images()
    {
        return $this->hasMany(ReportImage::class);
    }

    public function updates()
    {
        return $this->hasMany(ReportUpdate::class)->latest();
    }

    public function comments()
    {
        return $this->hasMany(ReportComment::class);
    }

    
    // ── Helpers ──────────────────────────────────────────────
 
    public function getStatusColorAttribute(): string
    {
        return match ($this->status) {
            'active'   => '#f59e0b',
            'process'  => '#3b82f6',
            'done'     => '#10b981',
            'rejected' => '#ef4444',
            default    => '#777',
        };
    }
 
    public function getStatusLabelAttribute(): string
    {
        return match ($this->status) {
            'active'   => 'Aktif',
            'process'  => 'Diproses',
            'done'     => 'Selesai',
            'rejected' => 'Ditolak',
            default    => $this->status,
        };
    }
 
    public function getFirstImageUrlAttribute(): ?string
    {
        $img = $this->images->first();
        return $img ? asset('storage/' . $img->image_url) : null;
    }
}
