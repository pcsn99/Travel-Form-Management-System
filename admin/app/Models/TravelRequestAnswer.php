<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TravelRequestAnswer extends Model
{
    protected $fillable = ['travel_request_id', 'question_id', 'answer'];

    public function question()
    {
        return $this->belongsTo(TravelRequestQuestion::class, 'question_id');
    }
}