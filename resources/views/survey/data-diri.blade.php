@extends('layouts.survey')

@section('content')
    <p class="text-xs font-semibold uppercase tracking-widest text-gray-400">Formulir Resmi</p>
    <h1 class="mt-2 text-xl font-semibold">Data Diri</h1>
    <p class="mt-2 text-sm text-gray-600">Isi data diri kinkin dengan benar ya.</p>

    @if ($errors->any())
        <div class="mt-4 rounded-lg border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-800">
            <ul class="list-inside list-disc">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('survey.data-diri.store') }}" class="mt-6 space-y-4">
        @csrf

        <div class="gform-field">
            <label for="name" class="text-sm font-medium">Nama Lengkap <span class="gform-required">*</span></label>
            <input type="text" name="name" id="name" value="{{ old('name') }}" required class="form-input">
        </div>

        <div class="gform-field">
            <label for="nickname" class="text-sm font-medium">Nama Panggilan <span class="gform-required">*</span></label>
            <input type="text" name="nickname" id="nickname" value="{{ old('nickname') }}" required class="form-input">
        </div>

        <div class="gform-field">
            <label for="birth_date" class="text-sm font-medium">Tanggal Lahir <span class="gform-required">*</span></label>
            <input type="date" name="birth_date" id="birth_date" value="{{ old('birth_date') }}" required class="form-input">
        </div>

        <button type="submit" class="btn-primary">Lanjut</button>
    </form>
@endsection
