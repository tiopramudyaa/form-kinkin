@extends('layouts.survey')

@section('content')
    <div class="text-center">
        <div class="text-5xl">{{ $content['emoji'] }}</div>
        <h1 class="mt-4 text-xl font-semibold">{{ $content['title'] }}</h1>
        <p class="mt-3 text-sm leading-relaxed text-gray-600">{{ $content['body'] }}</p>

        <div class="mt-8 flex items-center justify-center gap-3" x-data="{ msg: false }">
            <a href="{{ $next }}" class="btn-primary" style="width: auto;">{{ $content['cta'] }}</a>

            <button
                type="button"
                class="btn-secondary"
                x-on:click="msg = true; setTimeout(() => (msg = false), 1600)"
            >Tidak</button>

            <div x-show="msg" x-cloak class="reaction-toast">Harus pilih "{{ $content['cta'] }}" ya!</div>
        </div>
    </div>
@endsection
