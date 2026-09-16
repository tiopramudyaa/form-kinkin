<?php

namespace App\Http\Controllers;

use App\Models\SurveyResponse;
use App\Support\SurveyQuestions;
use App\Support\SurveyTheme;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class SurveyWizardController extends Controller
{
    public function intro(): View
    {
        return view('survey.intro', [
            'title' => 'Survey Pengenalan Diri',
            'progress' => SurveyTheme::progressFor('intro'),
        ]);
    }

    public function syarat(): View
    {
        return view('survey.syarat', [
            'title' => 'Syarat & Ketentuan',
            'progress' => SurveyTheme::progressFor('syarat'),
        ]);
    }

    public function syaratStore(Request $request): RedirectResponse
    {
        $request->validate([
            'agree' => ['accepted'],
        ]);

        session(['survey.agreed' => true]);

        return redirect()->route('survey.siap', ['level' => 1]);
    }

    public function siap(int $level): View|RedirectResponse
    {
        abort_unless($level >= 1 && $level <= 5, 404);

        if ($level > 1 && ! session('survey.data')) {
            return redirect()->route('survey.data-diri');
        }

        $content = [
            1 => [
                'emoji' => '📋',
                'title' => 'Sebelum lanjut...',
                'body' => 'Survey ini cuma butuh waktu beberapa menit kok. Yuk isi dengan jujur ya.',
                'cta' => 'Saya Siap',
            ],
            2 => [
                'emoji' => '🤔',
                'title' => 'Apakah kamu benar-benar siap?',
                'body' => 'Soalnya abis ini agak beda dari form-form biasanya lho...',
                'cta' => 'Iya, aku siap!',
            ],
            3 => [
                'emoji' => '😳',
                'title' => 'APAKAH KAMU BENAR-BENAR SIAP???',
                'body' => 'Serius nih, yakin? 👀',
                'cta' => 'YAKIN BANGET',
            ],
            4 => [
                'emoji' => '😏',
                'title' => 'Oke, aku tau kamu siap',
                'body' => 'Habis ini gak bisa balik lagi ya wkwk',
                'cta' => 'Gaskeun',
            ],
            5 => [
                'emoji' => '🥳',
                'title' => 'Wkwkwk beneran siap nih?',
                'body' => 'Oke, kita mulai survey serunya!',
                'cta' => 'Mulai Survey!',
            ],
        ][$level];

        $next = $level === 1
            ? route('survey.data-diri')
            : ($level < 5
                ? route('survey.siap', ['level' => $level + 1])
                : route('survey.question', ['nomor' => 1]));

        return view('survey.siap', [
            'title' => $content['title'],
            'progress' => SurveyTheme::progressFor("siap-{$level}"),
            'content' => $content,
            'next' => $next,
        ]);
    }

    public function dataDiri(): View|RedirectResponse
    {
        if (! session('survey.agreed')) {
            return redirect()->route('survey.syarat');
        }

        return view('survey.data-diri', [
            'title' => 'Data Diri',
            'progress' => SurveyTheme::progressFor('data-diri'),
        ]);
    }

    public function dataDiriStore(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'birth_date' => ['required', 'date'],
            'nickname' => ['required', 'string', 'max:255'],
        ]);

        session(['survey.data' => $data]);

        return redirect()->route('survey.siap', ['level' => 2]);
    }

    public function question(int $nomor): View|RedirectResponse
    {
        $questions = SurveyQuestions::all();

        abort_unless(isset($questions[$nomor]), 404);

        if (! session('survey.data')) {
            return redirect()->route('survey.data-diri');
        }

        if ($nomor > 1 && count(session('survey.answers', [])) < $nomor - 1) {
            return redirect()->route('survey.question', ['nomor' => 1]);
        }

        return view('survey.question', [
            'title' => "Pertanyaan {$nomor}",
            'progress' => SurveyTheme::progressFor("q-{$nomor}"),
            'nomor' => $nomor,
            'total' => count($questions),
            'question' => $questions[$nomor],
        ]);
    }

    public function questionStore(Request $request, int $nomor): RedirectResponse
    {
        $questions = SurveyQuestions::all();

        abort_unless(isset($questions[$nomor]), 404);

        $question = $questions[$nomor];
        $otherKey = array_key_last($question['options']);

        $rules = [
            'choice' => ['required', Rule::in(array_keys($question['options']))],
        ];

        if ($question['has_other']) {
            $rules['other'] = ['required_if:choice,'.$otherKey, 'nullable', 'string', 'max:255'];
        }

        $validated = $request->validate($rules);

        $answer = $question['options'][$validated['choice']];

        if ($question['has_other'] && $validated['choice'] === $otherKey) {
            $answer = $validated['other'];
        }

        session(["survey.answers.{$question['key']}" => $answer]);

        $next = $nomor < count($questions)
            ? route('survey.question', ['nomor' => $nomor + 1])
            : route('survey.wa');

        return redirect($next);
    }

    public function wa(): View|RedirectResponse
    {
        if (count(session('survey.answers', [])) < SurveyQuestions::total()) {
            return redirect()->route('survey.question', ['nomor' => 1]);
        }

        return view('survey.wa', [
            'title' => 'Satu Lagi Nih',
            'progress' => SurveyTheme::progressFor('wa'),
        ]);
    }

    public function waStore(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'phone' => ['required', 'string', 'max:30'],
        ]);

        SurveyResponse::create([
            ...session('survey.data', []),
            'phone' => $data['phone'],
            'answers' => session('survey.answers', []),
        ]);

        session()->forget('survey');

        return redirect()->route('survey.finish');
    }

    public function finish(): View
    {
        return view('survey.finish', [
            'title' => 'Selesai!',
            'progress' => SurveyTheme::progressFor('finish'),
        ]);
    }
}
