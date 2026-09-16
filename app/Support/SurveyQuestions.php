<?php

namespace App\Support;

class SurveyQuestions
{
    /**
     * Ordered survey questions, 1-indexed to match the `/survey/pertanyaan/{nomor}` route.
     *
     * @return array<int, array{key: string, question: string, options: array<string, string>, has_other: bool}>
     */
    public static function all(): array
    {
        return [
            1 => [
                'key' => 'makanan_favorit',
                'question' => 'Makanan favorit kinkin apa nih?',
                'options' => [
                    'A' => 'Makanan berat',
                    'B' => 'Cemilan/jajanan',
                    'C' => 'Makanan manis',
                    'D' => 'Lainnya',
                ],
                'has_other' => true,
            ],
            2 => [
                'key' => 'minuman_favorit',
                'question' => 'Kalau minuman favorit, kinkin pilih yang mana?',
                'options' => [
                    'A' => 'Kopi',
                    'B' => 'Teh',
                    'C' => 'Boba/minuman manis',
                    'D' => 'Jamu',
                    'E' => 'Brotowali Pramuka',
                    'F' => 'Lainnya',
                ],
                'has_other' => true,
            ],
            3 => [
                'key' => 'hobi',
                'question' => 'Hobinya pasti seru, apa tuh?',
                'options' => [
                    'A' => 'Nonton film/series',
                    'B' => 'Main game',
                    'C' => 'Voli',
                    'D' => 'Rebahan',
                    'E' => 'Lainnya',
                ],
                'has_other' => true,
            ],
            4 => [
                'key' => 'hewan_favorit',
                'question' => 'Hewan favorit kinkin apa?',
                'options' => [
                    'A' => 'Kucing',
                    'B' => 'Anjing',
                    'C' => 'Kura-kura ninja',
                    'D' => 'Lainnya',
                ],
                'has_other' => true,
            ],
            5 => [
                'key' => 'genre_film',
                'question' => 'Kalau nonton film, sukanya apa nih?',
                'options' => [
                    'A' => 'Horor',
                    'B' => 'Komedi',
                    'C' => 'Romance',
                    'D' => 'Drama Cina di Reels TikTok',
                ],
                'has_other' => false,
            ],
            6 => [
                'key' => 'pernah_ilang_barang',
                'question' => 'Pernah nggak kinkin ilang hp/laptop di asrama?',
                'options' => [
                    'A' => 'Pernah banget',
                    'B' => 'Pernah aja',
                    'C' => 'Pernah',
                    'D' => 'Gapernah, tapi boong',
                    'E' => 'Rill gapernah',
                ],
                'has_other' => false,
            ],
            7 => [
                'key' => 'berantem_suster',
                'question' => 'Pernah nggak berantem sama suster?',
                'options' => [
                    'A' => 'Pasti pernah lah',
                    'B' => 'Pernah',
                    'C' => 'Nggak pernah',
                    'D' => 'Sering banget',
                ],
                'has_other' => false,
            ],
            8 => [
                'key' => 'pelajaran_favorit',
                'question' => 'Pelajaran favorit kinkin di sedes?',
                'options' => [
                    'A' => 'Olahraga',
                    'B' => 'Matematika',
                    'C' => 'Biologi',
                    'D' => 'Fisika',
                    'E' => 'Jam kos, tidur di kelas WKWKWK',
                    'F' => 'Lainnya',
                ],
                'has_other' => true,
            ],
            9 => [
                'key' => 'cita_cita',
                'question' => 'Cita-cita kinkin?',
                'options' => [
                    'A' => 'Guru',
                    'B' => 'Cici-cici teknik',
                    'C' => 'Kayak Fizy (Upin Ipin)',
                    'D' => 'Lainnya',
                ],
                'has_other' => true,
            ],
            10 => [
                'key' => 'kalau_bosen',
                'question' => 'Kalau bosen di asrama ngapain aja tuh?',
                'options' => [
                    'A' => 'Bertani di kebun aspi',
                    'B' => 'Tidur',
                    'C' => 'Gibah',
                    'D' => 'Lainnya',
                ],
                'has_other' => true,
            ],
        ];
    }

    public static function total(): int
    {
        return count(self::all());
    }
}
