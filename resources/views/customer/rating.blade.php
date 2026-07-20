@extends('layouts.app')
@section('title', 'Beri Rating — MATAIR Auto Care')

@section('content')

<div class="max-w-xl mx-auto px-6 py-16">

    <div class="text-center mb-10">
        <p class="text-gray-400 text-xs tracking-widest uppercase mb-3">Ulasan Layanan</p>
        <h1 class="font-display text-gray-900 text-3xl tracking-widest">BERI RATING</h1>
        <div class="w-12 h-px bg-gray-300 mx-auto mt-4"></div>
    </div>

    {{-- Info Reservasi --}}
    <div class="bg-white border border-gray-200 rounded-xl p-5 mb-6 shadow-sm">
        <div class="text-gray-400 text-xs tracking-widest uppercase mb-3">Detail Reservasi</div>
        <div class="grid grid-cols-2 gap-3 text-sm">
            <div>
                <div class="text-gray-400 text-xs mb-0.5">Layanan</div>
                <div class="text-gray-900 font-medium">
                    {{ $reservation->service->name }}
                    @if($reservation->service->size)({{ $reservation->service->size }})@endif
                </div>
            </div>
            <div>
                <div class="text-gray-400 text-xs mb-0.5">Tanggal</div>
                <div class="text-gray-900">{{ $reservation->reservation_date->format('d M Y') }}</div>
            </div>
            <div>
                <div class="text-gray-400 text-xs mb-0.5">Karyawan</div>
                <div class="text-gray-900 font-medium">{{ $reservation->karyawan->name }}</div>
            </div>
            <div>
                <div class="text-gray-400 text-xs mb-0.5">Kendaraan</div>
                <div class="text-gray-900">{{ $reservation->car_brand }} • {{ $reservation->plate_number }}</div>
            </div>
        </div>
    </div>

    {{-- Form Rating --}}
    <form method="POST" action="{{ route('rating.store', $reservation) }}"
          class="bg-white border border-gray-200 rounded-xl p-8 shadow-sm"
          x-data="{ rating: 0, hover: 0 }">
        @csrf

        @if($errors->any())
            <div class="bg-red-50 border border-red-200 rounded-lg p-4 mb-6">
                @foreach($errors->all() as $error)
                    <p class="text-red-600 text-xs">• {{ $error }}</p>
                @endforeach
            </div>
        @endif

        {{-- Bintang Rating --}}
        <div class="mb-6">
            <label class="block text-gray-500 text-xs tracking-widest uppercase mb-4">
                Rating Karyawan *
            </label>
            <div class="flex gap-3 justify-center mb-2">
                @for($i = 1; $i <= 5; $i++)
                    <button type="button"
                            @click="rating = {{ $i }}"
                            @mouseenter="hover = {{ $i }}"
                            @mouseleave="hover = 0"
                            class="text-4xl transition-all duration-150 focus:outline-none"
                            :class="(hover >= {{ $i }} || (hover === 0 && rating >= {{ $i }}))
                                ? 'text-yellow-400 scale-110'
                                : 'text-gray-200'">
                        ★
                    </button>
                @endfor
            </div>
            <input type="hidden" name="rating" :value="rating">
            <p class="text-center text-gray-400 text-xs mt-2"
               x-text="rating === 0 ? 'Pilih rating' :
                        rating === 1 ? '1 ★ — Sangat Buruk' :
                        rating === 2 ? '2 ★ — Buruk' :
                        rating === 3 ? '3 ★ — Cukup' :
                        rating === 4 ? '4 ★ — Baik' :
                        '5 ★ — Sangat Baik'">
            </p>
        </div>

        {{-- Ulasan --}}
        <div class="mb-6">
            <label class="block text-gray-500 text-xs tracking-widest uppercase mb-2">
                Ulasan (Opsional)
            </label>
            <textarea name="ulasan" rows="4"
                      placeholder="Ceritakan pengalaman Anda dengan karyawan ini..."
                      class="input-dark w-full px-4 py-3 rounded-lg text-sm resize-none">{{ old('ulasan') }}</textarea>
        </div>

        <div class="flex gap-3">
            <button type="submit"
                    :disabled="rating === 0"
                    :class="rating === 0 ? 'opacity-50 cursor-not-allowed' : ''"
                    class="btn-primary flex-1 text-xs tracking-widest uppercase py-3.5 rounded-lg">
                Kirim Rating
            </button>
            <a href="{{ route('reservasi.history') }}"
               class="btn-outline text-xs tracking-widest uppercase px-6 py-3.5 rounded-lg text-center">
                Lewati
            </a>
        </div>
    </form>

</div>

@endsection