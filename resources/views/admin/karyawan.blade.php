@extends('admin.layout')
@section('title', 'Kelola Karyawan')
@section('page-title', 'KELOLA KARYAWAN')

@section('content')

{{-- Tambah Karyawan --}}
<div class="bg-white border border-gray-200 rounded-xl p-6 mb-6 shadow-sm" x-data="{ open: false }">
    <div class="flex items-center justify-between mb-4">
        <h3 class="text-gray-900 font-semibold text-sm tracking-wide">Tambah Karyawan Baru</h3>
        <button @click="open = !open"
                class="btn-primary text-xs tracking-widest uppercase px-4 py-2 rounded-lg">
            + Tambah
        </button>
    </div>
    <div x-show="open" x-cloak>
        @if($errors->any())
            <div class="bg-red-50 border border-red-200 rounded-lg p-3 mb-4">
                @foreach($errors->all() as $error)
                    <p class="text-red-600 text-xs">• {{ $error }}</p>
                @endforeach
            </div>
        @endif
        <form method="POST" action="{{ route('admin.karyawan.store') }}">
            @csrf
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-4">
                <div>
                    <label class="block text-gray-500 text-xs tracking-widest uppercase mb-1">Nama *</label>
                    <input type="text" name="name" value="{{ old('name') }}"
                           placeholder="Nama lengkap"
                           class="input-dark w-full px-3 py-2 rounded-lg text-sm">
                </div>
                <div>
                    <label class="block text-gray-500 text-xs tracking-widest uppercase mb-1">Email *</label>
                    <input type="email" name="email" value="{{ old('email') }}"
                           placeholder="email@contoh.com"
                           class="input-dark w-full px-3 py-2 rounded-lg text-sm">
                </div>
                <div>
                    <label class="block text-gray-500 text-xs tracking-widest uppercase mb-1">No. HP *</label>
                    <input type="tel" name="phone" value="{{ old('phone') }}"
                           placeholder="08xxxxxxxxxx"
                           class="input-dark w-full px-3 py-2 rounded-lg text-sm">
                </div>
                <div>
                    <label class="block text-gray-500 text-xs tracking-widest uppercase mb-1">Spesialisasi *</label>
                    <select name="specialization" class="input-dark w-full px-3 py-2 rounded-lg text-sm">
                        <option value="">-- Pilih --</option>
                        <option value="detailing" {{ old('specialization') === 'detailing' ? 'selected' : '' }}>Detailing</option>
                        <option value="carwash"   {{ old('specialization') === 'carwash'   ? 'selected' : '' }}>Carwash</option>
                        <option value="keduanya"  {{ old('specialization') === 'keduanya'  ? 'selected' : '' }}>Detailing & Carwash</option>
                    </select>
                </div>
                <div>
                    <label class="block text-gray-500 text-xs tracking-widest uppercase mb-1">Password *</label>
                    <input type="password" name="password"
                           placeholder="Minimal 6 karakter"
                           class="input-dark w-full px-3 py-2 rounded-lg text-sm">
                </div>
            </div>
            <button type="submit" class="btn-primary text-xs tracking-widest uppercase px-6 py-2 rounded-lg">
                Simpan Karyawan
            </button>
        </form>
    </div>
</div>

{{-- Tabel Karyawan --}}
<div class="bg-white border border-gray-200 rounded-xl overflow-hidden shadow-sm">
    <div class="px-5 py-4 border-b border-gray-100">
        <h3 class="text-gray-900 font-semibold text-sm tracking-wide">
            Daftar Karyawan
            <span class="text-gray-400 font-normal ml-2">({{ $karyawanList->total() }} karyawan)</span>
        </h3>
    </div>
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead>
                <tr class="border-b border-gray-100 bg-gray-50">
                    <th class="text-left px-5 py-3 text-gray-400 text-xs font-mono uppercase tracking-wider">Karyawan</th>
                    <th class="text-left px-5 py-3 text-gray-400 text-xs font-mono uppercase tracking-wider">Kontak</th>
                    <th class="text-left px-5 py-3 text-gray-400 text-xs font-mono uppercase tracking-wider">Spesialisasi</th>
                    <th class="text-left px-5 py-3 text-gray-400 text-xs font-mono uppercase tracking-wider">Status</th>
                    <th class="text-left px-5 py-3 text-gray-400 text-xs font-mono uppercase tracking-wider">Rating</th>
                    <th class="text-left px-5 py-3 text-gray-400 text-xs font-mono uppercase tracking-wider">Total Pesanan</th>
                    <th class="text-left px-5 py-3 text-gray-400 text-xs font-mono uppercase tracking-wider">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($karyawanList as $k)
                    <tr class="hover:bg-gray-50 transition-colors">
                        <td class="px-5 py-4">
                            <div class="flex items-center gap-3">
                                <div class="w-9 h-9 rounded-full bg-gray-100 border border-gray-200 flex items-center justify-center text-gray-600 text-sm font-bold shrink-0">
                                    {{ strtoupper(substr($k->name, 0, 1)) }}
                                </div>
                                <div>
                                    <div class="text-gray-900 font-medium">{{ $k->name }}</div>
                                    <div class="text-gray-400 text-xs">ID #{{ $k->id }}</div>
                                </div>
                            </div>
                        </td>
                        <td class="px-5 py-4">
                            <div class="text-gray-700 text-xs">{{ $k->email }}</div>
                            <div class="text-gray-400 text-xs mt-0.5">{{ $k->phone }}</div>
                        </td>
                        <td class="px-5 py-4">
                            <span class="text-xs border border-gray-200 text-gray-600 bg-gray-50 px-2 py-1 rounded-full">
                                {{ $k->specializationLabel() }}
                            </span>
                        </td>
                        <td class="px-5 py-4">
                            @if($k->is_available)
                                <span class="text-xs bg-green-100 text-green-700 px-2 py-1 rounded-full">Tersedia</span>
                            @else
                                <span class="text-xs bg-red-100 text-red-700 px-2 py-1 rounded-full">Tidak Tersedia</span>
                            @endif
                        </td>
                        <td class="px-5 py-4">
                            @if($k->rating_count > 0)
                                <div class="text-yellow-500 text-sm">
                                    @for($i = 1; $i <= 5; $i++)
                                        {{ $i <= round($k->rating_avg) ? '★' : '☆' }}
                                    @endfor
                                </div>
                                <div class="text-gray-400 text-xs mt-0.5">
                                    {{ number_format($k->rating_avg, 1) }} ({{ $k->rating_count }} ulasan)
                                </div>
                            @else
                                <span class="text-gray-300 text-xs">Belum ada rating</span>
                            @endif
                        </td>
                        <td class="px-5 py-4 text-center">
                            <span class="text-gray-900 font-bold">{{ $k->assigned_reservations_count }}</span>
                            <span class="text-gray-400 text-xs ml-1">pesanan</span>
                        </td>
                        <td class="px-5 py-4">
                            <form method="POST" action="{{ route('admin.karyawan.destroy', $k) }}"
                                  onsubmit="return confirm('Yakin hapus karyawan {{ $k->name }}?')">
                                @csrf @method('DELETE')
                                <button type="submit"
                                        class="text-xs border border-red-200 text-red-500 hover:bg-red-50 px-3 py-1.5 rounded-lg transition-all">
                                    Hapus
                                </button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="px-6 py-16 text-center text-gray-400">
                            <div class="text-3xl mb-2">👷</div>
                            Belum ada karyawan terdaftar
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($karyawanList->hasPages())
        <div class="px-5 py-4 border-t border-gray-100">
            {{ $karyawanList->links() }}
        </div>
    @endif
</div>

@endsection