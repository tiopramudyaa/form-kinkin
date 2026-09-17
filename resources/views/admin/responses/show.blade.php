@extends('layouts.admin')

@section('content')
    <a href="{{ route('admin.responses.index') }}" class="text-sm text-gray-500 hover:text-gray-900">&larr; Kembali ke daftar</a>

    <h1 class="mt-2 text-xl font-semibold">{{ $response->name }}</h1>

    <div class="mt-6 grid grid-cols-1 gap-4 sm:grid-cols-2">
        <div class="gform-field">
            <p class="text-xs font-medium uppercase tracking-wide text-gray-400">Nama Panggilan</p>
            <p class="mt-1 text-sm">{{ $response->nickname }}</p>
        </div>
        <div class="gform-field">
            <p class="text-xs font-medium uppercase tracking-wide text-gray-400">Tanggal Lahir</p>
            <p class="mt-1 text-sm">{{ $response->birth_date->format('d M Y') }}</p>
        </div>
        <div class="gform-field">
            <p class="text-xs font-medium uppercase tracking-wide text-gray-400">No. WhatsApp</p>
            <p class="mt-1 text-sm">{{ $response->phone }}</p>
        </div>
        <div class="gform-field">
            <p class="text-xs font-medium uppercase tracking-wide text-gray-400">Diisi Pada</p>
            <p class="mt-1 text-sm">{{ $response->created_at->format('d M Y H:i') }}</p>
        </div>
    </div>

    <h2 class="mt-8 text-sm font-semibold uppercase tracking-wide text-gray-400">Jawaban Survey</h2>

    <div class="mt-3 space-y-3">
        @foreach ($answers as $item)
            <div class="gform-field">
                <p class="text-sm font-medium">{{ $item['question'] }}</p>
                <p class="mt-1 text-sm text-gray-600">{{ $item['answer'] }}</p>
            </div>
        @endforeach
    </div>
@endsection
