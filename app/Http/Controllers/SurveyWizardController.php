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
        abort_unless($level >= 1 && $level <= 7, 404);

        if ($level > 1 && ! session('survey.data')) {
            return redirect()->route('survey.data-diri');
        }

        $content = [
            1 => [
                'emoji' => '📋',
                'title' => 'Sebelum Melanjutkan',
                'body' => 'Survey ini membutuhkan waktu sekitar 5 menit. Mohon isi dengan jujur dan sesuai keadaan sebenarnya.',
                'cta' => 'Saya Siap',
            ],
            2 => [
                'emoji' => '📌',
                'title' => 'Apakah Anda Siap?',
                'body' => 'Mohon pastikan kembali kesiapan Anda sebelum melanjutkan ke tahap berikutnya.',
                'cta' => 'Ya, Saya Siap',
            ],
            3 => [
                'emoji' => '🤔',
                'title' => 'Beneran kinkin siap?',
                'body' => 'Soalnya abis ini bakal rada beda nih dari form-form biasanya...',
                'cta' => 'Iya, aku siap',
            ],
            4 => [
                'emoji' => '😳',
                'title' => 'BENERAN YAAA KLO KINKIN SIAP?',
                'body' => 'Yakin nih? Habis ini gak bisa balik lagi lho... 👀',
                'cta' => 'YAKIN BANGET',
            ],
            5 => [
                'emoji' => '🥳',
                'title' => 'Wkwkwk oke deh, aku tau kinkin siap!',
                'body' => 'Yuk, kita mulai survey serunya! 🎉',
                'cta' => 'Mulai Survey!',
            ],
            6 => [
                'emoji' => '🤨',
                'title' => 'Tapi yakin kan kinkin siap???',
                'body' => 'Coba dipikir sekali lagi deh sebelum lanjut...',
                'cta' => 'Yakin, Lanjut',
            ],
            7 => [
                'emoji' => '🤪',
                'title' => 'OKEEEE KELIATAN DAH SIAP BANGET KINKIN, YUKK KITA MULAII',
                'body' => 'Oke fix ya, nggak boleh mundur lagi lho abis ini! 😆',
                'cta' => 'Siap, Lanjut!',
            ],
        ][$level];

        $next = $level === 1
            ? route('survey.data-diri')
            : ($level < 7
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

        $back = $nomor > 1
            ? route('survey.question', ['nomor' => $nomor - 1])
            : route('survey.siap', ['level' => 7]);

        $stickers = ['⭐', '🌸', '🎀', '🍭', '🌟', '🤪', '🦄', '🍬', '🎈'];
        $total = count($questions);
        $ratio = $nomor / $total;

        $milestone = match (true) {
            $nomor === $total => '🎉 Ini pertanyaan terakhir!',
            $ratio >= 0.75 => 'Tinggal dikit lagi! 🔥',
            $ratio >= 0.5 => 'Setengah jalan! 🎉',
            $ratio >= 0.25 => 'Lagi seru nih~ 😄',
            default => null,
        };

        return view('survey.question', [
            'title' => "Pertanyaan {$nomor}",
            'progress' => SurveyTheme::progressFor("q-{$nomor}"),
            'nomor' => $nomor,
            'total' => $total,
            'question' => $questions[$nomor],
            'back' => $back,
            'sticker' => $stickers[($nomor - 1) % count($stickers)],
            'selected' => session("survey.raw.{$nomor}", []),
            'floatEmoji' => '⭐',
            'milestone' => $milestone,
            'reaction' => session()->pull('survey.reaction'),
            'easterEgg' => session()->pull('survey.easter_egg', false),
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

        session([
            "survey.answers.{$question['key']}" => $answer,
            "survey.raw.{$nomor}" => $validated,
        ]);

        $reactions = [
            'Sip, keren! 😄', 'Ih relate! 🤭', 'Wih mantap! 👍',
            'Noted ya~ 📝', 'Hehe oke oke 😆', 'Wah baru tau nih! 👀',
        ];
        session()->flash('survey.reaction', $reactions[array_rand($reactions)]);

        if ($question['my_pick'] !== null && $validated['choice'] === $question['my_pick']) {
            session()->flash('survey.easter_egg', true);
        }

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

        $name = session('survey.data.name');

        session()->forget('survey');
        session(['certificate_name' => $name]);

        return redirect()->route('survey.finish');
    }

    public function finish(): View
    {
        return view('survey.finish', [
            'title' => 'Selesai!',
            'progress' => SurveyTheme::progressFor('finish'),
            'name' => session('certificate_name'),
        ]);
    }
}
