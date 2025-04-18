<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class TravelRequest extends Model
{
    protected $fillable = [
        'user_id', 'type', 'status', 'intended_departure_date', 'intended_return_date',
        'admin_comment', 'approved_at', 'rejected_at'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function answers()
    {
        return $this->hasMany(TravelRequestAnswer::class);
    }

    public function localForm()
    {
        return $this->hasOne(\App\Models\LocalTravelForm::class);
    }
    
    public function OverseasForm()
    {
        return $this->hasOne(\App\Models\OverseasTravelForm::class);
    }
}
