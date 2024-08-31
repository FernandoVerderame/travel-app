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
        'trip_id',
        'number',
        'date',
    ];

    public function trip()
    {
        return $this->belongsTo(Trip::class);
    }

    public function stops()
    {
        return $this->hasMany(Stop::class);
    }

    protected function title(): Attribute
    {
        return Attribute::make(
            set: fn(string $value) => [
                'title' => $value,
                'slug' => Str::slug($value)
            ]
        );
    }
}
