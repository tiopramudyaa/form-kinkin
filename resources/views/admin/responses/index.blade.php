@extends('layouts.admin')

@section('content')
    <h1 class="text-xl font-semibold">Survey Responses</h1>
    <p class="mt-1 text-sm text-gray-500">Total: {{ $responses->total() }} responden</p>

    <div class="mt-6 overflow-x-auto rounded-lg border border-gray-200 bg-white">
        <table class="min-w-full divide-y divide-gray-200 text-sm">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-4 py-3 text-left font-medium text-gray-500">Nama</th>
                    <th class="px-4 py-3 text-left font-medium text-gray-500">Panggilan</th>
                    <th class="px-4 py-3 text-left font-medium text-gray-500">Tanggal Lahir</th>
                    <th class="px-4 py-3 text-left font-medium text-gray-500">No. WA</th>
                    <th class="px-4 py-3 text-left font-medium text-gray-500">Diisi Pada</th>
                    <th class="px-4 py-3"></th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse ($responses as $response)
                    <tr>
                        <td class="px-4 py-3">{{ $response->name }}</td>
                        <td class="px-4 py-3">{{ $response->nickname }}</td>
                        <td class="px-4 py-3">{{ $response->birth_date->format('d M Y') }}</td>
                        <td class="px-4 py-3">{{ $response->phone }}</td>
                        <td class="px-4 py-3">{{ $response->created_at->format('d M Y H:i') }}</td>
                        <td class="px-4 py-3 text-right">
                            <a href="{{ route('admin.responses.show', $response) }}" class="font-medium text-gray-900 hover:underline">Detail</a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="px-4 py-6 text-center text-gray-500">Belum ada yang mengisi survey.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4">
        {{ $responses->links() }}
    </div>
@endsection
