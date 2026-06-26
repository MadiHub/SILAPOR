<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProblemCategory extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'name',
        'icon',
        'department_id',
        'description'
    ];

    public function department()
    {
        return $this->belongsTo(Department::class);
    }

    public function reports()
    {
        return $this->hasMany(Report::class, 'category_id');
    }
}
