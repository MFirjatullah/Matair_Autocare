@extends('layouts.app')
@section('title', 'Daftar — MATAIR Auto Care')

@section('content')
<div class="min-h-screen flex items-center justify-center px-4 py-16 bg-gray-50">
    <div class="w-full max-w-sm">
        <div class="text-center mb-8">
            <div class="w-14 h-14 rounded-full bg-gray-900 flex items-center justify-center mx-auto mb-4">
                <span style="font-family:'Cinzel',serif;color:#fff;font-weight:700;font-size:14px;letter-spacing:1px;">MA</span>
            </div>
            <h1 class="font-display text-gray-900 text-2xl tracking-widest">MATAIR</h1>
            <p class="text-gray-400 text-xs tracking-widest mt-1">Buat Akun Baru</p>
        </div>
        <div class="flex justify-center gap-3 mb-6">
            @foreach(['🎁 10x = 1 Gratis','📱 Booking Mudah','📋 Histori'] as $b)
                <span class="border border-gray-200 text-gray-500 text-xs px-3 py-1 rounded-full bg-white">{{ $b }}</span>
            @endforeach
        </div>
        <div class="bg-white border border-gray-200 rounded-2xl p-8 shadow-sm">
            @if($errors->any())
                <div class="bg-red-50 border border-red-200 rounded-lg p-4 mb-6">
                    @foreach($errors->all() as $error)
                        <p class="text-red-600 text-xs">• {{ $error }}</p>
                    @endforeach
                </div>
            @endif
            <form method="POST" action="{{ route('register') }}" class="space-y-4">
                @csrf
                <div>
                    <label class="block text-gray-500 text-xs tracking-widest uppercase mb-2">Nama Lengkap *</label>
                    <input type="text" name="name" value="{{ old('name') }}" placeholder="Nama lengkap" required autofocus class="input-dark w-full px-4 py-3 rounded-lg text-sm">
                </div>
                <div>
                    <label class="block text-gray-500 text-xs tracking-widest uppercase mb-2">Email *</label>
                    <input type="email" name="email" value="{{ old('email') }}" placeholder="email@contoh.com" required class="input-dark w-full px-4 py-3 rounded-lg text-sm">
                </div>
                <div>
                    <label class="block text-gray-500 text-xs tracking-widest uppercase mb-2">Nomor HP *</label>
                    <input type="tel" name="phone" value="{{ old('phone') }}" placeholder="08xxxxxxxxxx" required class="input-dark w-full px-4 py-3 rounded-lg text-sm">
                </div>
                <div>
                    <label class="block text-gray-500 text-xs tracking-widest uppercase mb-2">Password *</label>
                    <input type="password" name="password" placeholder="Minimal 6 karakter" required class="input-dark w-full px-4 py-3 rounded-lg text-sm">
                </div>
                <div>
                    <label class="block text-gray-500 text-xs tracking-widest uppercase mb-2">Konfirmasi Password *</label>
                    <input type="password" name="password_confirmation" placeholder="Ulangi password" required class="input-dark w-full px-4 py-3 rounded-lg text-sm">
                </div>
                <button type="submit" class="btn-primary w-full text-xs tracking-widest uppercase py-3.5 rounded-lg mt-2">Buat Akun</button>
            </form>
            <div class="flex items-center gap-4 my-6">
                <div class="flex-1 h-px bg-gray-200"></div>
                <span class="text-gray-300 text-xs tracking-widest">ATAU</span>
                <div class="flex-1 h-px bg-gray-200"></div>
            </div>
            <p class="text-center text-gray-500 text-xs">
                Sudah punya akun?
                <a href="{{ route('login') }}" class="text-gray-900 font-semibold hover:underline ml-1">Masuk di sini</a>
            </p>
        </div>
    </div>
</div>
@endsection