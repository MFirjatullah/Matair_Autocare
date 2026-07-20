@extends('layouts.app')
@section('title', 'Riwayat Reservasi — MATAIR Auto Care')

@section('content')

<div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-12">

    {{-- Header --}}
    <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4 mb-10">
        <div>
            <span class="font-mono text-xs text-gray-400 tracking-wider">AKUN SAYA</span>
            <h1 class="font-display text-gray-900 text-4xl md:text-5xl tracking-widest mt-1">
                RIWAYAT <span class="text-gray-400">RESERVASI</span>
            </h1>
        </div>
        <a href="{{ route('reservasi') }}"
           class="btn-primary text-xs tracking-widest uppercase px-6 py-3 rounded-lg">
            + Reservasi Baru
        </a>
    </div>

    {{-- Loyalty Card --}}
    @php $user = auth()->user(); @endphp
    <div class="bg-white border border-gray-200 rounded-2xl p-6 mb-8 shadow-sm">
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-6 items-center">
            <div class="sm:col-span-2">
                <div class="flex items-center gap-3 mb-3">
                    <div class="w-10 h-10 rounded-full bg-gray-100 border border-gray-200 flex items-center justify-center font-bold text-gray-700">
                        {{ strtoupper(substr($user->name, 0, 1)) }}
                    </div>
                    <div>
                        <div class="text-gray-900 font-semibold">{{ $user->name }}</div>
                        <div class="text-gray-400 text-xs">Member MATAIR Auto Care</div>
                    </div>
                </div>
                <div class="text-gray-500 text-sm mb-3">
                    Total cuci: <strong class="text-gray-900">{{ $user->wash_count }}</strong> kali •
                    Gratis tersisa: <strong class="text-gray-900">{{ $user->freeWashAvailable() }}</strong>
                </div>
                <div class="space-y-2">
                    <div class="flex justify-between text-xs text-gray-400">
                        <span>Progress menuju cuci gratis berikutnya</span>
                        <span>{{ $user->wash_count % 10 }}/10</span>
                    </div>
                    <div class="h-1.5 bg-gray-100 rounded-full overflow-hidden">
                        <div class="h-full bg-gray-700 rounded-full transition-all"
                             style="width: {{ $user->loyaltyProgress() }}%"></div>
                    </div>
                </div>
            </div>
            <div class="text-center sm:text-right">
                @if($user->freeWashAvailable() > 0)
                    <div class="inline-block border border-gray-200 bg-gray-50 rounded-2xl p-4">
                        <div class="text-gray-900 text-3xl font-display">{{ $user->freeWashAvailable() }}x</div>
                        <div class="text-gray-400 text-xs">Cuci Gratis</div>
                    </div>
                @else
                    <div class="text-gray-400 text-sm">
                        Cuci <span class="text-gray-900 font-bold">{{ $user->washesUntilNextFree() }}</span> kali lagi<br>untuk dapat gratis!
                    </div>
                @endif
            </div>
        </div>
    </div>

    {{-- Flash --}}
    @if(session('success'))
        <div class="bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-lg text-sm mb-6">
            ✓ {{ session('success') }}
        </div>
    @endif

    {{-- Reservations --}}
    @if($reservations->isEmpty())
        <div class="bg-white border border-gray-200 rounded-3xl p-16 text-center shadow-sm">
            <div class="text-5xl mb-4">🚗</div>
            <h3 class="font-display text-gray-900 text-3xl tracking-widest mb-2">BELUM ADA RESERVASI</h3>
            <p class="text-gray-400 mb-6">Buat reservasi pertama Anda sekarang!</p>
            <a href="{{ route('reservasi') }}"
               class="inline-block btn-primary text-xs tracking-widest uppercase px-8 py-3 rounded-lg">
                Buat Reservasi
            </a>
        </div>
    @else
        <div class="space-y-4">
            @foreach($reservations as $res)
                <div class="bg-white border border-gray-200 rounded-2xl p-5 shadow-sm hover:shadow-md transition-shadow">
                    <div class="flex flex-col sm:flex-row gap-4 items-start sm:items-center justify-between">
                        <div class="flex items-start gap-4">
                            <div class="w-12 h-12 rounded-lg bg-gray-50 border border-gray-200 flex items-center justify-center font-mono text-gray-400 text-xs font-bold shrink-0">
                                #{{ str_pad($res->id, 4, '0', STR_PAD_LEFT) }}
                            </div>
                            <div>
                                <div class="text-gray-900 font-semibold">
                                    {{ $res->service->name }}
                                    @if($res->service->size)
                                        <span class="text-gray-400 text-xs font-normal">({{ $res->service->size }})</span>
                                    @endif
                                </div>
                                <div class="text-gray-500 text-sm">{{ $res->car_brand }} • {{ $res->plate_number }}</div>
                                <div class="text-gray-400 text-xs mt-1">
                                    📅 {{ $res->reservation_date->format('d M Y') }} • ⏰ {{ $res->reservation_time }} WIB
                                </div>
                                {{-- Info Karyawan --}}
                                @if($res->karyawan)
                                    <div class="text-gray-400 text-xs mt-1">
                                        👷 Karyawan: <span class="text-gray-700 font-medium">{{ $res->karyawan->name }}</span>
                                        @php
                                            $asl = ['waiting'=>'Menunggu','accepted'=>'Diterima','rejected'=>'Ditolak','reassigned'=>'Dialihkan'];
                                            $asc = ['waiting'=>'text-yellow-600','accepted'=>'text-green-600','rejected'=>'text-red-600','reassigned'=>'text-blue-600'];
                                        @endphp
                                        <span class="ml-1 {{ $asc[$res->assign_status] ?? '' }}">
                                            ({{ $asl[$res->assign_status] ?? '' }})
                                        </span>
                                    </div>
                                    @if($res->reject_reason)
                                        <div class="text-red-500 text-xs mt-0.5">
                                            Alasan tolak: {{ $res->reject_reason }}
                                        </div>
                                    @endif
                                @endif
                            </div>
                        </div>

                        <div class="flex items-center gap-4 shrink-0">
                            <div class="text-right">
                                <div class="font-bold font-mono {{ $res->use_free_wash ? 'text-green-600' : 'text-gray-900' }}">
                                    {{ $res->use_free_wash ? 'GRATIS 🎁' : $res->formattedPrice() }}
                                </div>
                            </div>
                            <div>
                                @php
                                    $sc = ['pending'=>'bg-yellow-100 text-yellow-700 border-yellow-200','confirmed'=>'bg-blue-100 text-blue-700 border-blue-200','in_progress'=>'bg-orange-100 text-orange-700 border-orange-200','completed'=>'bg-green-100 text-green-700 border-green-200','cancelled'=>'bg-red-100 text-red-700 border-red-200'];
                                    $sl = ['pending'=>'Menunggu','confirmed'=>'Dikonfirmasi','in_progress'=>'Diproses','completed'=>'Selesai','cancelled'=>'Dibatalkan'];
                                @endphp
                                <span class="text-xs px-3 py-1 rounded-full border {{ $sc[$res->status] ?? '' }}">
                                    {{ $sl[$res->status] ?? $res->status }}
                                </span>
                            </div>
                        </div>
                    </div>

                    {{-- Tombol Rating --}}
                    @if($res->canBeRated())
                        <div class="mt-3 pt-3 border-t border-gray-100 flex justify-end">
                            <a href="{{ route('rating.create', $res) }}"
                               class="btn-primary text-xs tracking-widest uppercase px-5 py-2 rounded-lg">
                                ★ Beri Rating
                            </a>
                        </div>
                    @elseif($res->hasRating())
                        <div class="mt-3 pt-3 border-t border-gray-100 flex items-center gap-2">
                            <span class="text-yellow-500 text-sm">
                                @for($i = 1; $i <= 5; $i++)
                                    {{ $i <= $res->rating->rating ? '★' : '☆' }}
                                @endfor
                            </span>
                            <span class="text-gray-400 text-xs">
                                {{ $res->rating->ulasan ? '"' . Str::limit($res->rating->ulasan, 60) . '"' : 'Sudah dirating' }}
                            </span>
                        </div>
                    @endif

                    @if($res->notes)
                        <div class="mt-2 text-gray-400 text-xs">📝 {{ $res->notes }}</div>
                    @endif
                </div>
            @endforeach
        </div>

        <div class="mt-8">{{ $reservations->links() }}</div>
    @endif

</div>

@endsection