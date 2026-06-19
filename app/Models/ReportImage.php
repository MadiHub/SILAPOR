<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ReportImage extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'report_id',
        'image_url'
    ];

    public function report()
    {
        return $this->belongsTo(Report::class);
    }
}
