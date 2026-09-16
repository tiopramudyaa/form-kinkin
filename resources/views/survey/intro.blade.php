@extends('layouts.survey')

@section('content')
    <div class="text-center">
        <p class="text-xs font-semibold uppercase tracking-widest text-gray-400">Formulir Resmi</p>
        <h1 class="mt-2 text-2xl font-semibold">Survey Pengenalan Diri</h1>
        <p class="mt-4 text-sm leading-relaxed text-gray-600">
            Survey ini bertujuan untuk mengumpulkan data pengenalan diri responden.
            Mohon isi setiap pertanyaan dengan lengkap dan sesuai keadaan sebenarnya.
            Data yang Anda berikan akan digunakan sebagaimana mestinya.
        </p>

        <a href="{{ route('survey.syarat') }}" class="btn-primary mt-8 inline-block">Mulai</a>
    </div>
@endsection
