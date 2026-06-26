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
        return $this->hasMany(ReportUpdate::class);
    }

    public function comments()
    {
        return $this->hasMany(ReportComment::class);
    }
}
