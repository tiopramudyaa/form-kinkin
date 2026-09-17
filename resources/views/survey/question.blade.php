@extends('layouts.survey')

@php
    $blobLeft = -70 + (($nomor * 23) % 40);
    $blobTop = 8 + (($nomor * 17) % 20);
    $blobRight = -60 + (($nomor * 19) % 30);
    $blobBottom = 6 + (($nomor * 13) % 18);
@endphp

@push('decor')
    <div class="decor-blob" style="width: 220px; height: 220px; left: {{ $blobLeft }}px; top: {{ $blobTop }}%; background: var(--accent); animation-delay: 0s;"></div>
    <div class="decor-blob" style="width: 180px; height: 180px; right: {{ $blobRight }}px; bottom: {{ $blobBottom }}%; background: var(--accent); animation-delay: 1.5s;"></div>

    @for ($i = 0; $i < 22; $i++)
        @php
            $shootX = 220 + (($i * 13) % 140);
            $shootY = 220 + (($i * 19) % 140);
            $left = (($i * 11) + $nomor * 5) % 60;
            $top = (($i * 13) + $nomor * 5) % 40;
            $fontSize = 1.3 + (($i * 5) % 12) / 10;
            $duration = 2.5 + (($i * 7) % 30) / 10;
            $delay = (($i * 11) % 40) / 10;
        @endphp
        <span
            class="shooting-star"
            style="
                left: {{ $left }}%;
                top: {{ $top }}%;
                font-size: {{ $fontSize }}rem;
                --shoot-x: {{ $shootX }}px;
                --shoot-y: {{ $shootY }}px;
                animation-duration: {{ $duration }}s;
                animation-delay: {{ $delay }}s;
            "
        >⭐</span>

        @for ($d = 1; $d <= 3; $d++)
            <span
                class="star-dust"
                style="
                    left: {{ $left }}%;
                    top: {{ $top }}%;
                    font-size: {{ $fontSize * (1 - $d * 0.22) }}rem;
                    --shoot-x: {{ $shootX }}px;
                    --shoot-y: {{ $shootY }}px;
                    --peak-opacity: {{ 0.55 - $d * 0.13 }};
                    animation-duration: {{ $duration }}s;
                    animation-delay: {{ $delay + $d * 0.09 }}s;
                "
            >⭐</span>
        @endfor
    @endfor
@endpush

@section('content')
    @if ($reaction)
        <div class="reaction-toast">{{ $reaction }}</div>
    @endif

    <div class="animate-bounce-in relative">
        <span class="sticker-emoji" style="top: -0.25rem; right: 0.25rem;">{{ $sticker }}</span>

        <div class="flex items-start gap-3">
            <div class="qn-badge">{{ $nomor }}</div>
            <div class="min-w-0 pt-0.5">
                <p class="text-xs font-semibold uppercase tracking-widest text-gray-400">Pertanyaan {{ $nomor }} dari {{ $total }}</p>
                <div class="progress-dots">
                    @for ($i = 1; $i <= $total; $i++)
                        <span class="progress-dot @if ($i === $nomor) is-active @elseif ($i < $nomor) is-done @endif"></span>
                    @endfor
                </div>
            </div>
        </div>

        <h1 class="mt-4 pr-8 text-xl font-semibold leading-snug">{{ $question['question'] }}</h1>

        @if ($milestone || $easterEgg)
            <div class="mt-2 flex flex-wrap gap-2">
                @if ($milestone)
                    <span class="milestone-badge">{{ $milestone }}</span>
                @endif

                @if ($easterEgg)
                    <span class="easter-badge">Waah sama kayak aku! 😆⭐</span>
                @endif
            </div>
        @endif

        @error('choice')
            <p class="mt-2 text-xs text-red-600">Pilih salah satu jawaban dulu ya.</p>
        @enderror
        @error('other')
            <p class="mt-2 text-xs text-red-600">Tolong isi jawaban "Lainnya" ya.</p>
        @enderror

        <div class="qn-divider"></div>

        @php
            $currentChoice = old('choice', $selected['choice'] ?? '');
            $currentOther = old('other', $selected['other'] ?? '');
        @endphp

        <form
            method="POST"
            action="{{ route('survey.question.store', ['nomor' => $nomor]) }}"
            class="space-y-3"
            x-data="{ choice: '{{ $currentChoice }}' }"
        >
            @csrf

            @php($otherKey = array_key_last($question['options']))

            @foreach ($question['options'] as $key => $label)
                <label
                    class="option-card animate-pop-in flex items-center gap-3 text-sm"
                    style="--pop-delay: {{ $loop->index * 0.08 }}s"
                    x-on:click="choice = '{{ $key }}'; window.playPop && window.playPop()"
                >
                    <input
                        type="radio"
                        name="choice"
                        value="{{ $key }}"
                        required
                        class="sr-only"
                        @checked($currentChoice === $key)
                    >
                    <span class="option-letter">{{ $key }}</span>
                    <span>{{ $label }}</span>
                    <span class="confetti">
                        <span style="--tx: -22px; --ty: -20px;">⭐</span>
                        <span style="--tx: 20px; --ty: -18px;">⭐</span>
                        <span style="--tx: 0px; --ty: -26px;">🎉</span>
                        <span style="--tx: 12px; --ty: 14px;">⭐</span>
                    </span>
                </label>
            @endforeach

            @if ($question['has_other'])
                <div x-show="choice === '{{ $otherKey }}'" x-cloak class="pt-1">
                    <input
                        type="text"
                        name="other"
                        value="{{ $currentOther }}"
                        placeholder="Sebutkan..."
                        class="form-input"
                    >
                </div>
            @endif

            <div class="qn-divider"></div>

            <div class="flex items-center gap-3">
                <a href="{{ $back }}" class="btn-secondary">Kembali</a>
                <button type="submit" class="btn-primary flex-1">Lanjut 😆</button>
            </div>
        </form>
    </div>
@endsection
