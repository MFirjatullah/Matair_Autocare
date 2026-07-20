@extends('admin.layout')
@section('title', 'Kelola Reservasi')
@section('page-title', 'KELOLA RESERVASI')

@section('content')

{{-- Filter Bar --}}
<form method="GET" action="{{ route('admin.reservations') }}"
      class="bg-white border border-gray-200 rounded-xl p-5 mb-6 shadow-sm flex flex-wrap gap-3 items-end">
    <div class="flex-1 min-w-40">
        <label class="block text-gray-500 text-xs font-mono uppercase mb-2">Cari</label>
        <input type="text" name="search" value="{{ request('search') }}"
               placeholder="Nama, plat, atau HP..."
               class="input-dark w-full px-4 py-2 rounded-lg text-sm">
    </div>
    <div class="min-w-36">
        <label class="block text-gray-500 text-xs font-mono uppercase mb-2">Status</label>
        <select name="status" class="input-dark w-full px-4 py-2 rounded-lg text-sm">
            <option value="">Semua Status</option>
            <option value="pending"     {{ request('status') === 'pending'     ? 'selected' : '' }}>Menunggu</option>
            <option value="confirmed"   {{ request('status') === 'confirmed'   ? 'selected' : '' }}>Dikonfirmasi</option>
            <option value="in_progress" {{ request('status') === 'in_progress' ? 'selected' : '' }}>Diproses</option>
            <option value="completed"   {{ request('status') === 'completed'   ? 'selected' : '' }}>Selesai</option>
            <option value="cancelled"   {{ request('status') === 'cancelled'   ? 'selected' : '' }}>Dibatalkan</option>
        </select>
    </div>
    <div class="min-w-40">
        <label class="block text-gray-500 text-xs font-mono uppercase mb-2">Tanggal</label>
        <input type="date" name="date" value="{{ request('date') }}"
               class="input-dark w-full px-4 py-2 rounded-lg text-sm">
    </div>
    <button type="submit" class="btn-primary text-xs tracking-widest uppercase px-5 py-2 rounded-lg">Filter</button>
    @if(request()->hasAny(['search','status','date']))
        <a href="{{ route('admin.reservations') }}"
           class="border border-gray-200 text-gray-500 hover:text-gray-900 hover:border-gray-400 px-5 py-2 rounded-lg text-xs transition-all">
            Reset
        </a>
    @endif
</form>

{{-- Table --}}
<div class="bg-white border border-gray-200 rounded-xl overflow-hidden shadow-sm">
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead>
                <tr class="border-b border-gray-100 bg-gray-50">
                    <th class="text-left px-5 py-3 text-gray-400 text-xs font-mono uppercase tracking-wider">No.</th>
                    <th class="text-left px-5 py-3 text-gray-400 text-xs font-mono uppercase tracking-wider">Pelanggan</th>
                    <th class="text-left px-5 py-3 text-gray-400 text-xs font-mono uppercase tracking-wider">Kendaraan</th>
                    <th class="text-left px-5 py-3 text-gray-400 text-xs font-mono uppercase tracking-wider">Layanan</th>
                    <th class="text-left px-5 py-3 text-gray-400 text-xs font-mono uppercase tracking-wider">Jadwal</th>
                    <th class="text-left px-5 py-3 text-gray-400 text-xs font-mono uppercase tracking-wider">Karyawan</th>
                    <th class="text-left px-5 py-3 text-gray-400 text-xs font-mono uppercase tracking-wider">Total</th>
                    <th class="text-left px-5 py-3 text-gray-400 text-xs font-mono uppercase tracking-wider">Status</th>
                    <th class="text-left px-5 py-3 text-gray-400 text-xs font-mono uppercase tracking-wider">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($reservations as $res)
                    <tr class="hover:bg-gray-50 transition-colors" x-data="{ openStatus: false, openAssign: false }">
                        <td class="px-5 py-4 font-mono text-gray-400 text-xs">
                            #{{ str_pad($res->id, 4, '0', STR_PAD_LEFT) }}
                        </td>
                        <td class="px-5 py-4">
                            <div class="text-gray-900 font-medium">{{ $res->customer_name }}</div>
                            <div class="text-gray-400 text-xs">{{ $res->customer_phone }}</div>
                        </td>
                        <td class="px-5 py-4">
                            <div class="text-gray-700">{{ $res->car_brand }}</div>
                            <div class="font-mono text-gray-400 text-xs">{{ $res->plate_number }}</div>
                        </td>
                        <td class="px-5 py-4">
                            <div class="text-gray-700">{{ $res->service->name }}</div>
                            <div class="text-gray-400 text-xs">{{ $res->service->size ?? 'Carwash' }}</div>
                        </td>
                        <td class="px-5 py-4 text-xs">
                            <div class="text-gray-600">{{ $res->reservation_date->format('d M Y') }}</div>
                            <div class="text-gray-400">{{ $res->reservation_time }} WIB</div>
                        </td>
                        <td class="px-5 py-4">
                            @if($res->karyawan)
                                <div class="text-gray-700 text-xs font-medium">{{ $res->karyawan->name }}</div>
                                @php
                                    $asc = ['waiting'=>'bg-yellow-100 text-yellow-700','accepted'=>'bg-green-100 text-green-700','rejected'=>'bg-red-100 text-red-700','reassigned'=>'bg-blue-100 text-blue-700'];
                                    $asl = ['waiting'=>'Menunggu','accepted'=>'Diterima','rejected'=>'Ditolak','reassigned'=>'Dialihkan'];
                                @endphp
                                <span class="text-xs px-2 py-0.5 rounded-full {{ $asc[$res->assign_status] ?? '' }}">
                                    {{ $asl[$res->assign_status] ?? $res->assign_status }}
                                </span>
                                @if($res->reject_reason)
                                    <div class="text-red-500 text-xs mt-0.5">{{ Str::limit($res->reject_reason, 30) }}</div>
                                @endif
                            @else
                                <span class="text-gray-300 text-xs">Belum ditugaskan</span>
                            @endif
                        </td>
                        <td class="px-5 py-4">
                            @if($res->use_free_wash)
                                <span class="text-green-600 text-xs font-bold">GRATIS 🎁</span>
                            @else
                                <span class="text-gray-900 font-mono text-xs">Rp {{ number_format($res->total_price, 0, ',', '.') }}</span>
                            @endif
                        </td>
                        <td class="px-5 py-4">
                            @php
                                $sc = ['pending'=>'bg-yellow-100 text-yellow-700','confirmed'=>'bg-blue-100 text-blue-700','in_progress'=>'bg-orange-100 text-orange-700','completed'=>'bg-green-100 text-green-700','cancelled'=>'bg-red-100 text-red-700'];
                                $sl = ['pending'=>'Menunggu','confirmed'=>'Dikonfirmasi','in_progress'=>'Diproses','completed'=>'Selesai','cancelled'=>'Dibatalkan'];
                            @endphp
                            <span class="text-xs px-2 py-1 rounded-full {{ $sc[$res->status] ?? '' }}">
                                {{ $sl[$res->status] ?? $res->status }}
                            </span>
                        </td>
                        <td class="px-5 py-4">
                            <div class="flex flex-col gap-1.5">
                                {{-- Update Status --}}
                                <button @click="openStatus = !openStatus"
                                        class="text-xs bg-gray-100 hover:bg-gray-200 text-gray-600 hover:text-gray-900 px-3 py-1.5 rounded-lg transition-all">
                                    Status
                                </button>
                                {{-- Assign Karyawan --}}
                                <button @click="openAssign = !openAssign"
                                        class="text-xs bg-gray-100 hover:bg-gray-200 text-gray-600 hover:text-gray-900 px-3 py-1.5 rounded-lg transition-all">
                                    {{ $res->karyawan ? 'Reassign' : 'Assign' }}
                                </button>
                            </div>

                            {{-- Form Status --}}
                            <div x-show="openStatus" x-cloak
                                 class="mt-2 bg-white border border-gray-200 rounded-lg p-3 w-48 shadow-md">
                                <form method="POST" action="{{ route('admin.reservations.update', $res) }}">
                                    @csrf @method('PATCH')
                                    <select name="status" class="input-dark w-full px-3 py-1.5 rounded-lg text-xs mb-2">
                                        <option value="pending"     {{ $res->status==='pending'     ? 'selected':'' }}>Menunggu</option>
                                        <option value="confirmed"   {{ $res->status==='confirmed'   ? 'selected':'' }}>Dikonfirmasi</option>
                                        <option value="in_progress" {{ $res->status==='in_progress' ? 'selected':'' }}>Diproses</option>
                                        <option value="completed"   {{ $res->status==='completed'   ? 'selected':'' }}>Selesai</option>
                                        <option value="cancelled"   {{ $res->status==='cancelled'   ? 'selected':'' }}>Dibatalkan</option>
                                    </select>
                                    <button type="submit"
                                            class="w-full btn-primary py-1.5 rounded-lg text-xs tracking-widest uppercase">
                                        Simpan
                                    </button>
                                </form>
                            </div>

                            {{-- Form Assign/Reassign --}}
                            <div x-show="openAssign" x-cloak
                                 class="mt-2 bg-white border border-gray-200 rounded-lg p-3 w-48 shadow-md">
                                <form method="POST"
                                      action="{{ $res->karyawan
                                          ? route('admin.reservations.reassign', $res)
                                          : route('admin.reservations.assign', $res) }}">
                                    @csrf
                                    <select name="karyawan_id" class="input-dark w-full px-3 py-1.5 rounded-lg text-xs mb-2">
                                        <option value="">-- Pilih Karyawan --</option>
                                        @foreach($karyawanList as $k)
                                            <option value="{{ $k->id }}"
                                                    {{ $res->karyawan_id == $k->id ? 'selected' : '' }}>
                                                {{ $k->name }} ({{ $k->specializationLabel() }})
                                                @if($k->rating_count > 0) ★{{ number_format($k->rating_avg,1) }} @endif
                                            </option>
                                        @endforeach
                                    </select>
                                    <button type="submit"
                                            class="w-full btn-primary py-1.5 rounded-lg text-xs tracking-widest uppercase">
                                        {{ $res->karyawan ? 'Alihkan' : 'Tugaskan' }}
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @if($res->notes)
                        <tr class="bg-gray-50">
                            <td colspan="9" class="px-5 py-2 text-gray-400 text-xs italic">
                                📝 {{ $res->notes }}
                            </td>
                        </tr>
                    @endif
                @empty
                    <tr>
                        <td colspan="9" class="px-6 py-16 text-center text-gray-400">
                            <div class="text-3xl mb-2">📭</div>
                            Tidak ada reservasi ditemukan
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($reservations->hasPages())
        <div class="px-6 py-4 border-t border-gray-100">
            {{ $reservations->withQueryString()->links() }}
        </div>
    @endif
</div>

@endsection