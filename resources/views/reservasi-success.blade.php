@extends('layouts.app')
@section('title', 'Reservasi Berhasil — MATAIR Auto Care')

@section('content')

<div class="max-w-2xl mx-auto px-4 py-20 text-center">

    {{-- Icon --}}
    <div class="w-20 h-20 rounded-full bg-green-100 border border-green-300 flex items-center justify-center text-4xl mx-auto mb-6">
        ✓
    </div>

    <h1 class="font-display text-gray-900 text-4xl md:text-5xl tracking-widest mb-4">
        RESERVASI <span class="text-green-600">BERHASIL!</span>
    </h1>
    <p class="text-gray-500 mb-10">Tim kami akan segera menghubungi Anda untuk konfirmasi jadwal.</p>

    {{-- Detail Card --}}
    <div class="bg-white border border-gray-200 rounded-2xl p-8 text-left mb-8 shadow-sm">
        <div class="text-gray-400 text-xs tracking-widest uppercase font-mono mb-5">Detail Reservasi</div>
        <div class="grid grid-cols-2 gap-5 text-sm">
            <div>
                <div class="text-gray-400 text-xs mb-1">No. Reservasi</div>
                <div class="text-gray-900 font-mono font-bold">#{{ str_pad($reservation->id, 6, '0', STR_PAD_LEFT) }}</div>
            </div>
            <div>
                <div class="text-gray-400 text-xs mb-1">Status</div>
                <div class="inline-block bg-yellow-100 text-yellow-700 text-xs font-semibold px-3 py-1 rounded-full">
                    Menunggu Konfirmasi
                </div>
            </div>
            <div>
                <div class="text-gray-400 text-xs mb-1">Layanan</div>
                <div class="text-gray-900 font-medium">
                    {{ $reservation->service->name }}
                    @if($reservation->service->size)
                        ({{ $reservation->service->size }})
                    @endif
                </div>
            </div>
            <div>
                <div class="text-gray-400 text-xs mb-1">Total</div>
                <div class="{{ $reservation->use_free_wash ? 'text-green-600' : 'text-gray-900' }} font-bold font-mono">
                    {{ $reservation->use_free_wash ? 'GRATIS 🎁' : $reservation->formattedPrice() }}
                </div>
            </div>
            <div>
                <div class="text-gray-400 text-xs mb-1">Tanggal</div>
                <div class="text-gray-900">{{ $reservation->reservation_date->format('d M Y') }}</div>
            </div>
            <div>
                <div class="text-gray-400 text-xs mb-1">Waktu</div>
                <div class="text-gray-900">{{ $reservation->reservation_time }} WIB</div>
            </div>
            <div>
                <div class="text-gray-400 text-xs mb-1">Kendaraan</div>
                <div class="text-gray-900">{{ $reservation->car_brand }} — {{ $reservation->plate_number }}</div>
            </div>
            <div>
                <div class="text-gray-400 text-xs mb-1">Nama</div>
                <div class="text-gray-900">{{ $reservation->customer_name }}</div>
            </div>
        </div>

        @if($reservation->karyawan)
            <div class="mt-5 pt-5 border-t border-gray-100">
                <div class="text-gray-400 text-xs mb-1">Karyawan Ditugaskan</div>
                <div class="text-gray-900 font-medium">{{ $reservation->karyawan->name }}</div>
                <div class="text-gray-400 text-xs mt-0.5">{{ $reservation->karyawan->specializationLabel() }}</div>
            </div>
        @endif
    </div>

    {{-- Info Box --}}
    <div class="bg-blue-50 border border-blue-200 rounded-xl p-4 mb-8 text-left">
        <p class="text-blue-700 text-xs leading-relaxed">
            ℹ Reservasi Anda telah diterima. Karyawan akan segera ditugaskan oleh admin.
            Anda akan mendapat konfirmasi lebih lanjut melalui WhatsApp atau Email.
        </p>
    </div>

    {{-- Buttons --}}
    <div class="flex flex-wrap gap-4 justify-center">
        <a href="{{ route('reservasi.history') }}"
           class="btn-primary text-xs tracking-widest uppercase px-8 py-3.5 rounded-xl">
            Lihat Riwayat
        </a>
        <a href="{{ route('home') }}"
           class="btn-outline text-xs tracking-widest uppercase px-8 py-3.5 rounded-xl">
            Kembali ke Beranda
        </a>
    </div>

</div>

@endsection