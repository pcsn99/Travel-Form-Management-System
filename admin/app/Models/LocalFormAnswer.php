<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LocalFormAnswer extends Model
{
    protected $fillable = ['local_travel_form_id', 'question_id', 'answer'];

    public function question()
    {
        return $this->belongsTo(\App\Models\LocalFormQuestion::class, 'question_id');
    }

    public function form()
    {
        return $this->belongsTo(\App\Models\LocalTravelForm::class, 'local_travel_form_id');
    }
}

