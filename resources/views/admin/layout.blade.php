<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Admin') — MATAIR Auto Care</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Cinzel:wght@400;600;700&family=Raleway:wght@300;400;500;600;700&family=Space+Mono:wght@400;700&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: { extend: { fontFamily: { display:['Cinzel','serif'], body:['Raleway','sans-serif'], mono:['Space Mono','monospace'] } } }
        }
    </script>
    <style>
        * { font-family: 'Raleway', sans-serif; }
        .font-display { font-family: 'Cinzel', serif; }
        html, body { background: #f3f4f6; color: #111827; min-height: 100vh; }
        ::-webkit-scrollbar { width: 3px; }
        ::-webkit-scrollbar-track { background: #e5e7eb; }
        ::-webkit-scrollbar-thumb { background: #9ca3af; border-radius: 2px; }
        .input-dark { background: #f9fafb; border: 1px solid #d1d5db; color: #111827; transition: border-color 0.2s; }
        .input-dark:focus { outline: none; border-color: #374151; }
        .input-dark::placeholder { color: #9ca3af; }
        select.input-dark option { background: #fff; color: #111827; }
        .box-style { border: 1px solid #e5e7eb; background: #ffffff; }
        .btn-primary { background: #111827; color: #fff; font-weight: 700; letter-spacing: 0.08em; transition: background 0.2s; }
        .btn-primary:hover { background: #374151; }
        .btn-outline { border: 1px solid #d1d5db; color: #6b7280; transition: all 0.2s; }
        .btn-outline:hover { border-color: #374151; color: #111827; }
        .sidebar-link {
            display: flex; align-items: center; gap: 10px;
            padding: 9px 14px; border-radius: 5px;
            font-size: 12px; color: #6b7280;
            transition: all 0.2s; text-decoration: none;
            letter-spacing: 0.07em; text-transform: uppercase;
        }
        .sidebar-link:hover { background: #f3f4f6; color: #111827; }
        .sidebar-link.active { background: #111827; color: #ffffff; }
        .logo-text { font-family:'Cinzel',serif; letter-spacing:0.15em; font-weight:700; }
        [x-cloak] { display: none !important; }
    </style>
</head>
<body class="flex min-h-screen">

    {{-- SIDEBAR --}}
    <aside class="w-56 shrink-0 flex flex-col h-screen sticky top-0 bg-white border-r border-gray-200">
        <div class="p-5 border-b border-gray-100">
            <div class="flex flex-col">
                <span class="logo-text text-gray-900 text-base">MATAIR</span>
                <span style="font-family:'Raleway',sans-serif;letter-spacing:0.45em;font-size:8px;color:#9ca3af;text-transform:uppercase;margin-top:2px;">AUTO CARE</span>
                <span style="font-size:9px;letter-spacing:0.2em;color:#9ca3af;text-transform:uppercase;margin-top:4px;">ADMIN PANEL</span>
            </div>
        </div>

        <nav class="flex-1 p-3 space-y-0.5 overflow-y-auto">
            <a href="{{ route('admin.dashboard') }}"
               class="sidebar-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                <span>▪</span> Dashboard
            </a>
            <a href="{{ route('admin.reservations') }}"
               class="sidebar-link {{ request()->routeIs('admin.reservations') ? 'active' : '' }}">
                <span>▪</span> Reservasi
            </a>
            <a href="{{ route('admin.customers') }}"
               class="sidebar-link {{ request()->routeIs('admin.customers') ? 'active' : '' }}">
                <span>▪</span> Pelanggan
            </a>
            <a href="{{ route('admin.karyawan') }}"
               class="sidebar-link {{ request()->routeIs('admin.karyawan*') ? 'active' : '' }}">
                <span>▪</span> Karyawan
            </a>
            <a href="{{ route('admin.services') }}"
               class="sidebar-link {{ request()->routeIs('admin.services') ? 'active' : '' }}">
                <span>▪</span> Layanan
            </a>
            <a href="{{ route('admin.laporan') }}"
               class="sidebar-link {{ request()->routeIs('admin.laporan*') ? 'active' : '' }}">
                <span>▪</span> Laporan
            </a>
            <div class="pt-3 mt-3 border-t border-gray-100">
                <a href="{{ route('home') }}" class="sidebar-link">
                    <span>▪</span> Lihat Website
                </a>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="sidebar-link w-full text-left hover:text-red-500">
                        <span>▪</span> Keluar
                    </button>
                </form>
            </div>
        </nav>

        <div class="p-4 border-t border-gray-100">
            <div class="flex items-center gap-2">
                <div class="w-8 h-8 rounded-full bg-gray-200 border border-gray-300 flex items-center justify-center text-gray-600 text-xs font-bold">
                    {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                </div>
                <div>
                    <div class="text-gray-800 text-xs font-semibold">{{ auth()->user()->name }}</div>
                    <div class="text-gray-400 text-xs" style="font-size:9px;letter-spacing:0.1em;">ADMINISTRATOR</div>
                </div>
            </div>
        </div>
    </aside>

    {{-- MAIN --}}
    <div class="flex-1 flex flex-col min-w-0">
        <header class="bg-white border-b border-gray-200 px-6 py-4 flex items-center justify-between sticky top-0 z-10">
            <h2 class="font-display text-gray-900 text-lg tracking-wider">@yield('page-title', 'DASHBOARD')</h2>
            <div class="text-gray-400 text-xs font-mono">{{ now()->format('d M Y') }}</div>
        </header>

        @if(session('success'))
            <div class="mx-6 mt-4 bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded text-sm">
                ✓ {{ session('success') }}
            </div>
        @endif
        @if(session('error'))
            <div class="mx-6 mt-4 bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded text-sm">
                ✕ {{ session('error') }}
            </div>
        @endif

        <main class="flex-1 p-6 overflow-y-auto">
            @yield('content')
        </main>
    </div>

    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
    @stack('scripts')
</body>
</html>