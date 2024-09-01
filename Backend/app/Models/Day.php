<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Model;

class Day extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'slug',
        'number',
        'date',
        'trip_id'
    ];

    public function trip()
    {
        return $this->belongsTo(Trip::class);
    }

    public function stops()
    {
        return $this->hasMany(Stop::class);
    }
}
