<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AttendanceRecord extends Model
{
    /** @use HasFactory<\Database\Factories\AttendanceRecordFactory> */
    use HasFactory;

    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function event()
    {
        return $this->belongsTo(Event::class);
    }

    protected $fillable = [
        'user_id',
        'event_id',
        'date',
        'morning_time_in',
        'morning_time_out',
        'afternoon_time_in',
        'afternoon_time_out',
    ];


    public function getMorningTimeInAttribute()
    {
        return $this->time_in <= '12:00:00' ? $this->time_in : null;
    }

    public function getMorningTimeOutAttribute()
    {
        return $this->time_out <= '12:00:00' ? $this->time_out : null;
    }

    public function getAfternoonTimeInAttribute()
    {
        return $this->time_in > '12:00:00' ? $this->time_in : null;
    }

    public function getAfternoonTimeOutAttribute()
    {
        return $this->time_out > '12:00:00' ? $this->time_out : null;
    }

}
