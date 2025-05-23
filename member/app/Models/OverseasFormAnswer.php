<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OverseasFormAnswer extends Model
{
    protected $fillable = ['overseas_travel_form_id', 'question_id', 'answer'];

    public function question()
    {
        return $this->belongsTo(\App\Models\OverseasFormQuestion::class, 'question_id');
    }

    public function form()
    {
        return $this->belongsTo(\App\Models\OverseasTravelForm::class, 'overseas_travel_form_id');
    }
}

