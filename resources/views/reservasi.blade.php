@extends('layouts.app')
@section('title', 'Reservasi — MATAIR Auto Care')

@section('content')

<div class="max-w-3xl mx-auto px-6 py-16">

   
    <div class="text-center mb-12">
        <p class="text-gray-400 text-xs tracking-widest uppercase mb-3">Booking Online</p>
        <h1 class="font-display text-gray-900 text-3xl md:text-4xl tracking-widest">BUAT RESERVASI</h1>
        <div class="w-12 h-px bg-gray-300 mx-auto mt-4"></div>
    </div>

    
    @auth
        @php $user = auth()->user(); @endphp
        <div class="bg-white border border-gray-200 rounded-xl p-5 mb-8 shadow-sm">
            <div class="flex flex-wrap items-center justify-between gap-4">
                <div>
                    <div class="text-gray-900 text-sm font-semibold tracking-wide mb-1">Halo, {{ $user->name }}</div>
                    <div class="text-gray-400 text-xs tracking-wide">
                        Total cuci: <span class="text-gray-700 font-semibold">{{ $user->wash_count }}</span> kali •
                        @if($user->freeWashAvailable() > 0)
                            <span class="text-green-600 font-semibold">{{ $user->freeWashAvailable() }} cuci gratis tersedia</span>
                        @else
                            Cuci <span class="text-gray-700 font-semibold">{{ $user->washesUntilNextFree() }}</span> kali lagi untuk gratis
                        @endif
                    </div>
                </div>
                <div class="flex items-center gap-1.5">
                    @for($i = 1; $i <= 10; $i++)
                        <div class="w-7 h-7 rounded border flex items-center justify-center text-xs font-mono
                            {{ $i <= ($user->wash_count % 10 ?: ($user->wash_count > 0 ? 10 : 0))
                                ? 'border-gray-700 text-gray-900 bg-gray-100'
                                : 'border-gray-200 text-gray-300' }}">
                            {{ $i <= ($user->wash_count % 10 ?: ($user->wash_count > 0 ? 10 : 0)) ? '✓' : $i }}
                        </div>
                    @endfor
                    <span class="text-gray-400 text-lg ml-1">🎁</span>
                </div>
            </div>
        </div>
    @endauth

    
    @if($errors->any())
        <div class="border border-red-200 bg-red-50 rounded-lg p-4 mb-6">
            @foreach($errors->all() as $error)
                <p class="text-red-600 text-xs tracking-wide">• {{ $error }}</p>
            @endforeach
        </div>
    @endif

    {{-- Form --}}
    <form action="{{ route('reservasi.store') }}" method="POST"
        class="bg-white border border-gray-200 rounded-xl p-8 shadow-sm"
        x-data="reservasiForm()">
        @csrf

        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">

            <div>
                <label class="block text-gray-500 text-xs tracking-widest uppercase mb-2">Nama Lengkap *</label>
                <input type="text" name="customer_name"
                       value="{{ old('customer_name', auth()->user()->name ?? '') }}"
                       placeholder="Nama lengkap"
                       class="input-dark w-full px-4 py-3 rounded-lg text-sm">
            </div>

            <div>
                <label class="block text-gray-500 text-xs tracking-widest uppercase mb-2">Nomor HP *</label>
                <input type="tel" name="customer_phone"
                       value="{{ old('customer_phone', auth()->user()->phone ?? '') }}"
                       placeholder="08xxxxxxxxxx"
                       class="input-dark w-full px-4 py-3 rounded-lg text-sm">
            </div>

            <div class="md:col-span-2">
                <label class="block text-gray-500 text-xs tracking-widest uppercase mb-2">Email *</label>
                <input type="email" name="customer_email"
                       value="{{ old('customer_email', auth()->user()->email ?? '') }}"
                       placeholder="email@contoh.com"
                       class="input-dark w-full px-4 py-3 rounded-lg text-sm">
            </div>

            <div>
                <label class="block text-gray-500 text-xs tracking-widest uppercase mb-2">Jenis Kendaraan *</label>
                <select name="vehicle_type" class="input-dark w-full px-4 py-3 rounded-lg text-sm">
                    <option value="">-- Pilih Jenis --</option>
                    @foreach(['Sedan','SUV','MPV','Hatchback','Pickup/Truck','Minibus','Sports Car','Lainnya'] as $type)
                        <option value="{{ $type }}" {{ old('vehicle_type') === $type ? 'selected' : '' }}>{{ $type }}</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block text-gray-500 text-xs tracking-widest uppercase mb-2">Merek Mobil *</label>
                <input type="text" name="car_brand"
                       value="{{ old('car_brand') }}"
                       placeholder="Toyota, Honda, BMW..."
                       class="input-dark w-full px-4 py-3 rounded-lg text-sm">
            </div>

            <div>
                <label class="block text-gray-500 text-xs tracking-widest uppercase mb-2">Plat Nomor *</label>
                <input type="text" name="plate_number"
                       value="{{ old('plate_number') }}"
                       placeholder="BK 1234 AB"
                       class="input-dark w-full px-4 py-3 rounded-lg text-sm uppercase"
                       style="text-transform:uppercase;">
            </div>

    
            <div>
                <label class="block text-gray-500 text-xs tracking-widest uppercase mb-2">Jenis Layanan *</label>
                <select name="service_id"
                        id="service_select"
                        @change="checkService()"
                        class="input-dark w-full px-4 py-3 rounded-lg text-sm">
                    <option value="">-- Pilih Layanan --</option>
                    @foreach(['detailing' => 'Detailing', 'carwash' => 'Carwash'] as $cat => $label)
                        <optgroup label="── {{ $label }} ──">
                            @foreach($services[$cat] ?? [] as $service)
                                <option value="{{ $service->id }}" {{ old('service_id') == $service->id ? 'selected' : '' }}>
                                    {{ $service->name }}
                                    @if($service->size) ({{ $service->size }}) @endif
                                    — Rp {{ number_format($service->price, 0, ',', '.') }}
                                </option>
                            @endforeach
                        </optgroup>
                    @endforeach
                </select>
            </div>

         
            <div>
                <label class="block text-gray-500 text-xs tracking-widest uppercase mb-2">Tanggal *</label>
                <input type="date" name="reservation_date"
                    id="reservation_date"
                    value="{{ old('reservation_date') }}"
                    min="{{ date('Y-m-d') }}"
                    class="input-dark w-full px-4 py-3 rounded-lg text-sm"
                    x-model="selectedDate"
                    @change="checkSlots()">
            </div>

        
            <div>
                <label class="block text-gray-500 text-xs tracking-widest uppercase mb-2">Waktu *</label>
                <select name="reservation_time" id="reservation_time"
                        class="input-dark w-full px-4 py-3 rounded-lg text-sm">
                    <option value="">-- Pilih Tanggal Dulu --</option>
                </select>
                <p class="text-gray-400 text-xs mt-1" x-show="loadingSlots">Mengecek ketersediaan slot...</p>
            </div>

            {{-- Catatan --}}
            <div class="md:col-span-2">
                <label class="block text-gray-500 text-xs tracking-widest uppercase mb-2">Catatan (Opsional)</label>
                <textarea name="notes" rows="3"
                          placeholder="Informasi tambahan tentang kendaraan Anda..."
                          class="input-dark w-full px-4 py-3 rounded-lg text-sm resize-none">{{ old('notes') }}</textarea>
            </div>

            
            @auth
                @if(auth()->user()->freeWashAvailable() > 0)
                    <div class="md:col-span-2" x-show="isRegularWash">
                        <label class="flex items-center gap-3 cursor-pointer border border-gray-200 rounded-lg px-5 py-4 hover:border-gray-400 hover:bg-gray-50 transition-colors">
                            <input type="checkbox" name="use_free_wash" value="1"
                                   class="w-4 h-4 accent-gray-900"
                                   {{ old('use_free_wash') ? 'checked' : '' }}>
                            <div>
                                <div class="text-gray-900 text-sm font-semibold tracking-wide">🎁 Gunakan Cuci Gratis</div>
                                <div class="text-gray-400 text-xs tracking-wide mt-0.5">
                                    Anda memiliki {{ auth()->user()->freeWashAvailable() }} cuci gratis tersisa
                                    — Hanya berlaku untuk Regular Wash
                                </div>
                            </div>
                        </label>
                    </div>
                @endif
            @endauth

        </div>

        <div class="mt-8 pt-6 border-t border-gray-100 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
            <button type="submit"
                    class="btn-primary w-full sm:w-auto text-xs tracking-widest uppercase px-10 py-3.5 rounded-lg">
                Konfirmasi Reservasi
            </button>
        </div>
    </form>

</div>

@endsection

@push('scripts')
<script>
function reservasiForm() {
    return {
        selectedDate: '{{ old("reservation_date", "") }}',
        loadingSlots: false,
        slots: [],
        isRegularWash: false,

        checkService() {
            const select = document.getElementById('service_select');
            if (!select) return;
            const selectedOption = select.options[select.selectedIndex];
            const serviceName = selectedOption ? selectedOption.text.toLowerCase() : '';
            this.isRegularWash = serviceName.includes('regular wash');
        },

        async checkSlots() {
            if (!this.selectedDate) return;

            this.loadingSlots = true;
            const select = document.getElementById('reservation_time');
            select.innerHTML = '<option value="">Memuat slot...</option>';

            try {
                const response = await fetch(`{{ url('/check-slots') }}?date=${this.selectedDate}`);
                const slots    = await response.json();
                this.slots     = slots;

                select.innerHTML = '<option value="">-- Pilih Waktu --</option>';

                slots.forEach(slot => {
                    const option    = document.createElement('option');
                    option.value    = slot.time;
                    option.disabled = slot.is_full;

                    if (slot.is_full) {
                        option.text        = `${slot.time} WIB — PENUH`;
                        option.style.color = '#9ca3af';
                    } else {
                        option.text = `${slot.time} WIB — Sisa ${slot.available} slot`;
                    }

                    if (slot.time === '{{ old("reservation_time", "") }}') {
                        option.selected = true;
                    }

                    select.appendChild(option);
                });

            } catch (e) {
                select.innerHTML = '<option value="">Gagal memuat slot</option>';
            }

            this.loadingSlots = false;
        },

        init() {
            if (this.selectedDate) {
                this.checkSlots();
            }
            this.checkService();
        }
    }
}
</script>
@endpush