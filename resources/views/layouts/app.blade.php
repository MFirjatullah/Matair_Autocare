<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'MATAIR Auto Care')</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Cinzel:wght@400;600;700&family=Raleway:wght@300;400;500;600;700&family=Space+Mono:wght@400;700&display=swap" rel="stylesheet">

    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        display: ['Cinzel', 'serif'],
                        body:    ['Raleway', 'sans-serif'],
                        mono:    ['Space Mono', 'monospace'],
                    },
                }
            }
        }
    </script>

    <style>
        * { font-family: 'Raleway', sans-serif; }
        .font-display { font-family: 'Cinzel', serif; }
        .font-mono    { font-family: 'Space Mono', monospace; }

        html, body { background: #f9fafb; color: #111827; min-height: 100vh; }

        ::-webkit-scrollbar { width: 4px; }
        ::-webkit-scrollbar-track { background: #f3f4f6; }
        ::-webkit-scrollbar-thumb { background: #d1d5db; border-radius: 2px; }

        /* Navbar */
        .nav-blur {
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            background: rgba(255,255,255,0.95);
            border-bottom: 1px solid #e5e7eb;
            box-shadow: 0 1px 3px rgba(0,0,0,0.05);
        }

        /* Box style */
        .box-style {
            border: 1px solid #e5e7eb;
            background: #ffffff;
            box-shadow: 0 1px 3px rgba(0,0,0,0.04);
        }
        .box-style:hover {
            border-color: #d1d5db;
            box-shadow: 0 2px 8px rgba(0,0,0,0.08);
        }

        /* Input */
        .input-dark {
            background: #f9fafb;
            border: 1px solid #d1d5db;
            color: #111827;
            transition: border-color 0.2s, box-shadow 0.2s;
        }
        .input-dark:focus {
            outline: none;
            border-color: #374151;
            box-shadow: 0 0 0 3px rgba(55,65,81,0.08);
        }
        .input-dark::placeholder { color: #9ca3af; }
        select.input-dark option { background: #fff; color: #111827; }

        /* Card hover */
        .card-hover { transition: transform 0.2s ease, box-shadow 0.2s ease; }
        .card-hover:hover { transform: translateY(-2px); box-shadow: 0 8px 24px rgba(0,0,0,0.1); }

        /* Buttons */
        .btn-primary {
            background: #111827;
            color: #ffffff;
            font-weight: 700;
            letter-spacing: 0.08em;
            transition: background 0.2s;
        }
        .btn-primary:hover { background: #374151; }

        .btn-outline {
            border: 1px solid #d1d5db;
            color: #6b7280;
            transition: all 0.2s;
        }
        .btn-outline:hover { border-color: #374151; color: #111827; }

        /* Logo */
        .logo-text { font-family:'Cinzel',serif; letter-spacing:0.15em; font-weight:700; color:#111827; }
        .logo-sub  { font-family:'Raleway',sans-serif; letter-spacing:0.45em; font-size:9px; color:#9ca3af; text-transform:uppercase; }

        /* Sidebar admin */
        .sidebar-link {
            display:flex; align-items:center; gap:10px;
            padding:9px 14px; border-radius:6px;
            font-size:12px; color:#6b7280;
            transition:all 0.2s; text-decoration:none; letter-spacing:0.05em;
        }
        .sidebar-link:hover  { background:#f3f4f6; color:#111827; }
        .sidebar-link.active { background:#111827; color:#ffffff; }

        /* Status badges */
        .badge-pending     { background:#fef9c3; color:#854d0e; padding:2px 10px; border-radius:999px; font-size:11px; }
        .badge-confirmed   { background:#dbeafe; color:#1e40af; padding:2px 10px; border-radius:999px; font-size:11px; }
        .badge-progress    { background:#ffedd5; color:#9a3412; padding:2px 10px; border-radius:999px; font-size:11px; }
        .badge-completed   { background:#dcfce7; color:#166534; padding:2px 10px; border-radius:999px; font-size:11px; }
        .badge-cancelled   { background:#fee2e2; color:#991b1b; padding:2px 10px; border-radius:999px; font-size:11px; }

        /* Animate */
        @keyframes fadeUp {
            from { opacity:0; transform:translateY(16px); }
            to   { opacity:1; transform:translateY(0); }
        }
        .fade-up  { animation:fadeUp 0.6s ease forwards; }
        .delay-1  { animation-delay:0.1s; opacity:0; }
        .delay-2  { animation-delay:0.2s; opacity:0; }
        .delay-3  { animation-delay:0.3s; opacity:0; }
        .delay-4  { animation-delay:0.4s; opacity:0; }

        [x-cloak] { display: none !important; }
    </style>

    @stack('styles')
</head>
<body>

    {{-- NAVBAR --}}
    <nav class="nav-blur fixed top-0 left-0 right-0 z-50">
        <div class="max-w-6xl mx-auto px-6 lg:px-8">
            <div class="flex items-center justify-between h-16">

                {{-- Logo --}}
                <a href="{{ route('home') }}" class="flex flex-col items-center leading-none">
                    <span class="logo-text text-lg">MATAIR</span>
                    <span class="logo-sub mt-0.5">AUTO CARE</span>
                </a>

                {{-- Nav Links --}}
                <div class="hidden md:flex items-center gap-8">
                    <a href="{{ route('home') }}"
                       class="text-xs tracking-widest uppercase {{ request()->routeIs('home') ? 'text-gray-900 font-semibold' : 'text-gray-500 hover:text-gray-900' }} transition-colors">
                        Beranda
                    </a>
                    <a href="{{ route('home') }}#layanan"
                       class="text-xs tracking-widest uppercase text-gray-500 hover:text-gray-900 transition-colors">
                        Layanan
                    </a>
                    @auth
                        @if(auth()->user()->isCustomer() || auth()->user()->isAdmin())
                            <a href="{{ route('reservasi') }}"
                               class="text-xs tracking-widest uppercase {{ request()->routeIs('reservasi') ? 'text-gray-900 font-semibold' : 'text-gray-500 hover:text-gray-900' }} transition-colors">
                                Reservasi
                            </a>
                        @endif
                        @if(auth()->user()->isCustomer())
                            <a href="{{ route('reservasi.history') }}"
                               class="text-xs tracking-widest uppercase {{ request()->routeIs('reservasi.history') ? 'text-gray-900 font-semibold' : 'text-gray-500 hover:text-gray-900' }} transition-colors">
                                Riwayat
                            </a>
                        @endif
                        @if(auth()->user()->isKaryawan())
                            <a href="{{ route('karyawan.dashboard') }}"
                               class="text-xs tracking-widest uppercase {{ request()->routeIs('karyawan.*') ? 'text-gray-900 font-semibold' : 'text-gray-500 hover:text-gray-900' }} transition-colors">
                                Dashboard
                            </a>
                        @endif
                        @if(auth()->user()->isAdmin())
                            <a href="{{ route('admin.dashboard') }}"
                               class="text-xs tracking-widest uppercase text-gray-600 hover:text-gray-900 border border-gray-300 px-3 py-1 rounded transition-colors">
                                Admin Panel
                            </a>
                        @endif
                    @endauth
                </div>

                {{-- Auth Buttons --}}
                <div class="flex items-center gap-4">
                    @guest
                        <a href="{{ route('login') }}"
                           class="text-xs tracking-widest uppercase text-gray-500 hover:text-gray-900 transition-colors">
                            Masuk
                        </a>
                        <a href="{{ route('register') }}"
                           class="btn-primary text-xs tracking-widest uppercase px-5 py-2 rounded">
                            Daftar
                        </a>
                    @endguest
                    @auth
                        <span class="text-xs text-gray-400 hidden sm:block">{{ auth()->user()->name }}</span>
                        @if(auth()->user()->freeWashAvailable() > 0)
                            <span class="text-xs border border-gray-200 text-gray-600 px-2 py-1 rounded font-mono bg-gray-50">
                                🎁 {{ auth()->user()->freeWashAvailable() }}x
                            </span>
                        @endif
                        <form method="POST" action="{{ route('logout') }}" class="inline">
                            @csrf
                            <button type="submit" class="text-xs text-gray-400 hover:text-red-500 transition-colors tracking-widest uppercase">
                                Keluar
                            </button>
                        </form>
                    @endauth
                </div>

            </div>
        </div>
    </nav>

    {{-- Flash Messages --}}
    @if(session('success'))
        <div class="fixed top-20 right-4 z-50 bg-white border border-gray-200 text-gray-700 px-5 py-3 rounded-lg text-xs tracking-wide max-w-sm shadow-md fade-up">
            ✓ {{ session('success') }}
        </div>
    @endif
    @if(session('info'))
        <div class="fixed top-20 right-4 z-50 bg-blue-50 border border-blue-200 text-blue-700 px-5 py-3 rounded-lg text-xs tracking-wide max-w-sm shadow-md fade-up">
            ℹ {{ session('info') }}
        </div>
    @endif
    @if(session('error'))
        <div class="fixed top-20 right-4 z-50 bg-red-50 border border-red-200 text-red-700 px-5 py-3 rounded-lg text-xs tracking-wide max-w-sm shadow-md fade-up">
            ✕ {{ session('error') }}
        </div>
    @endif

    {{-- Main --}}
    <main class="relative pt-16">
        @yield('content')
    </main>

    {{-- Footer --}}
    <footer class="bg-gray-900 mt-20">
        <div class="max-w-6xl mx-auto px-6 py-12">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-10">
                <div>
                    <div class="flex flex-col mb-4">
                        <span style="font-family:'Cinzel',serif;letter-spacing:0.15em;font-weight:700;color:#fff;font-size:18px;">MATAIR</span>
                        <span style="font-family:'Raleway',sans-serif;letter-spacing:0.45em;font-size:9px;color:#6b7280;text-transform:uppercase;margin-top:4px;">AUTO CARE</span>
                    </div>
                    <p class="text-gray-400 text-xs leading-relaxed">
                        Professional car detailing & carwash dengan standar kualitas premium.
                    </p>
                </div>
                <div>
                    <h4 class="text-white text-xs tracking-widest uppercase mb-4 font-semibold">Layanan</h4>
                    <ul class="space-y-2 text-gray-400 text-xs">
                        <li>Full Detailing</li>
                        <li>Interior & Exterior Detail</li>
                        <li>Ceramic Coating</li>
                        <li>Regular & Special Wash</li>
                    </ul>
                </div>
                <div>
                    <h4 class="text-white text-xs tracking-widest uppercase mb-4 font-semibold">Kontak</h4>
                    <ul class="space-y-2 text-gray-400 text-xs">
                        <li>📍 Jl. Puri Tj. Sari Jl. Ring Road No.20 A Pasar 1, Tj. Sari, Kec. Medan Selayang, Kota Medan</li>
                        <li>📞 0812-3456-7890</li>
                        <li>✉️ info@matair-autocare.com</li>
                        <li>⏰ Senin – minggu, 08:00 – 17:00</li>
                    </ul>
                </div>
            </div>
            <div class="border-t border-gray-800 mt-8 pt-8 text-center text-gray-500 text-xs tracking-widest">
                © {{ date('Y') }} MATAIR AUTO CARE. ALL RIGHTS RESERVED.
            </div>
        </div>
    </footer>

    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
    @stack('scripts')
</body>
</html>