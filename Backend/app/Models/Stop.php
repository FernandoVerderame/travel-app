<?php

namespace App\Models;

use App\Models\Day;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Stop extends Model
{
    use HasFactory;

    protected $fillable = [
        'day_id',
        'title',
        'image',
        'foods',
        'address',
        'latitude',
        'longitude',
        'rating'
    ];

    public function day()
    {
        return $this->belongsTo(Day::class);
    }
}
