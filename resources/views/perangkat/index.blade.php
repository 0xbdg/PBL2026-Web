<x-layout>
    <x-slot:title>Sensor & Perangkat IoT</x-slot:title>

    <div x-data="{ showModal: false, openDeleteModal: false }">
        <div class="flex flex-wrap items-end justify-between gap-3">
            <div>
                <h1 class="text-2xl font-bold text-slate-800">Sensor &amp; Perangkat IoT</h1>
                <p class="text-sm text-slate-500">Status perangkat keras pemantau psikofisiologis wisatawan.</p>
            </div>

            <button type="button" @click="showModal = true" class="inline-flex items-center gap-2 rounded-xl bg-emerald-600 px-4 py-2.5 text-sm font-semibold text-white shadow-sm hover:bg-emerald-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-emerald-600 transition-colors">
                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M12 4.5v15m7.5-7.5h-15"/>
                </svg>
                Tambah Perangkat
            </button>
        </div>
        <div
            x-show="showModal"
            class="fixed inset-0 z-50 flex items-center justify-center bg-slate-900/50 backdrop-blur-sm p-4"
            x-transition.opacity
            x-cloak
        >
            <div
                @click.away="showModal = false"
                class="w-full max-w-md rounded-3xl bg-white p-6 shadow-xl ring-1 ring-slate-200"
                x-transition
            >
                <div class="flex items-center justify-between border-b border-slate-100 pb-4">
                    <h3 class="text-lg font-bold text-slate-800">Tambah Perangkat Baru</h3>
                    <button type="button" @click="showModal = false" class="text-slate-400 hover:text-slate-600">
                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>

                <form action="{{ route('perangkat.store') }}" method="POST" class="mt-4 space-y-4">
                    @csrf

                    <div>
                        <label for="device_uid" class="block text-sm font-medium text-slate-700">Device UID</label>
                        <input
                            type="text"
                            id="device_uid"
                            name="device_uid"
                            required
                            placeholder="Contoh: ESP32_C3_001"
                            class="mt-1 block w-full rounded-xl border border-slate-300 px-3 py-2 text-sm text-slate-800 shadow-sm focus:border-emerald-500 focus:outline-none focus:ring-1 focus:ring-emerald-500"
                        >
                    </div>

                    <div>
                        <label for="device_name" class="block text-sm font-medium text-slate-700">Device Name</label>
                        <input
                            type="text"
                            id="device_name"
                            name="device_name"
                            required
                            placeholder="Contoh: Perangkat Wisatawan 01"
                            class="mt-1 block w-full rounded-xl border border-slate-300 px-3 py-2 text-sm text-slate-800 shadow-sm focus:border-emerald-500 focus:outline-none focus:ring-1 focus:ring-emerald-500"
                        >
                    </div>

                    <div class="flex justify-end gap-3 pt-4 border-t border-slate-100">
                        <button
                            type="button"
                            @click="showModal = false"
                            class="rounded-xl px-4 py-2 text-sm font-semibold text-slate-600 hover:bg-slate-100 transition-colors"
                        >
                            Batal
                        </button>
                        <button
                            type="submit"
                            class="rounded-xl bg-emerald-600 px-4 py-2 text-sm font-semibold text-white shadow-sm hover:bg-emerald-500 transition-colors"
                        >
                            Simpan Perangkat
                        </button>
                    </div>
                </form>
            </div>
        </div>

        @forelse($perangkat as $device)
        <div class="mt-6 rounded-3xl border-2 border-slate-300 bg-white p-4 sm:p-6 shadow-sm">
            <!-- Header Section Device 1 + Tombol Delete yang memicu Modal Delete -->
            <div class="mb-6 flex items-center justify-between border-b border-slate-100 pb-4">
                <h1 class="text-xl font-bold text-slate-800">{{$device["device_name"]}}</h1>

                <!-- Tombol Hapus memicu openDeleteModal = true -->
                <button type="button" @click="openDeleteModal = true" class="rounded-xl p-2 text-slate-400 hover:bg-rose-50 hover:text-rose-600 transition-colors" title="Hapus Perangkat">
                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                    </svg>
                </button>
            </div>

            <div class="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-4">
                <div class="flex flex-col rounded-2xl bg-white p-6 shadow-sm ring-1 ring-slate-200">
                    <div class="flex items-start justify-between">
                        <div class="flex h-12 w-12 items-center justify-center rounded-2xl bg-emerald-100 text-emerald-600">
                            <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M9 3v2m6-2v2M9 19v2m6-2v2M3 9h2m-2 6h2m14-6h2m-2 6h2M7 5h10a2 2 0 0 1 2 2v10a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V7a2 2 0 0 1 2-2Z"/>
                            </svg>
                        </div>
                        <span class="inline-flex items-center gap-1.5 rounded-full px-2.5 py-1 text-xs font-semibold ring-1 ring-inset {{ $online ? 'bg-emerald-100 text-emerald-700 ring-emerald-200' : 'bg-rose-100 text-rose-700 ring-rose-200' }}">
                            <span class="h-1.5 w-1.5 rounded-full {{ $online ? 'bg-emerald-500' : 'bg-rose-500' }}"></span>
                            {{ $online ? 'Online' : 'Offline' }}
                        </span>
                    </div>

                    <h2 class="mt-4 text-lg font-bold text-slate-800">ESP32-C3 Supermini</h2>
                    <p class="text-sm text-slate-500">Mikrokontroller</p>

                    <dl class="mt-4 space-y-2 border-t border-slate-100 pt-4 text-sm">
                        <div class="flex justify-between">
                            <dt class="text-slate-500">Pembacaan</dt>
                            <dd class="font-semibold text-slate-800">-</dd>
                        </div>
                        <div class="flex justify-between">
                            <dt class="text-slate-500">Antarmuka</dt>
                            <dd class="font-semibold text-slate-800">MQTT</dd>
                        </div>
                    </dl>
                </div>

                <div class="flex flex-col rounded-2xl bg-white p-6 shadow-sm ring-1 ring-slate-200">
                    <div class="flex items-start justify-between">
                        <div class="flex h-12 w-12 items-center justify-center rounded-2xl bg-emerald-100 text-emerald-600">
                            <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M9 3v2m6-2v2M9 19v2m6-2v2M3 9h2m-2 6h2m14-6h2m-2 6h2M7 5h10a2 2 0 0 1 2 2v10a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V7a2 2 0 0 1 2-2Z"/>
                            </svg>
                        </div>
                        <span class="inline-flex items-center gap-1.5 rounded-full px-2.5 py-1 text-xs font-semibold ring-1 ring-inset {{ $online ? 'bg-emerald-100 text-emerald-700 ring-emerald-200' : 'bg-rose-100 text-rose-700 ring-rose-200' }}">
                            <span class="h-1.5 w-1.5 rounded-full {{ $online ? 'bg-emerald-500' : 'bg-rose-500' }}"></span>
                            {{ $online ? 'Online' : 'Offline' }}
                        </span>
                    </div>

                    <h2 class="mt-4 text-lg font-bold text-slate-800">MAX30102</h2>
                    <p class="text-sm text-slate-500">Detak jantung (Heart Rate)</p>

                    <dl class="mt-4 space-y-2 border-t border-slate-100 pt-4 text-sm">
                        <div class="flex justify-between">
                            <dt class="text-slate-500">Pembacaan</dt>
                            <dd class="font-semibold text-slate-800">-</dd>
                        </div>
                        <div class="flex justify-between">
                            <dt class="text-slate-500">Antarmuka</dt>
                            <dd class="font-semibold text-slate-800">I2C</dd>
                        </div>
                    </dl>
                </div>

                <div class="flex flex-col rounded-2xl bg-white p-6 shadow-sm ring-1 ring-slate-200">
                    <div class="flex items-start justify-between">
                        <div class="flex h-12 w-12 items-center justify-center rounded-2xl bg-emerald-100 text-emerald-600">
                            <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M9 3v2m6-2v2M9 19v2m6-2v2M3 9h2m-2 6h2m14-6h2m-2 6h2M7 5h10a2 2 0 0 1 2 2v10a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V7a2 2 0 0 1 2-2Z"/>
                            </svg>
                        </div>
                        <span class="inline-flex items-center gap-1.5 rounded-full px-2.5 py-1 text-xs font-semibold ring-1 ring-inset {{ $online ? 'bg-emerald-100 text-emerald-700 ring-emerald-200' : 'bg-rose-100 text-rose-700 ring-rose-200' }}">
                            <span class="h-1.5 w-1.5 rounded-full {{ $online ? 'bg-emerald-500' : 'bg-rose-500' }}"></span>
                            {{ $online ? 'Online' : 'Offline' }}
                        </span>
                    </div>

                    <h2 class="mt-4 text-lg font-bold text-slate-800">MAX30205</h2>
                    <p class="text-sm text-slate-500">Suhu kulit (Skin Temperature)</p>

                    <dl class="mt-4 space-y-2 border-t border-slate-100 pt-4 text-sm">
                        <div class="flex justify-between">
                            <dt class="text-slate-500">Pembacaan</dt>
                            <dd class="font-semibold text-slate-800">-</dd>
                        </div>
                        <div class="flex justify-between">
                            <dt class="text-slate-500">Antarmuka</dt>
                            <dd class="font-semibold text-slate-800">I2C</dd>
                        </div>
                    </dl>
                </div>

                <div class="flex flex-col rounded-2xl bg-white p-6 shadow-sm ring-1 ring-slate-200">
                    <div class="flex items-start justify-between">
                        <div class="flex h-12 w-12 items-center justify-center rounded-2xl bg-emerald-100 text-emerald-600">
                            <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M9 3v2m6-2v2M9 19v2m6-2v2M3 9h2m-2 6h2m14-6h2m-2 6h2M7 5h10a2 2 0 0 1 2 2v10a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V7a2 2 0 0 1 2-2Z"/>
                            </svg>
                        </div>
                        <span class="inline-flex items-center gap-1.5 rounded-full px-2.5 py-1 text-xs font-semibold ring-1 ring-inset {{ $online ? 'bg-emerald-100 text-emerald-700 ring-emerald-200' : 'bg-rose-100 text-rose-700 ring-rose-200' }}">
                            <span class="h-1.5 w-1.5 rounded-full {{ $online ? 'bg-emerald-500' : 'bg-rose-500' }}"></span>
                            {{ $online ? 'Online' : 'Offline' }}
                        </span>
                    </div>

                    <h2 class="mt-4 text-lg font-bold text-slate-800">Grove-GSR</h2>
                    <p class="text-sm text-slate-500">Respons resistansi kulit (EDA)</p>

                    <dl class="mt-4 space-y-2 border-t border-slate-100 pt-4 text-sm">
                        <div class="flex justify-between">
                            <dt class="text-slate-500">Pembacaan</dt>
                            <dd class="font-semibold text-slate-800">-</dd>
                        </div>
                        <div class="flex justify-between">
                            <dt class="text-slate-500">Antarmuka</dt>
                            <dd class="font-semibold text-slate-800">ADC</dd>
                        </div>
                    </dl>
                </div>
            </div>
        </div>

        <div
            x-show="openDeleteModal"
            x-transition.opacity
            class="fixed inset-0 z-50 flex items-center justify-center bg-slate-900/40 backdrop-blur-sm p-4"
            style="display: none;"
            x-cloak
        >
            <div @click.away="openDeleteModal = false"
                 class="bg-white rounded-2xl max-w-sm w-full p-6 shadow-xl border border-slate-100 text-center space-y-4"
                 x-transition>

                <div class="w-12 h-12 rounded-full bg-red-100 text-red-600 flex items-center justify-center mx-auto">
                    <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                    </svg>
                </div>

                <div>
                    <h3 class="text-base font-bold text-slate-900">Hapus Perangkat Ini?</h3>
                    <p class="text-xs text-slate-500 mt-1">Yakin ingin menghapus perangkat ini beserta seluruh sensornya? Tindakan ini tidak dapat dibatalkan.</p>
                </div>

                <div class="flex items-center gap-3 pt-2">
                    <button type="button" @click="openDeleteModal = false" class="w-full py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-700 rounded-xl text-xs font-semibold transition-all">
                        Batal
                    </button>

                    <form action="{{ route('perangkat.destroy', $device) }}" method="POST" class="w-full">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="w-full py-2.5 bg-red-600 hover:bg-red-700 text-white rounded-xl text-xs font-semibold shadow-sm shadow-red-200 transition-all">
                            Ya, Hapus
                        </button>
                    </form>
                </div>
            </div>
        </div>
        @empty
        <div class="mt-6 rounded-3xl border-2 border-dashed border-slate-200 bg-white p-12 text-center shadow-sm">
            <div class="mx-auto flex h-16 w-16 items-center justify-center rounded-2xl bg-emerald-50 text-emerald-600 ring-8 ring-emerald-50/50">
                <svg class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M9 3v2m6-2v2M9 19v2m6-2v2M3 9h2m-2 6h2m14-6h2m-2 6h2M7 5h10a2 2 0 0 1 2 2v10a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V7a2 2 0 0 1 2-2Z"/>
                </svg>
            </div>
            <h3 class="mt-4 text-base font-bold text-slate-800">Belum Ada Perangkat Terdaftar</h3>

        </div>
        @endforelse

    </div>
</x-layout>
