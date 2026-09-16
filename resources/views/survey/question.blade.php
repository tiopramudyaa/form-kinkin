@extends('layouts.survey')

@section('content')
    <p class="text-xs font-semibold uppercase tracking-widest text-gray-400">Pertanyaan {{ $nomor }} dari {{ $total }}</p>
    <h1 class="mt-2 text-xl font-semibold">{{ $question['question'] }}</h1>

    @error('choice')
        <p class="mt-2 text-xs text-red-600">Pilih salah satu jawaban dulu ya.</p>
    @enderror
    @error('other')
        <p class="mt-2 text-xs text-red-600">Tolong isi jawaban "Lainnya" ya.</p>
    @enderror

    <form
        method="POST"
        action="{{ route('survey.question.store', ['nomor' => $nomor]) }}"
        class="mt-6 space-y-3"
        x-data="{ choice: '{{ old('choice') }}' }"
    >
        @csrf

        @php($otherKey = array_key_last($question['options']))

        @foreach ($question['options'] as $key => $label)
            <label class="option-card flex items-center gap-3 text-sm" x-on:click="choice = '{{ $key }}'">
                <input
                    type="radio"
                    name="choice"
                    value="{{ $key }}"
                    required
                    @checked(old('choice') === $key)
                >
                <span>{{ $label }}</span>
            </label>
        @endforeach

        @if ($question['has_other'])
            <div x-show="choice === '{{ $otherKey }}'" x-cloak class="pt-1">
                <input
                    type="text"
                    name="other"
                    value="{{ old('other') }}"
                    placeholder="Sebutkan..."
                    class="form-input"
                >
            </div>
        @endif

        <button type="submit" class="btn-primary mt-4">Lanjut</button>
    </form>
@endsection
