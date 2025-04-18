<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LocalFormQuestion extends Model
{
    protected $fillable = ['question', 'type', 'choices', 'allow_other', 'status', 'order'];

    protected $casts = [
        'choices' => 'array',
        'allow_other' => 'boolean',
    ];
    

    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }
}

