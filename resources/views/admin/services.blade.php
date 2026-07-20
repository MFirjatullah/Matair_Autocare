@extends('admin.layout')
@section('title', 'Kelola Layanan')
@section('page-title', 'KELOLA LAYANAN')

@section('content')

@php
    $detailing = $services->where('category', 'detailing');
    $carwash   = $services->where('category', 'carwash');
@endphp

<div class="mb-8">
    <h3 class="font-display text-gray-900 text-xl tracking-wide mb-4">
        PAKET 01 — DETAILING
        <span class="text-gray-400 text-base font-sans ml-2">({{ $detailing->count() }} layanan)</span>
    </h3>
    <div class="bg-white border border-gray-200 rounded overflow-hidden">
        <table class="w-full text-sm">
            <thead>
                <tr class="border-b border-gray-100 bg-gray-50">
                    <th class="text-left px-5 py-3 text-gray-400 text-xs font-mono uppercase">Nama Layanan</th>
                    <th class="text-left px-5 py-3 text-gray-400 text-xs font-mono uppercase">Ukuran</th>
                    <th class="text-left px-5 py-3 text-gray-400 text-xs font-mono uppercase">Harga</th>
                    <th class="text-left px-5 py-3 text-gray-400 text-xs font-mono uppercase">Status</th>
                    <th class="text-left px-5 py-3 text-gray-400 text-xs font-mono uppercase">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @foreach($detailing as $service)
                    <tr class="hover:bg-gray-50 {{ !$service->is_active ? 'opacity-50' : '' }}">
                        <td class="px-5 py-3 text-gray-900 font-medium">{{ $service->name }}</td>
                        <td class="px-5 py-3">
                            <span class="text-xs px-2 py-1 rounded bg-gray-100 text-gray-600">{{ $service->size }}</span>
                        </td>
                        <td class="px-5 py-3 font-mono text-gray-900 text-sm">Rp {{ number_format($service->price, 0, ',', '.') }}</td>
                        <td class="px-5 py-3">
                            @if($service->is_active)
                                <span class="text-xs bg-green-100 text-green-700 px-2 py-1 rounded-full">Aktif</span>
                            @else
                                <span class="text-xs bg-red-100 text-red-700 px-2 py-1 rounded-full">Nonaktif</span>
                            @endif
                        </td>
                        <td class="px-5 py-3">
                            <form method="POST" action="{{ route('admin.services.toggle', $service) }}" class="inline">
                                @csrf @method('PATCH')
                                <button type="submit" class="text-xs px-3 py-1.5 rounded border transition-all {{ $service->is_active ? 'border-red-200 text-red-500 hover:bg-red-50' : 'border-green-200 text-green-600 hover:bg-green-50' }}">
                                    {{ $service->is_active ? 'Nonaktifkan' : 'Aktifkan' }}
                                </button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

<div>
    <h3 class="font-display text-gray-900 text-xl tracking-wide mb-4">
        PAKET 02 — CARWASH
        <span class="text-gray-400 text-base font-sans ml-2">({{ $carwash->count() }} layanan)</span>
    </h3>
    <div class="bg-white border border-gray-200 rounded overflow-hidden">
        <table class="w-full text-sm">
            <thead>
                <tr class="border-b border-gray-100 bg-gray-50">
                    <th class="text-left px-5 py-3 text-gray-400 text-xs font-mono uppercase">Nama Layanan</th>
                    <th class="text-left px-5 py-3 text-gray-400 text-xs font-mono uppercase">Harga</th>
                    <th class="text-left px-5 py-3 text-gray-400 text-xs font-mono uppercase">Status</th>
                    <th class="text-left px-5 py-3 text-gray-400 text-xs font-mono uppercase">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @foreach($carwash as $service)
                    <tr class="hover:bg-gray-50 {{ !$service->is_active ? 'opacity-50' : '' }}">
                        <td class="px-5 py-3 text-gray-900 font-medium">{{ $service->name }}</td>
                        <td class="px-5 py-3 font-mono text-gray-900 text-sm">Rp {{ number_format($service->price, 0, ',', '.') }}</td>
                        <td class="px-5 py-3">
                            @if($service->is_active)
                                <span class="text-xs bg-green-100 text-green-700 px-2 py-1 rounded-full">Aktif</span>
                            @else
                                <span class="text-xs bg-red-100 text-red-700 px-2 py-1 rounded-full">Nonaktif</span>
                            @endif
                        </td>
                        <td class="px-5 py-3">
                            <form method="POST" action="{{ route('admin.services.toggle', $service) }}" class="inline">
                                @csrf @method('PATCH')
                                <button type="submit" class="text-xs px-3 py-1.5 rounded border transition-all {{ $service->is_active ? 'border-red-200 text-red-500 hover:bg-red-50' : 'border-green-200 text-green-600 hover:bg-green-50' }}">
                                    {{ $service->is_active ? 'Nonaktifkan' : 'Aktifkan' }}
                                </button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

@endsection