<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TravelRequestQuestion extends Model
{
    protected $fillable = ['question', 'status'];

    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }
}

