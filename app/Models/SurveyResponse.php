<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SurveyResponse extends Model
{
    protected $fillable = [
        'name',
        'birth_date',
        'nickname',
        'phone',
        'answers',
    ];

    protected $casts = [
        'birth_date' => 'date',
        'answers' => 'array',
    ];
}
