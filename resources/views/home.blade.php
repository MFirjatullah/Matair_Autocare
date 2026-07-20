@extends('layouts.app')
@section('title', 'MATAIR Auto Care — Premium Car Care')

@section('content')

{{-- HERO --}}
<section class="relative min-h-screen flex items-center justify-center overflow-hidden bg-linear-to-br from-white via-gray-50 to-gray-100">
    <div class="absolute top-0 right-0 w-96 h-96 bg-gray-100 rounded-full blur-3xl opacity-60 -translate-y-1/2 translate-x-1/2"></div>
    <div class="absolute bottom-0 left-0 w-64 h-64 bg-gray-200 rounded-full blur-3xl opacity-40 translate-y-1/2 -translate-x-1/2"></div>

    <div class="relative max-w-4xl mx-auto px-6 text-center py-24">
        

        <div class="fade-up delay-2 flex flex-col items-center mb-6">
            <span class="font-display text-gray-900 text-5xl md:text-6xl tracking-widest leading-tight">MATAIR</span>
            <span style="font-family:'Raleway',sans-serif;letter-spacing:0.45em;font-size:11px;color:#9ca3af;text-transform:uppercase;margin-top:6px;">AUTO CARE</span>
        </div>

        <div class="fade-up delay-3 w-16 h-px bg-gray-300 mx-auto mb-6"></div>

        <p class="fade-up delay-3 text-gray-500 text-base leading-relaxed mb-10 max-w-xl mx-auto">
            Professional car detailing & carwash dengan standar kualitas premium. Kami hadirkan kendaraan Anda dalam kondisi terbaik.
        </p>

        <div class="fade-up delay-4 flex flex-wrap gap-4 justify-center">
            <a href="{{ route('reservasi') }}"
               class="btn-primary text-xs tracking-widest uppercase px-8 py-3.5 rounded-lg shadow-sm">
                Buat Reservasi →
            </a>
            <a href="#layanan"
               class="btn-outline text-xs tracking-widest uppercase px-8 py-3.5 rounded-lg">
                Lihat Layanan
            </a>
        </div>

        {{-- Stats --}}
        <div class="fade-up mt-16 flex justify-center gap-12 pt-10 border-t border-gray-200">
            <div class="text-center">
                <div class="font-display text-gray-900 text-2xl font-bold">500+</div>
                <div class="text-gray-400 text-xs tracking-widest uppercase mt-1">Pelanggan</div>
            </div>
            <div class="w-px bg-gray-200"></div>
            <div class="text-center">
                <div class="font-display text-gray-900 text-2xl font-bold">7</div>
                <div class="text-gray-400 text-xs tracking-widest uppercase mt-1">Jenis Detailing</div>
            </div>
            <div class="w-px bg-gray-200"></div>
            <div class="text-center">
                <div class="font-display text-gray-900 text-2xl font-bold">5★</div>
                <div class="text-gray-400 text-xs tracking-widests uppercase mt-1">Rating</div>
            </div>
        </div>
    </div>
</section>

{{-- LAYANAN --}}
<section id="layanan" class="py-24 bg-white">
    <div class="max-w-6xl mx-auto px-6">

        <div class="text-center mb-16">
            <p class="text-gray-400 text-xs tracking-widest uppercase mb-3 font-mono">Pilihan Layanan</p>
            <h2 class="font-display text-gray-900 text-3xl md:text-4xl tracking-widest">PAKET LAYANAN</h2>
            <div class="w-12 h-0.5 bg-gray-300 mx-auto mt-4"></div>
        </div>

        {{-- DETAILING --}}
        <div class="mb-20" x-data="{ activeSize: 'Normal' }">
            <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4 mb-8">
                <div>
                    <p class="text-gray-400 text-xs tracking-widest uppercase mb-1 font-mono">Paket 01</p>
                    <h3 class="font-display text-gray-900 text-2xl tracking-widest">Detailing Menu</h3>
                    <p class="text-gray-400 text-xs mt-1">7 jenis layanan detailing profesional</p>
                </div>
                <div class="flex border border-gray-200 rounded-lg overflow-hidden shadow-sm">
                    @foreach(['Normal', 'Large', 'Exotic'] as $size)
                        <button @click="activeSize = '{{ $size }}'"
                                :class="activeSize === '{{ $size }}' ? 'bg-gray-900 text-white' : 'text-gray-400 hover:text-gray-700 hover:bg-gray-50'"
                                class="px-5 py-2 text-xs tracking-widest uppercase transition-all font-medium">
                            {{ $size }}
                        </button>
                    @endforeach
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                @php
                    $detailingServices = $services['detailing'] ?? collect();
                    $groupedDetailing  = $detailingServices->groupBy('name');
                @endphp
                @foreach($groupedDetailing as $name => $variants)
                    <div class="box-style card-hover rounded-xl p-5">
                        <div class="font-mono text-gray-200 text-4xl leading-none mb-3 select-none font-bold">
                            {{ str_pad($loop->iteration, 2, '0', STR_PAD_LEFT) }}
                        </div>
                        <h4 class="text-gray-900 font-semibold text-sm mb-2">{{ $name }}</h4>
                        <p class="text-gray-400 text-xs leading-relaxed mb-4">{{ Str::limit($variants->first()->description, 60) }}</p>
                        @foreach(['Normal', 'Large', 'Exotic'] as $size)
                            @php $variant = $variants->where('size', $size)->first(); @endphp
                            @if($variant)
                                <div x-show="activeSize === '{{ $size }}'"
                                     class="bg-gray-50 border border-gray-200 rounded-lg px-3 py-2 flex justify-between items-center">
                                    <span class="text-gray-400 text-xs">{{ $size }}</span>
                                    <span class="text-gray-900 font-mono text-sm font-semibold">
                                        Rp {{ number_format($variant->price, 0, ',', '.') }}
                                    </span>
                                </div>
                            @endif
                        @endforeach
                    </div>
                @endforeach
            </div>
        </div>

        {{-- CARWASH --}}
        <div>
            <div class="mb-8">
                <p class="text-gray-400 text-xs tracking-widest uppercase mb-1 font-mono">Paket 02</p>
                <h3 class="font-display text-gray-900 text-2xl tracking-widest">Carwash Menu</h3>
                <p class="text-gray-400 text-xs mt-1">2 pilihan cuci kendaraan harian</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                @php
                    $carwashServices = $services['carwash'] ?? collect();
                    $groupedCarwash  = $carwashServices->groupBy('name');
                @endphp
                @foreach($groupedCarwash as $name => $variants)
                    <div class="box-style card-hover rounded-2xl p-8">
                        <div class="flex items-start justify-between mb-4">
                            <div>
                                <p class="text-gray-300 font-mono text-xs mb-1">0{{ $loop->iteration }}</p>
                                <h4 class="font-display text-gray-900 text-2xl tracking-widest">{{ strtoupper($name) }}</h4>
                            </div>
                            <div class="w-10 h-10 rounded-full bg-gray-100 flex items-center justify-center text-xl">
                                {{ $loop->iteration === 1 ? '🚿' : '✨' }}
                            </div>
                        </div>
                        <div class="w-8 h-0.5 bg-gray-200 mb-4"></div>
                        <p class="text-gray-400 text-xs leading-relaxed mb-6">{{ $variants->first()->description }}</p>
                        @if($loop->iteration === 1)
                            <ul class="space-y-1.5 mb-6 text-xs text-gray-400">
                                <li class="flex items-center gap-2"><span class="text-gray-600">✓</span> Cuci eksterior menyeluruh</li>
                                <li class="flex items-center gap-2"><span class="text-gray-600">✓</span> Sampo mobil premium</li>
                                <li class="flex items-center gap-2"><span class="text-gray-600">✓</span> Bilas & lap kering</li>
                            </ul>
                        @else
                            <ul class="space-y-1.5 mb-6 text-xs text-gray-400">
                                <li class="flex items-center gap-2"><span class="text-gray-600">✓</span> Semua layanan Regular Wash</li>
                                <li class="flex items-center gap-2"><span class="text-gray-600">✓</span> Vacuum interior & bersih dashboard</li>
                                <li class="flex items-center gap-2"><span class="text-gray-600">✓</span> Parfum kabin & semir ban</li>
                                <li class="flex items-center gap-2"><span class="text-gray-600">✓</span> Meguiar's Gold Class Wax</li>
                            </ul>
                        @endif
                        <div class="bg-gray-50 border border-gray-200 rounded-xl px-5 py-3 flex justify-between items-center">
                            <span class="text-gray-400 text-xs tracking-widest uppercase">Harga</span>
                            <span class="text-gray-900 font-mono text-xl font-bold">
                                Rp {{ number_format($variants->first()->price, 0, ',', '.') }}
                            </span>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</section>

{{-- LOYALTY --}}
<section class="py-24 bg-gray-50">
    <div class="max-w-4xl mx-auto px-6">
        <div class="bg-white border border-gray-200 rounded-3xl p-10 md:p-14 text-center shadow-sm">
            <p class="text-gray-400 text-xs tracking-widests uppercase mb-4 font-mono">Loyalty Program</p>
            <h2 class="font-display text-gray-900 text-3xl md:text-4xl tracking-widest mb-2">
                10X CUCI = 1 GRATIS
            </h2>
            <div class="w-12 h-0.5 bg-gray-200 mx-auto my-6"></div>
            <p class="text-gray-500 text-sm leading-relaxed mb-8 max-w-lg mx-auto">
                Setiap kali kendaraan Anda dicuci di MATAIR Auto Care, Anda mengumpulkan poin.
                Setelah <strong class="text-gray-900">10 kali cuci</strong>, dapatkan
                <strong class="text-gray-900">1 kali cuci GRATIS</strong>!
            </p>

            <div class="flex flex-col sm:flex-row gap-4 justify-center mb-10">
                @foreach([['1','Daftar & login ke akun Anda'],['2','Buat reservasi & kunjungi kami'],['3','Kumpulkan 10 cuci, tukar 1 gratis']] as $step)
                    <div class="border border-gray-200 rounded-xl p-4 flex items-start gap-3 flex-1 text-left bg-gray-50">
                        <span class="w-6 h-6 rounded-full bg-gray-900 text-white flex items-center justify-center text-xs font-bold shrink-0">{{ $step[0] }}</span>
                        <span class="text-gray-500 text-xs leading-relaxed">{{ $step[1] }}</span>
                    </div>
                @endforeach
            </div>

            <div class="flex flex-wrap gap-4 justify-center">
                <a href="{{ route('register') }}" class="btn-primary text-xs tracking-widest uppercase px-8 py-3.5 rounded-lg shadow-sm">
                    Daftar Sekarang
                </a>
                <a href="{{ route('reservasi') }}" class="btn-outline text-xs tracking-widest uppercase px-8 py-3.5 rounded-lg">
                    Buat Reservasi
                </a>
            </div>
        </div>
    </div>
</section>

@endsection