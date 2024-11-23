<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Suggestion extends Model
{
    use HasFactory;

    /**
     * Define the relationship with the User model.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Define the relationship with the Event model.
     */
    public function event()
    {
        return $this->belongsTo(Event::class);
    }

    /**
     * Specify which attributes can be mass assigned.
     */
    protected $fillable = [
        'user_id',
        'event_id',
        'context',
        'dateSubmitted',
    ];

    /**
     * Cast attributes to native types.
     */
    protected $casts = [
        'dateSubmitted' => 'datetime', // Ensure proper date casting
    ];

    /**
     * Disable timestamps if not using Laravel's default.
     */
    public $timestamps = false;
}
