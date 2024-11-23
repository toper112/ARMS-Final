<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    /** @use HasFactory<\Database\Factories\EventFactory> */
    use HasFactory;

    public function attendanceRecords()
    {
        return $this->hasMany(AttendanceRecord::class);
    }
    public function suggestions()
    {
        return $this->hasMany(Suggestion::class);
    }
    public function checkings()
    {
        return $this->hasMany(Suggestion::class);
    }

    protected $fillable = [
        'name',
        'description',
        'date',
        'time',
        'remarks'
    ];
}
