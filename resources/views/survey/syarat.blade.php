@extends('layouts.survey')

@section('content')
    <p class="text-xs font-semibold uppercase tracking-widest text-gray-400">Formulir Resmi</p>
    <h1 class="mt-2 text-xl font-semibold">Syarat & Ketentuan Survey</h1>

    <div class="gform-field mt-4">
        <ol class="list-decimal space-y-2 pl-5 text-sm leading-relaxed text-gray-600">
            <li>Responden mengisi survey ini secara sadar dan tanpa paksaan.</li>
            <li>Seluruh jawaban wajib diisi dengan jujur, sejujur-jujurnya.</li>
            <li>Data yang dikumpulkan bersifat rahasia dan hanya digunakan oleh pihak yang berkepentingan.</li>
            <li>Responden tidak dapat membatalkan pengisian setelah menekan tombol "Mulai".</li>
        </ol>
    </div>

    <form method="POST" action="{{ route('survey.syarat.store') }}" class="mt-6" x-data="{ agreed: false }">
        @csrf

        <label class="option-card flex items-start gap-3 text-sm">
            <input type="checkbox" name="agree" value="1" class="mt-0.5" required x-model="agreed">
            <span>Saya telah membaca dan menyetujui syarat & ketentuan di atas. <span class="gform-required">*</span></span>
        </label>

        @error('agree')
            <p class="mt-2 text-xs text-red-600">Kinkin harus menyetujui syarat & ketentuan dulu ya.</p>
        @enderror

        <button type="submit" class="btn-primary mt-6" x-bind:disabled="! agreed" x-bind:class="{ 'opacity-40 cursor-not-allowed': ! agreed }">Lanjut</button>
    </form>
@endsection
