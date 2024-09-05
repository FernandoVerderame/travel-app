<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;

    protected $fillable = ['label', 'icon'];

    public function stops()
    {
        return $this->hasMany(Stop::class);
    }
}
