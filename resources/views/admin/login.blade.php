@extends('layouts.admin')

@section('content')
    <div class="mx-auto mt-16 max-w-sm">
        <h1 class="text-xl font-semibold">Admin Login</h1>
        <p class="mt-1 text-sm text-gray-500">Masukkan password untuk melihat data survey.</p>

        @error('password')
            <div class="mt-4 rounded-lg border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-800">
                {{ $message }}
            </div>
        @enderror

        <form method="POST" action="{{ route('admin.login.store') }}" class="mt-6 space-y-4">
            @csrf

            <div>
                <label for="password" class="text-sm font-medium">Password</label>
                <input type="password" name="password" id="password" required autofocus class="form-input">
            </div>

            <button type="submit" class="btn-primary">Masuk</button>
        </form>
    </div>
@endsection
