@extends('layouts.survey')

@section('content')
    <div class="text-center">
        <div class="text-5xl">{{ $content['emoji'] }}</div>
        <h1 class="mt-4 text-xl font-semibold">{{ $content['title'] }}</h1>
        <p class="mt-3 text-sm leading-relaxed text-gray-600">{{ $content['body'] }}</p>

        <a href="{{ $next }}" class="btn-primary mt-8 inline-block">{{ $content['cta'] }}</a>
    </div>
@endsection
