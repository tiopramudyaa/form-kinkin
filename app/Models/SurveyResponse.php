<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SurveyResponse extends Model
{
    protected $fillable = [
        'name',
        'email',
        'phone',
        'address',
        'question_1',
        'question_2',
        'question_3',
        'answers',
    ];

    protected $casts = [
        'answers' => 'array',
    ];
}
