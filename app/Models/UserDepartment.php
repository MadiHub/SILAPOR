<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserDepartment extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'user_id',
        'department_id'
    ];
}
