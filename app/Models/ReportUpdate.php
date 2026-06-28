<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ReportUpdate extends Model
{
    use HasFactory;

    protected $table = 'report_updates';
    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    protected $fillable = [
        'report_id',
        'status',
        'note',
        'updated_by',
        'created_at',
    ];

    public $timestamps = false; // karena cuma pakai created_at

    /**
     * Relasi ke Report
     */
    public function report()
    {
        return $this->belongsTo(Report::class);
    }

    /**
     * Relasi ke User (yang update)
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    public function updatedBy()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }
}