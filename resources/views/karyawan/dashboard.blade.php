@extends('layouts.app')
@section('title', 'Dashboard Karyawan — MATAIR Auto Care')

@section('content')

<div class="max-w-6xl mx-auto px-6 py-12">

    {{-- Header --}}
    <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4 mb-8">
        <div>
            <p class="text-gray-400 text-xs tracking-widest uppercase mb-1">Dashboard Karyawan</p>
            <h1 class="font-display text-gray-900 text-3xl tracking-widest">
                Halo, {{ $karyawan->name }}
            </h1>
            <p class="text-gray-500 text-sm mt-1">
                Spesialisasi: {{ $karyawan->specializationLabel() }}
                &nbsp;•&nbsp;
                Rating: {{ $karyawan->formattedRating() }}
            </p>
        </div>
        {{-- Toggle ketersediaan --}}
        <form method="POST" action="{{ route('karyawan.toggle-availability') }}">
            @csrf
            <button type="submit"
                    class="px-6 py-2.5 rounded-lg text-xs tracking-widest uppercase font-bold transition-all
                    {{ $karyawan->is_available
                        ? 'bg-green-100 border border-green-300 text-green-700 hover:bg-green-200'
                        : 'bg-red-100 border border-red-300 text-red-700 hover:bg-red-200' }}">
                {{ $karyawan->is_available ? '✓ Tersedia' : '✕ Tidak Tersedia' }}
            </button>
        </form>
    </div>

    {{-- Flash --}}
    @if(session('success'))
        <div class="bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-lg text-sm mb-6">
            ✓ {{ session('success') }}
        </div>
    @endif
    @if(session('error'))
        <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg text-sm mb-6">
            ✕ {{ session('error') }}
        </div>
    @endif

    {{-- Stat Cards --}}
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-10">
        @php
            $statCards = [
                ['label'=>'Menunggu Konfirmasi', 'value'=>$stats['menunggu'],                              'color'=>'border-l-4 border-yellow-400'],
                ['label'=>'Sedang Dikerjakan',   'value'=>$stats['diterima'],                              'color'=>'border-l-4 border-blue-400'],
                ['label'=>'Total Selesai',        'value'=>$stats['selesai'],                               'color'=>'border-l-4 border-green-400'],
                ['label'=>'Rating Saya',          'value'=>number_format($stats['rating'], 1) . ' ★',      'color'=>'border-l-4 border-gray-300'],
            ];
        @endphp
        @foreach($statCards as $card)
            <div class="bg-white border border-gray-200 {{ $card['color'] }} rounded-xl p-5 shadow-sm">
                <div class="text-gray-900 font-bold text-2xl mb-1">{{ $card['value'] }}</div>
                <div class="text-gray-400 text-xs tracking-wide">{{ $card['label'] }}</div>
            </div>
        @endforeach
    </div>

    {{-- PESANAN MENUNGGU KONFIRMASI --}}
    @if($pending->count() > 0)
        <div class="mb-10">
            <h2 class="font-display text-gray-900 text-xl tracking-widest mb-4">
                MENUNGGU KONFIRMASI
                <span class="text-yellow-500 text-base ml-2">({{ $pending->count() }})</span>
            </h2>
            <div class="space-y-4">
                @foreach($pending as $res)
                    <div class="bg-white border border-yellow-200 rounded-xl p-6 shadow-sm" x-data="{ showTolak: false }">
                        <div class="flex flex-col md:flex-row gap-4 items-start md:items-center justify-between">
                            <div class="flex-1">
                                <div class="flex items-center gap-3 mb-2">
                                    <span class="font-mono text-gray-400 text-xs">#{{ str_pad($res->id, 4, '0', STR_PAD_LEFT) }}</span>
                                    <span class="text-xs bg-yellow-100 text-yellow-700 px-2 py-0.5 rounded-full">Menunggu Konfirmasi Anda</span>
                                </div>
                                <div class="text-gray-900 font-semibold mb-1">{{ $res->customer_name }}</div>
                                <div class="text-gray-500 text-sm">
                                    {{ $res->service->name }}
                                    @if($res->service->size) ({{ $res->service->size }}) @endif
                                    &nbsp;•&nbsp; {{ $res->car_brand }} &nbsp;•&nbsp; {{ $res->plate_number }}
                                </div>
                                <div class="text-gray-400 text-xs mt-1">
                                    📅 {{ $res->reservation_date->format('d M Y') }}
                                    &nbsp;⏰ {{ $res->reservation_time }} WIB
                                </div>
                                @if($res->notes)
                                    <div class="text-gray-400 text-xs mt-1">📝 {{ $res->notes }}</div>
                                @endif
                            </div>
                            <div class="flex gap-3 shrink-0">
                                {{-- Tombol Terima --}}
                                <form method="POST" action="{{ route('karyawan.pesanan.terima', $res) }}">
                                    @csrf
                                    <button type="submit"
                                            class="bg-green-100 border border-green-300 text-green-700 hover:bg-green-200 text-xs tracking-widest uppercase px-5 py-2.5 rounded-lg transition-all font-bold">
                                        ✓ Terima
                                    </button>
                                </form>
                                {{-- Tombol Tolak --}}
                                <button @click="showTolak = !showTolak"
                                        class="bg-red-100 border border-red-300 text-red-700 hover:bg-red-200 text-xs tracking-widest uppercase px-5 py-2.5 rounded-lg transition-all font-bold">
                                    ✕ Tolak
                                </button>
                            </div>
                        </div>

                        {{-- Form Alasan Tolak --}}
                        <div x-show="showTolak" x-cloak class="mt-4 pt-4 border-t border-gray-100">
                            <form method="POST" action="{{ route('karyawan.pesanan.tolak', $res) }}">
                                @csrf
                                <label class="block text-gray-500 text-xs tracking-widest uppercase mb-2">
                                    Alasan Penolakan *
                                </label>
                                <textarea name="reject_reason" rows="2" required
                                          placeholder="Contoh: Jadwal sudah penuh, sedang sakit, dll."
                                          class="input-dark w-full px-4 py-2.5 rounded-lg text-sm resize-none mb-3"></textarea>
                                <div class="flex gap-3">
                                    <button type="submit"
                                            class="bg-red-600 hover:bg-red-700 text-white text-xs tracking-widest uppercase px-5 py-2 rounded-lg transition-all font-bold">
                                        Konfirmasi Tolak
                                    </button>
                                    <button type="button" @click="showTolak = false"
                                            class="btn-outline text-xs tracking-widest uppercase px-5 py-2 rounded-lg">
                                        Batal
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    @endif

    {{-- PESANAN AKTIF --}}
    @if($aktif->count() > 0)
        <div class="mb-10">
            <h2 class="font-display text-gray-900 text-xl tracking-widest mb-4">
                PESANAN AKTIF
                <span class="text-blue-500 text-base ml-2">({{ $aktif->count() }})</span>
            </h2>
            <div class="space-y-4">
                @foreach($aktif as $res)
                    <div class="bg-white border border-blue-200 rounded-xl p-6 shadow-sm">
                        <div class="flex flex-col md:flex-row gap-4 items-start md:items-center justify-between">
                            <div class="flex-1">
                                <div class="flex items-center gap-3 mb-2">
                                    <span class="font-mono text-gray-400 text-xs">#{{ str_pad($res->id, 4, '0', STR_PAD_LEFT) }}</span>
                                    @if($res->status === 'confirmed')
                                        <span class="text-xs bg-blue-100 text-blue-700 px-2 py-0.5 rounded-full">Dikonfirmasi</span>
                                    @else
                                        <span class="text-xs bg-orange-100 text-orange-700 px-2 py-0.5 rounded-full">Sedang Diproses</span>
                                    @endif
                                </div>
                                <div class="text-gray-900 font-semibold mb-1">{{ $res->customer_name }}</div>
                                <div class="text-gray-500 text-sm">
                                    {{ $res->service->name }}
                                    @if($res->service->size) ({{ $res->service->size }}) @endif
                                    &nbsp;•&nbsp; {{ $res->car_brand }} &nbsp;•&nbsp; {{ $res->plate_number }}
                                </div>
                                <div class="text-gray-400 text-xs mt-1">
                                    📅 {{ $res->reservation_date->format('d M Y') }}
                                    &nbsp;⏰ {{ $res->reservation_time }} WIB
                                </div>
                            </div>
                            {{-- Update status --}}
                            <form method="POST" action="{{ route('karyawan.pesanan.status', $res) }}" class="flex gap-3 shrink-0">
                                @csrf @method('PATCH')
                                <select name="status" class="input-dark px-3 py-2 rounded-lg text-sm">
                                    <option value="in_progress" {{ $res->status === 'in_progress' ? 'selected' : '' }}>
                                        Sedang Diproses
                                    </option>
                                    <option value="completed">Selesai</option>
                                </select>
                                <button type="submit"
                                        class="btn-primary text-xs tracking-widest uppercase px-5 py-2 rounded-lg">
                                    Update
                                </button>
                            </form>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    @endif

    {{-- RIWAYAT --}}
    <div>
        <h2 class="font-display text-gray-900 text-xl tracking-widest mb-4">RIWAYAT SELESAI</h2>
        @if($riwayat->count() > 0)
            <div class="bg-white border border-gray-200 rounded-xl overflow-hidden shadow-sm">
                <table class="w-full text-sm">
                    <thead>
                        <tr class="border-b border-gray-100 bg-gray-50">
                            <th class="text-left px-5 py-3 text-gray-400 text-xs font-mono uppercase tracking-wider">No.</th>
                            <th class="text-left px-5 py-3 text-gray-400 text-xs font-mono uppercase tracking-wider">Pelanggan</th>
                            <th class="text-left px-5 py-3 text-gray-400 text-xs font-mono uppercase tracking-wider">Layanan</th>
                            <th class="text-left px-5 py-3 text-gray-400 text-xs font-mono uppercase tracking-wider">Tanggal</th>
                            <th class="text-left px-5 py-3 text-gray-400 text-xs font-mono uppercase tracking-wider">Rating</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @foreach($riwayat as $res)
                            <tr class="hover:bg-gray-50 transition-colors">
                                <td class="px-5 py-3 font-mono text-gray-400 text-xs">#{{ str_pad($res->id, 4, '0', STR_PAD_LEFT) }}</td>
                                <td class="px-5 py-3 text-gray-900 font-medium">{{ $res->customer_name }}</td>
                                <td class="px-5 py-3 text-gray-500 text-xs">
                                    {{ $res->service->name }}
                                    @if($res->service->size) ({{ $res->service->size }}) @endif
                                </td>
                                <td class="px-5 py-3 text-gray-400 text-xs">{{ $res->reservation_date->format('d M Y') }}</td>
                                <td class="px-5 py-3">
                                    @if($res->rating)
                                        <span class="text-yellow-500 text-sm">
                                            @for($i = 1; $i <= 5; $i++)
                                                {{ $i <= $res->rating->rating ? '★' : '☆' }}
                                            @endfor
                                        </span>
                                        @if($res->rating->ulasan)
                                            <div class="text-gray-400 text-xs mt-0.5">{{ Str::limit($res->rating->ulasan, 40) }}</div>
                                        @endif
                                    @else
                                        <span class="text-gray-300 text-xs">Belum dirating</span>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <div class="bg-white border border-gray-200 rounded-xl p-12 text-center shadow-sm">
                <div class="text-4xl mb-3">🚗</div>
                <p class="text-gray-400 text-sm">Belum ada riwayat pesanan selesai</p>
            </div>
        @endif
    </div>

</div>

@endsection