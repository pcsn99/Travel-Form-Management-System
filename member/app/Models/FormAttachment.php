<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FormAttachment extends Model
{
    protected $fillable = [
        'local_travel_form_id',
        'Overseas_travel_form_id',
        'file_path',
        'original_name'
    ];

    public function localForm()
    {
        return $this->belongsTo(LocalTravelForm::class);
    }

    public function OverseasForm()
    {
        return $this->belongsTo(OverseasTravelForm::class);
    }
}

