<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use Notifiable;

    protected $fillable = [
        'name', 'email', 'password', 'role',
    ];

    protected $hidden = [
        'password', 'remember_token',
    ];

    public function travelRequests()
    {
        return $this->hasMany(\App\Models\TravelRequest::class);
    }
    
    public function localForms()
    {
        return $this->hasManyThrough(
            \App\Models\LocalTravelForm::class,
            \App\Models\TravelRequest::class,
            'user_id',        // Foreign key on travel_requests table
            'travel_request_id', // Foreign key on local_forms table
            'id',             // Local key on users table
            'id'              // Local key on travel_requests table
        );
    }
    
    public function OverseasForms()
    {
        return $this->hasManyThrough(
            \App\Models\OverseasTravelForm::class,
            \App\Models\TravelRequest::class,
            'user_id',
            'travel_request_id',
            'id',
            'id'
        );
    }

    public function files()
    {
        return $this->hasMany(UserFile::class);
    }

    public function profilePhoto()
    {
        return $this->hasOne(UserProfilePhoto::class);
    }


    
}