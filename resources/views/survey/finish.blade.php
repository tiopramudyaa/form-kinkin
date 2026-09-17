@extends('layouts.survey')

@section('content')
    <div class="pointer-events-none fixed inset-0 z-30 overflow-hidden">
        @php
            $emojiList = ['🎉', '🎊', '⭐'];
        @endphp
        @for ($i = 0; $i < 30; $i++)
            <span
                class="confetti-fall"
                style="
                    left: {{ ($i * 13 + 7) % 100 }}%;
                    animation-delay: {{ (($i * 7) % 30) / 10 }}s;
                    animation-duration: {{ 3 + (($i * 11) % 30) / 10 }}s;
                "
            >{{ $emojiList[$i % count($emojiList)] }}</span>
        @endfor
    </div>

    <div class="animate-bounce-in text-center">
        <div class="text-5xl">🎉</div>
        <h1 class="mt-4 text-2xl font-semibold">Makasih udah isi semuanya!</h1>
        <p class="mt-4 text-sm leading-relaxed opacity-80">
            Terima kasih sudah meluangkan waktu untuk mengisi survey ini.
        </p>

        @if ($name)
            <div class="mt-8">
                <canvas
                    id="certificate-canvas"
                    width="1200"
                    height="800"
                    class="mx-auto w-full max-w-md rounded-lg border border-black/10 shadow-sm"
                ></canvas>

                <button
                    type="button"
                    onclick="downloadCertificate(@js($name))"
                    class="mt-4 inline-flex items-center gap-2 rounded-full px-6 py-3 text-sm font-semibold text-white transition hover:opacity-90"
                    style="background: var(--accent);"
                >
                    ⬇️ Download Sertifikat
                </button>
            </div>

            <script>
                document.addEventListener('DOMContentLoaded', () => drawCertificate(@js($name)));
            </script>
        @endif
    </div>
@endsection
