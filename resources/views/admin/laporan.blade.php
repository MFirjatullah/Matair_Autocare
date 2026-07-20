@extends('admin.layout')
@section('title', 'Laporan Transaksi')
@section('page-title', 'LAPORAN TRANSAKSI')

@section('content')

<form method="GET" action="{{ route('admin.laporan') }}"
      class="bg-white border border-gray-200 rounded p-5 mb-6 flex flex-wrap gap-3 items-end">
    <div class="min-w-40">
        <label class="block text-gray-500 text-xs font-mono uppercase mb-2">Dari Tanggal</label>
        <input type="date" name="start_date" value="{{ request('start_date') }}" class="input-dark w-full px-4 py-2 rounded text-sm">
    </div>
    <div class="min-w-40">
        <label class="block text-gray-500 text-xs font-mono uppercase mb-2">Sampai Tanggal</label>
        <input type="date" name="end_date" value="{{ request('end_date') }}" class="input-dark w-full px-4 py-2 rounded text-sm">
    </div>
    <div class="min-w-36">
        <label class="block text-gray-500 text-xs font-mono uppercase mb-2">Kategori</label>
        <select name="category" class="input-dark w-full px-4 py-2 rounded text-sm">
            <option value="">Semua</option>
            <option value="detailing" {{ request('category')==='detailing'?'selected':'' }}>Detailing</option>
            <option value="carwash"   {{ request('category')==='carwash'  ?'selected':'' }}>Carwash</option>
        </select>
    </div>
    <button type="submit" class="btn-primary text-xs tracking-widest uppercase px-5 py-2 rounded">Filter</button>
    @if(request()->hasAny(['start_date','end_date','category']))
        <a href="{{ route('admin.laporan') }}" class="border border-gray-300 text-gray-500 hover:text-gray-900 px-5 py-2 rounded text-xs transition-all">Reset</a>
    @endif
    <a href="{{ route('admin.laporan.export', request()->query()) }}"
       class="ml-auto border border-gray-300 hover:border-gray-500 text-gray-600 hover:text-gray-900 px-5 py-2 rounded text-xs tracking-widest uppercase transition-all flex items-center gap-2">
        📄 Export PDF
    </a>
</form>

<div class="grid grid-cols-3 gap-4 mb-6">
    <div class="bg-white border border-gray-200 rounded p-5">
        <div class="text-gray-400 text-xs tracking-widest uppercase mb-1">Total Transaksi</div>
        <div class="text-gray-900 text-2xl font-bold">{{ $totalCount }}</div>
    </div>
    <div class="bg-white border border-gray-200 rounded p-5">
        <div class="text-gray-400 text-xs tracking-widest uppercase mb-1">Total Pendapatan</div>
        <div class="text-gray-900 text-2xl font-bold font-mono">Rp {{ number_format($totalRevenue, 0, ',', '.') }}</div>
    </div>
    <div class="bg-white border border-gray-200 rounded p-5">
        <div class="text-gray-400 text-xs tracking-widest uppercase mb-1">Rata-rata per Transaksi</div>
        <div class="text-gray-900 text-2xl font-bold font-mono">Rp {{ $totalCount > 0 ? number_format($totalRevenue/$totalCount,0,',','.') : 0 }}</div>
    </div>
</div>

<div class="bg-white border border-gray-200 rounded overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead>
                <tr class="border-b border-gray-100 bg-gray-50">
                    <th class="text-left px-5 py-3 text-gray-400 text-xs font-mono uppercase">No.</th>
                    <th class="text-left px-5 py-3 text-gray-400 text-xs font-mono uppercase">Pelanggan</th>
                    <th class="text-left px-5 py-3 text-gray-400 text-xs font-mono uppercase">Kendaraan</th>
                    <th class="text-left px-5 py-3 text-gray-400 text-xs font-mono uppercase">Layanan</th>
                    <th class="text-left px-5 py-3 text-gray-400 text-xs font-mono uppercase">Karyawan</th>
                    <th class="text-left px-5 py-3 text-gray-400 text-xs font-mono uppercase">Tanggal</th>
                    <th class="text-left px-5 py-3 text-gray-400 text-xs font-mono uppercase">Total</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($reservations as $res)
                    <tr class="hover:bg-gray-50 transition-colors">
                        <td class="px-5 py-3 font-mono text-gray-400 text-xs">#{{ str_pad($res->id, 4, '0', STR_PAD_LEFT) }}</td>
                        <td class="px-5 py-3">
                            <div class="text-gray-900 text-sm">{{ $res->customer_name }}</div>
                            <div class="text-gray-400 text-xs">{{ $res->customer_phone }}</div>
                        </td>
                        <td class="px-5 py-3">
                            <div class="text-gray-700 text-sm">{{ $res->car_brand }}</div>
                            <div class="text-gray-400 text-xs font-mono">{{ $res->plate_number }}</div>
                        </td>
                        <td class="px-5 py-3">
                            <div class="text-gray-700 text-sm">{{ $res->service->name }}</div>
                            <div class="text-gray-400 text-xs">{{ ucfirst($res->service->category) }}</div>
                        </td>
                        <td class="px-5 py-3 text-gray-600 text-sm">
                            {{ $res->karyawan ? $res->karyawan->name : '-' }}
                        </td>
                        <td class="px-5 py-3 text-gray-500 text-xs">{{ $res->reservation_date->format('d M Y') }}</td>
                        <td class="px-5 py-3">
                            @if($res->use_free_wash)
                                <span class="text-green-600 text-xs font-bold">GRATIS 🎁</span>
                            @else
                                <span class="text-gray-900 font-mono text-sm">Rp {{ number_format($res->total_price, 0, ',', '.') }}</span>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="px-6 py-16 text-center text-gray-400">Tidak ada data transaksi</td>
                    </tr>
                @endforelse
            </tbody>
            @if($totalCount > 0)
                <tfoot>
                    <tr class="border-t-2 border-gray-200 bg-gray-50">
                        <td colspan="6" class="px-5 py-3 text-gray-600 text-xs tracking-widest uppercase text-right font-semibold">Total Pendapatan</td>
                        <td class="px-5 py-3 text-gray-900 font-mono font-bold">Rp {{ number_format($totalRevenue, 0, ',', '.') }}</td>
                    </tr>
                </tfoot>
            @endif
        </table>
    </div>
</div>

@endsection