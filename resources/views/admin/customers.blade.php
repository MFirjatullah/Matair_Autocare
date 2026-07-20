@extends('admin.layout')
@section('title', 'Pelanggan')
@section('page-title', 'DATA PELANGGAN')

@section('content')

<div class="bg-white border border-gray-200 rounded overflow-hidden">
    <div class="px-6 py-4 border-b border-gray-100">
        <h3 class="text-gray-600 text-sm">Total: <span class="text-gray-900 font-bold">{{ $customers->total() }}</span> pelanggan terdaftar</h3>
    </div>
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead>
                <tr class="border-b border-gray-100 bg-gray-50">
                    <th class="text-left px-5 py-3 text-gray-400 text-xs font-mono uppercase">Pelanggan</th>
                    <th class="text-left px-5 py-3 text-gray-400 text-xs font-mono uppercase">Kontak</th>
                    <th class="text-left px-5 py-3 text-gray-400 text-xs font-mono uppercase">Total Reservasi</th>
                    <th class="text-left px-5 py-3 text-gray-400 text-xs font-mono uppercase">Cuci Count</th>
                    <th class="text-left px-5 py-3 text-gray-400 text-xs font-mono uppercase">Loyalty</th>
                    <th class="text-left px-5 py-3 text-gray-400 text-xs font-mono uppercase">Bergabung</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($customers as $customer)
                    <tr class="hover:bg-gray-50 transition-colors">
                        <td class="px-5 py-4">
                            <div class="flex items-center gap-3">
                                <div class="w-9 h-9 rounded-full bg-gray-200 border border-gray-300 flex items-center justify-center text-gray-600 text-sm font-bold shrink-0">
                                    {{ strtoupper(substr($customer->name, 0, 1)) }}
                                </div>
                                <div>
                                    <div class="text-gray-900 font-medium">{{ $customer->name }}</div>
                                    <div class="text-gray-400 text-xs">ID #{{ $customer->id }}</div>
                                </div>
                            </div>
                        </td>
                        <td class="px-5 py-4">
                            <div class="text-gray-600 text-xs">{{ $customer->email }}</div>
                            <div class="text-gray-400 text-xs mt-0.5">{{ $customer->phone ?? '-' }}</div>
                        </td>
                        <td class="px-5 py-4 text-center">
                            <span class="font-bold text-gray-900">{{ $customer->reservations_count }}</span>
                            <span class="text-gray-400 text-xs ml-1">kali</span>
                        </td>
                        <td class="px-5 py-4 text-center">
                            <span class="font-bold text-gray-700 font-mono">{{ $customer->wash_count }}</span>
                        </td>
                        <td class="px-5 py-4">
                            @php $free = $customer->freeWashAvailable(); @endphp
                            <div class="flex items-center gap-2">
                                <div class="h-1.5 w-20 bg-gray-200 rounded-full overflow-hidden">
                                    <div class="h-full bg-gray-700 rounded-full" style="width: {{ $customer->loyaltyProgress() }}%"></div>
                                </div>
                                <span class="text-xs text-gray-400">{{ $customer->wash_count % 10 }}/10</span>
                                @if($free > 0)
                                    <span class="text-xs bg-green-100 text-green-700 px-2 py-0.5 rounded-full">🎁 {{ $free }}x</span>
                                @endif
                            </div>
                        </td>
                        <td class="px-5 py-4 text-gray-400 text-xs">{{ $customer->created_at->format('d M Y') }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="px-6 py-16 text-center text-gray-400">
                            <div class="text-3xl mb-2">👥</div>
                            Belum ada pelanggan terdaftar
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($customers->hasPages())
        <div class="px-6 py-4 border-t border-gray-100">{{ $customers->links() }}</div>
    @endif
</div>

@endsection