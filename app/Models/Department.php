<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Department extends Model
{
    protected $fillable = [
        'name',
        'code',
        'description'
    ];

    public function users()
    {
        return $this->belongsToMany(User::class, 'user_departments');
    }

    public function categories()
    {
        return $this->hasMany(ProblemCategory::class);
    }

    public function reports()
    {
        return $this->hasMany(Report::class);
    }
}
