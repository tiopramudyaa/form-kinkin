@extends('layouts.survey')

@section('content')
    <p class="text-xs font-semibold uppercase tracking-widest opacity-60">Terakhir nih</p>
    <h1 class="mt-2 text-xl font-semibold">
        Demi kenyamanan bersama, boleh tolong di-share nomor WA-nya? 🥰
    </h1>
    <p class="mt-2 text-sm opacity-70">
        Biar kalau ada apa-apa, admin bisa menghubungi ci kinkin ya~
    </p>

    @error('phone')
        <p class="mt-2 text-xs text-red-600">Nomor WA-nya wajib diisi ya.</p>
    @enderror

    <form method="POST" action="{{ route('survey.wa.store') }}" class="mt-6 space-y-4">
        @csrf

        <div>
            <label for="phone" class="text-sm font-medium">Nomor WhatsApp</label>
            <input type="text" name="phone" id="phone" value="{{ old('phone') }}" required placeholder="08xxxxxxxxxx" class="form-input">
        </div>

        <button type="submit" class="btn-primary">Kirim Jawaban 💌</button>
    </form>
@endsection
