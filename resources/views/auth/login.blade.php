@extends('layouts.app')
@section('title', 'Masuk — MATAIR Auto Care')

@section('content')
<div class="min-h-screen flex items-center justify-center px-4 py-16 bg-gray-50">
    <div class="w-full max-w-sm">
        <div class="text-center mb-8">
            <div class="w-14 h-14 rounded-full bg-gray-900 flex items-center justify-center mx-auto mb-4">
                <span style="font-family:'Cinzel',serif;color:#fff;font-weight:700;font-size:14px;letter-spacing:1px;">MA</span>
            </div>
            <h1 class="font-display text-gray-900 text-2xl tracking-widest">MATAIR</h1>
            <p class="text-gray-400 text-xs tracking-widest mt-1">Masuk ke Akun Anda</p>
        </div>
        <div class="bg-white border border-gray-200 rounded-2xl p-8 shadow-sm">
            @if($errors->any())
                <div class="bg-red-50 border border-red-200 rounded-lg p-4 mb-6">
                    @foreach($errors->all() as $error)
                        <p class="text-red-600 text-xs">• {{ $error }}</p>
                    @endforeach
                </div>
            @endif
            @if(session('info'))
                <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 mb-6 text-blue-700 text-xs">
                    ℹ {{ session('info') }}
                </div>
            @endif
            <form method="POST" action="{{ route('login') }}" class="space-y-5">
                @csrf
                <div>
                    <label class="block text-gray-500 text-xs tracking-widest uppercase mb-2">Email</label>
                    <input type="email" name="email" value="{{ old('email') }}" placeholder="email@contoh.com" required autofocus class="input-dark w-full px-4 py-3 rounded-lg text-sm">
                </div>
                <div>
                    <label class="block text-gray-500 text-xs tracking-widest uppercase mb-2">Password</label>
                    <input type="password" name="password" placeholder="••••••••" required class="input-dark w-full px-4 py-3 rounded-lg text-sm">
                </div>
                <div class="flex items-center gap-2">
                    <input type="checkbox" name="remember" id="remember" class="w-4 h-4 accent-gray-900">
                    <label for="remember" class="text-gray-500 text-xs cursor-pointer">Ingat saya</label>
                </div>
                <button type="submit" class="btn-primary w-full text-xs tracking-widest uppercase py-3.5 rounded-lg">Masuk</button>
            </form>
            <div class="flex items-center gap-4 my-6">
                <div class="flex-1 h-px bg-gray-200"></div>
                <span class="text-gray-300 text-xs tracking-widest">ATAU</span>
                <div class="flex-1 h-px bg-gray-200"></div>
            </div>
            <p class="text-center text-gray-500 text-xs">
                Belum punya akun?
                <a href="{{ route('register') }}" class="text-gray-900 font-semibold hover:underline ml-1">Daftar Sekarang</a>
            </p>
            <div class="mt-6 p-3 bg-gray-50 border border-gray-100 rounded-lg text-xs text-gray-400 space-y-1 font-mono">
                <div>admin@mataair.com / admin123</div>
                <div>customer@demo.com / password</div>
                <div>andi@mataair.com / password</div>
            </div>
        </div>
    </div>
</div>
@endsection