<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OverseasTravelForm extends Model
{
    protected $fillable = [
        'travel_request_id', 'status', 'admin_comment',
        'submitted_at', 'approved_at', 'rejected_at'
    ];

    public function request()
    {
        return $this->belongsTo(\App\Models\TravelRequest::class, 'travel_request_id');
    }

    public function answers()
    {
        return $this->hasMany(\App\Models\OverseasFormAnswer::class);
    }
    public function supervisor()
    {
        return $this->belongsTo(User::class, 'local_supervisor');
    }

    public function attachments()
    {
        return $this->hasMany(\App\Models\FormAttachment::class);
    }
    
}

