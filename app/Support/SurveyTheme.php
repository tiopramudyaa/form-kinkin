<?php

namespace App\Support;

class SurveyTheme
{
    /**
     * Ordered step keys used to compute how far along the "formal -> lucu -> romantis" journey a page sits.
     *
     * @var list<string>
     */
    private const STEPS = [
        'intro', 'syarat', 'siap-1', 'data-diri', 'siap-2', 'siap-3', 'siap-4', 'siap-5',
        'q-1', 'q-2', 'q-3', 'q-4', 'q-5', 'q-6', 'q-7', 'q-8', 'q-9', 'q-10',
        'wa', 'finish',
    ];

    public static function progressFor(string $step): int
    {
        $index = array_search($step, self::STEPS, true);

        if ($index === false) {
            return 0;
        }

        return (int) round(($index / (count(self::STEPS) - 1)) * 100);
    }

    /**
     * Interpolates a color/shape palette between a formal look (0%) and a full romantic
     * look (100%), passing through a playful midpoint so the page gradually feels cuter.
     *
     * @return array{bg1: string, bg2: string, accent: string, text: string, radius: int, cute: bool, hearts: int}
     */
    public static function palette(int $progress): array
    {
        $progress = max(0, min(100, $progress));

        $stops = [
            0 => ['bg1' => '#f8fafc', 'bg2' => '#eef2f7', 'accent' => '#111827', 'text' => '#111827'],
            55 => ['bg1' => '#fdf2f8', 'bg2' => '#fce7f3', 'accent' => '#db2777', 'text' => '#831843'],
            100 => ['bg1' => '#fda4af', 'bg2' => '#fb7185', 'accent' => '#e11d48', 'text' => '#881337'],
        ];

        $keys = array_keys($stops);

        for ($i = 0; $i < count($keys) - 1; $i++) {
            $from = $keys[$i];
            $to = $keys[$i + 1];

            if ($progress < $from || $progress > $to) {
                continue;
            }

            $t = $to === $from ? 0 : ($progress - $from) / ($to - $from);

            return [
                'bg1' => self::lerpColor($stops[$from]['bg1'], $stops[$to]['bg1'], $t),
                'bg2' => self::lerpColor($stops[$from]['bg2'], $stops[$to]['bg2'], $t),
                'accent' => self::lerpColor($stops[$from]['accent'], $stops[$to]['accent'], $t),
                'text' => self::lerpColor($stops[$from]['text'], $stops[$to]['text'], $t),
                'radius' => (int) round(12 + ($progress / 100) * 28),
                'cute' => $progress >= 55,
                'hearts' => $progress >= 55 ? (int) round((($progress - 55) / 45) * 10) : 0,
            ];
        }

        $last = $stops[100];

        return [
            'bg1' => $last['bg1'],
            'bg2' => $last['bg2'],
            'accent' => $last['accent'],
            'text' => $last['text'],
            'radius' => 40,
            'cute' => true,
            'hearts' => 10,
        ];
    }

    private static function lerpColor(string $from, string $to, float $t): string
    {
        [$r1, $g1, $b1] = sscanf($from, '#%02x%02x%02x');
        [$r2, $g2, $b2] = sscanf($to, '#%02x%02x%02x');

        $r = (int) round($r1 + ($r2 - $r1) * $t);
        $g = (int) round($g1 + ($g2 - $g1) * $t);
        $b = (int) round($b1 + ($b2 - $b1) * $t);

        return sprintf('#%02x%02x%02x', $r, $g, $b);
    }
}
