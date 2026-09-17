<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SurveyResponse;
use App\Support\SurveyQuestions;
use Illuminate\View\View;

class SurveyResponseController extends Controller
{
    public function index(): View
    {
        return view('admin.responses.index', [
            'responses' => SurveyResponse::latest()->paginate(20),
        ]);
    }

    public function show(SurveyResponse $surveyResponse): View
    {
        $answers = collect(SurveyQuestions::all())->map(fn (array $question) => [
            'question' => $question['question'],
            'answer' => $surveyResponse->answers[$question['key']] ?? '-',
        ]);

        return view('admin.responses.show', [
            'response' => $surveyResponse,
            'answers' => $answers,
        ]);
    }
}
