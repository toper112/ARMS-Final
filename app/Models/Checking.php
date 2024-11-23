<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Pest\Collision\Events;

class Checking extends Model
{
    /** @use HasFactory<\Database\Factories\CheckingFactory> */
    use HasFactory;

    public function events()
    {
        return $this->belongsTo(Event::class);
    }
}
