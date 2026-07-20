@extends('admin.layout')
@section('title', 'Dashboard')
@section('page-title', 'DASHBOARD')

@section('content')

{{-- Stats Cards --}}
<div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
    @php
        $cards = [
            ['label'=>'Total Reservasi',  'value'=>$stats['total_reservations'], 'icon'=>'📋', 'color'=>'border-l-4 border-blue-400'],
            ['label'=>'Menunggu',         'value'=>$stats['pending'],            'icon'=>'⏳', 'color'=>'border-l-4 border-yellow-400'],
            ['label'=>'Selesai',          'value'=>$stats['completed'],          'icon'=>'✅', 'color'=>'border-l-4 border-green-400'],
            ['label'=>'Total Pelanggan',  'value'=>$stats['total_customers'],    'icon'=>'👥', 'color'=>'border-l-4 border-purple-400'],
            ['label'=>'Total Karyawan',   'value'=>$stats['total_karyawan'],     'icon'=>'👷', 'color'=>'border-l-4 border-orange-400'],
            ['label'=>'Belum Ditugaskan', 'value'=>$stats['unassigned'],         'icon'=>'⚠️', 'color'=>'border-l-4 border-red-400'],
            ['label'=>'Pendapatan',       'value'=>'Rp '.number_format($stats['revenue'],0,',','.'), 'icon'=>'💰', 'color'=>'border-l-4 border-gray-400'],
        ];
    @endphp
    @foreach($cards as $card)
        <div class="bg-white {{ $card['color'] }} rounded shadow-sm p-5">
            <div class="text-2xl mb-2">{{ $card['icon'] }}</div>
            <div class="text-gray-900 font-bold text-xl">{{ $card['value'] }}</div>
            <div class="text-gray-500 text-xs mt-1 tracking-wide">{{ $card['label'] }}</div>
        </div>
    @endforeach
</div>

{{-- Quick Actions --}}
@if($stats['unassigned'] > 0)
    <div class="bg-yellow-50 border border-yellow-200 rounded p-4 mb-6 flex items-center justify-between">
        <div class="flex items-center gap-3">
            <span class="text-yellow-500 text-xl">⚠️</span>
            <span class="text-yellow-800 text-sm font-medium">
                Ada <strong>{{ $stats['unassigned'] }}</strong> reservasi yang belum ditugaskan ke karyawan
            </span>
        </div>
        <a href="{{ route('admin.reservations') }}"
           class="btn-primary text-xs tracking-widest uppercase px-4 py-2 rounded">
            Tugaskan Sekarang
        </a>
    </div>
@endif

{{-- Recent Reservations --}}
<div class="bg-white border border-gray-200 rounded overflow-hidden">
    <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between">
        <h3 class="font-display text-gray-900 text-base tracking-wide">RESERVASI TERBARU</h3>
        <a href="{{ route('admin.reservations') }}" class="text-gray-500 text-xs hover:text-gray-900">Lihat Semua →</a>
    </div>
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead>
                <tr class="border-b border-gray-100 bg-gray-50">
                    <th class="text-left px-6 py-3 text-gray-400 text-xs font-mono uppercase tracking-wider">No.</th>
                    <th class="text-left px-6 py-3 text-gray-400 text-xs font-mono uppercase tracking-wider">Pelanggan</th>
                    <th class="text-left px-6 py-3 text-gray-400 text-xs font-mono uppercase tracking-wider">Layanan</th>
                    <th class="text-left px-6 py-3 text-gray-400 text-xs font-mono uppercase tracking-wider">Karyawan</th>
                    <th class="text-left px-6 py-3 text-gray-400 text-xs font-mono uppercase tracking-wider">Tanggal</th>
                    <th class="text-left px-6 py-3 text-gray-400 text-xs font-mono uppercase tracking-wider">Total</th>
                    <th class="text-left px-6 py-3 text-gray-400 text-xs font-mono uppercase tracking-wider">Status</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($recent as $res)
                    <tr class="hover:bg-gray-50 transition-colors">
                        <td class="px-6 py-4 font-mono text-gray-400 text-xs">#{{ str_pad($res->id, 4, '0', STR_PAD_LEFT) }}</td>
                        <td class="px-6 py-4">
                            <div class="text-gray-900 font-medium">{{ $res->customer_name }}</div>
                            <div class="text-gray-400 text-xs">{{ $res->plate_number }}</div>
                        </td>
                        <td class="px-6 py-4">
                            <div class="text-gray-700">{{ $res->service->name }}</div>
                            <div class="text-gray-400 text-xs">{{ $res->service->size ?? 'Carwash' }}</div>
                        </td>
                        <td class="px-6 py-4">
                            @if($res->karyawan)
                                <div class="text-gray-700 text-sm">{{ $res->karyawan->name }}</div>
                            @else
                                <span class="text-xs bg-red-50 text-red-500 px-2 py-0.5 rounded">Belum ditugaskan</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-gray-500 text-xs">
                            {{ $res->reservation_date->format('d M Y') }}<br>
                            {{ $res->reservation_time }} WIB
                        </td>
                        <td class="px-6 py-4">
                            @if($res->use_free_wash)
                                <span class="text-green-600 text-xs font-bold">GRATIS 🎁</span>
                            @else
                                <span class="text-gray-900 font-mono text-xs">Rp {{ number_format($res->total_price,0,',','.') }}</span>
                            @endif
                        </td>
                        <td class="px-6 py-4">
                            @php
                                $sc=['pending'=>'bg-yellow-100 text-yellow-700','confirmed'=>'bg-blue-100 text-blue-700','in_progress'=>'bg-orange-100 text-orange-700','completed'=>'bg-green-100 text-green-700','cancelled'=>'bg-red-100 text-red-700'];
                                $sl=['pending'=>'Menunggu','confirmed'=>'Dikonfirmasi','in_progress'=>'Diproses','completed'=>'Selesai','cancelled'=>'Dibatalkan'];
                            @endphp
                            <span class="text-xs px-2 py-1 rounded-full {{ $sc[$res->status] ?? '' }}">
                                {{ $sl[$res->status] ?? $res->status }}
                            </span>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="px-6 py-12 text-center text-gray-400">Belum ada reservasi</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

@endsection