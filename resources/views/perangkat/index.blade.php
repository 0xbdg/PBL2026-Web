<x-layout>
    <x-slot:title>Sensor & Perangkat IoT</x-slot:title>

    <div x-data="{
        showModal: false,
        editModal: false,
        deleteModal: false,
        editAction: '',
        deleteAction: '',
        editForm: { device_name: '', esp32_topic: '', gsr_topic: '', heartrate_topic: '', temp_topic: '' },
        openEdit(action, data) { this.editAction = action; this.editForm = { ...data }; this.editModal = true; },
        openDelete(action) { this.deleteAction = action; this.deleteModal = true; }
    }">
        <div class="flex flex-wrap items-end justify-between gap-3">
            <div>
                <h1 class="text-2xl font-bold text-slate-800">Sensor &amp; Perangkat IoT</h1>
                <p class="text-sm text-slate-500">Koneksi MQTT: <span id="conn-status">-</span></p>
            </div>

            <button type="button" @click="showModal = true" class="inline-flex items-center gap-2 rounded-xl bg-emerald-600 px-4 py-2.5 text-sm font-semibold text-white shadow-sm hover:bg-emerald-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-emerald-600 transition-colors">
                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M12 4.5v15m7.5-7.5h-15"/>
                </svg>
                Tambah Perangkat
            </button>
        </div>

        <!-- Modal Tambah -->
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
                    <div>
                        <label for="esp32_topic" class="block text-sm font-medium text-slate-700">ESP32 Topic</label>
                        <input
                            type="text"
                            id="esp32_topic"
                            name="esp32_topic"
                            required
                            placeholder="Contoh: esp32/data1"
                            class="mt-1 block w-full rounded-xl border border-slate-300 px-3 py-2 text-sm text-slate-800 shadow-sm focus:border-emerald-500 focus:outline-none focus:ring-1 focus:ring-emerald-500"
                        >
                    </div>
                    <div>
                        <label for="gsr_topic" class="block text-sm font-medium text-slate-700">GSR Topic</label>
                        <input
                            type="text"
                            id="gsr_topic"
                            name="gsr_topic"
                            required
                            placeholder="Contoh: gsr/data1"
                            class="mt-1 block w-full rounded-xl border border-slate-300 px-3 py-2 text-sm text-slate-800 shadow-sm focus:border-emerald-500 focus:outline-none focus:ring-1 focus:ring-emerald-500"
                        >
                    </div>
                    <div>
                        <label for="heartrate_topic" class="block text-sm font-medium text-slate-700">HeartRate Topic</label>
                        <input
                            type="text"
                            id="heartrate_topic"
                            name="heartrate_topic"
                            required
                            placeholder="Contoh: heartrate/data1"
                            class="mt-1 block w-full rounded-xl border border-slate-300 px-3 py-2 text-sm text-slate-800 shadow-sm focus:border-emerald-500 focus:outline-none focus:ring-1 focus:ring-emerald-500"
                        >
                    </div>
                    <div>
                        <label for="temp_topic" class="block text-sm font-medium text-slate-700">Temperature Topic</label>
                        <input
                            type="text"
                            id="temp_topic"
                            name="temp_topic"
                            required
                            placeholder="Contoh: temp/data1"
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
        <div id="card-{{ $device["id"] }}" class="mt-6 rounded-3xl border-2 border-slate-300 bg-white p-4 sm:p-6 shadow-sm">
            <!-- Header: nama perangkat + tombol Edit & Hapus -->
            <div class="mb-6 flex items-center justify-between border-b border-slate-100 pb-4">
                <h1 class="text-xl font-bold text-slate-800">{{ $device["device_name"] }}</h1>

                <div class="flex items-center gap-1">
                    <!-- Tombol Edit -->
                    <button type="button"
                        @click="openEdit(
                            '{{ route('perangkat.update', $device) }}',
                            {{ Js::from($device->only(['device_name', 'esp32_topic', 'gsr_topic', 'heartrate_topic', 'temp_topic'])) }}
                        )"
                        class="rounded-xl p-2 text-slate-400 hover:bg-emerald-50 hover:text-emerald-600 transition-colors" title="Edit Perangkat">
                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0115.75 21H5.25A2.25 2.25 0 013 18.75V8.25A2.25 2.25 0 015.25 6H10"/>
                        </svg>
                    </button>

                    <!-- Tombol Hapus -->
                    <button type="button"
                        @click="openDelete('{{ route('perangkat.destroy', $device) }}')"
                        class="rounded-xl p-2 text-slate-400 hover:bg-rose-50 hover:text-rose-600 transition-colors" title="Hapus Perangkat">
                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                        </svg>
                    </button>
                </div>
            </div>

            <div class="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-4">
                <!-- ESP32 -->
                <div id="card-{{$device["device_name"]}}-{{$device["esp32_topic"]}}" class="flex flex-col bg-amber-200 rounded-2xl p-6 shadow-sm ring-1 ring-slate-200">
                    <div class="flex items-start justify-between">
                        <div id="icon-{{$device["device_name"]}}-{{$device["esp32_topic"]}}" class="flex h-12 w-12 items-center justify-center rounded-2xl bg-slate-100 text-slate-600">
                            <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M9 3v2m6-2v2M9 19v2m6-2v2M3 9h2m-2 6h2m14-6h2m-2 6h2M7 5h10a2 2 0 0 1 2 2v10a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V7a2 2 0 0 1 2-2Z"/>
                            </svg>
                        </div>
                        <span class="inline-flex items-center gap-1.5 rounded-full px-2.5 py-1 text-xs font-semibold">
                            <span id="dot-{{$device["device_name"]}}-{{$device["esp32_topic"]}}" class="h-1.5 w-1.5 rounded-full bg-amber-500"></span>
                            <span id="status-{{$device["device_name"]}}-{{$device["esp32_topic"]}}" class="status-box text-amber-600">Menunggu</span>
                        </span>
                    </div>

                    <h2 class="mt-4 text-lg font-bold text-slate-800">ESP32-C3 Supermini</h2>
                    <p class="text-sm text-slate-500">Mikrokontroller</p>

                    <dl class="mt-4 space-y-2 border-t border-slate-100 pt-4 text-sm">
                        <div class="flex justify-between">
                            <dt class="text-slate-500">LED BUILTIN</dt>
                            <dd class="font-semibold text-slate-800">-</dd>
                        </div>
                        <div class="flex justify-between">
                            <dt class="text-slate-500">Topic</dt>
                            <dd class="font-semibold text-slate-800">{{$device["esp32_topic"]}}</dd>
                        </div>
                    </dl>
                </div>

                <!-- MAX30102 -->
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
                            <dt class="text-slate-500">Topic</dt>
                            <dd class="font-semibold text-slate-800">{{$device['heartrate_topic']}}</dd>
                        </div>
                    </dl>
                </div>

                <!-- MAX30205 -->
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
                            <dt class="text-slate-500">Topic</dt>
                            <dd class="font-semibold text-slate-800">{{$device['temp_topic']}}</dd>
                        </div>
                    </dl>
                </div>

                <!-- Grove-GSR -->
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
                            <dt class="text-slate-500">Topic</dt>
                            <dd class="font-semibold text-slate-800">{{$device["gsr_topic"]}}</dd>
                        </div>
                    </dl>
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

        <!-- Modal Edit (satu untuk semua perangkat) -->
        <div x-show="editModal" x-transition.opacity x-cloak
             class="fixed inset-0 z-50 flex items-center justify-center bg-slate-900/50 backdrop-blur-sm p-4">
            <div @click.away="editModal = false" x-transition
                 class="w-full max-w-md rounded-3xl bg-white p-6 shadow-xl ring-1 ring-slate-200">
                <div class="flex items-center justify-between border-b border-slate-100 pb-4">
                    <h3 class="text-lg font-bold text-slate-800">Edit Perangkat</h3>
                    <button type="button" @click="editModal = false" class="text-slate-400 hover:text-slate-600">
                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>

                <form :action="editAction" method="POST" class="mt-4 space-y-4">
                    @csrf
                    @method('PUT')

                    @php
                        $fields = [
                            'device_name'     => 'Device Name',
                            'esp32_topic'     => 'ESP32 Topic',
                            'gsr_topic'       => 'GSR Topic',
                            'heartrate_topic' => 'HeartRate Topic',
                            'temp_topic'      => 'Temperature Topic',
                        ];
                    @endphp

                    @foreach ($fields as $name => $label)
                    <div>
                        <label for="edit_{{ $name }}" class="block text-sm font-medium text-slate-700">{{ $label }}</label>
                        <input type="text" id="edit_{{ $name }}" name="{{ $name }}" required x-model="editForm.{{ $name }}"
                               class="mt-1 block w-full rounded-xl border border-slate-300 px-3 py-2 text-sm text-slate-800 shadow-sm focus:border-emerald-500 focus:outline-none focus:ring-1 focus:ring-emerald-500">
                    </div>
                    @endforeach

                    <div class="flex justify-end gap-3 pt-4 border-t border-slate-100">
                        <button type="button" @click="editModal = false"
                                class="rounded-xl px-4 py-2 text-sm font-semibold text-slate-600 hover:bg-slate-100 transition-colors">
                            Batal
                        </button>
                        <button type="submit"
                                class="rounded-xl bg-emerald-600 px-4 py-2 text-sm font-semibold text-white shadow-sm hover:bg-emerald-500 transition-colors">
                            Simpan Perubahan
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Modal Hapus (satu untuk semua perangkat) -->
        <div x-show="deleteModal" x-transition.opacity x-cloak
             class="fixed inset-0 z-50 flex items-center justify-center bg-slate-900/40 backdrop-blur-sm p-4">
            <div @click.away="deleteModal = false" x-transition
                 class="bg-white rounded-2xl max-w-sm w-full p-6 shadow-xl border border-slate-100 text-center space-y-4">

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
                    <button type="button" @click="deleteModal = false"
                            class="w-full py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-700 rounded-xl text-xs font-semibold transition-all">
                        Batal
                    </button>

                    <form :action="deleteAction" method="POST" class="w-full">
                        @csrf
                        @method('DELETE')
                        <button type="submit"
                                class="w-full py-2.5 bg-red-600 hover:bg-red-700 text-white rounded-xl text-xs font-semibold shadow-sm shadow-red-200 transition-all">
                            Ya, Hapus
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            const devices = @json($perangkat->items());
            console.log(devices);

            // Ganti dengan IP lokal komputer/server Mosquitto Anda
            const brokerUrl = 'ws://192.168.1.56:9001';

            const client = mqtt.connect(brokerUrl, {
                clientId: 'laravel_dashboard_' + Math.random().toString(16).substr(2, 8)
            });

            client.on('connect', function () {
                const connStatus = document.getElementById('conn-status');
                if (connStatus) {
                    connStatus.innerText = 'Terhubung';
                    connStatus.style.color = 'green';
                }

                // Subscribe otomatis ke topik tiap perangkat
                devices.forEach(dev => {
                    const topic = dev.esp32_topic ?? dev['esp32_topic'];
                    client.subscribe(topic, function (err) {
                        if (!err) {
                            console.log('Berhasil subscribe ke topik: ' + topic);
                        } else {
                            console.error('Gagal subscribe ke: ' + topic, err);
                        }
                    });
                });
            });

            client.on('error', function (error) {
                console.error('Koneksi MQTT Gagal:', error);
                const connStatus = document.getElementById('conn-status');
                if (connStatus) {
                    connStatus.innerText = 'Gagal Terhubung';
                    connStatus.style.color = 'red';
                }
            });

            client.on('message', function (topic, payload) {
                const message = payload.toString();
                console.log(`Pesan masuk [${topic}]: ${message}`);

                // Cocokkan topik yang masuk dengan data perangkat di halaman ini
                devices.forEach(dev => {
                    const esp32Topic = dev.esp32_topic ?? dev['esp32_topic'];
                    const devName = dev.device_name ?? dev['device_name'];

                    if (topic === esp32Topic) {
                        const cardElement = document.getElementById(`card-${devName}-${esp32Topic}`);
                        const dotElement = document.getElementById(`dot-${devName}-${esp32Topic}`);
                        const statusElement = document.getElementById(`status-${devName}-${esp32Topic}`);
                        if (statusElement) {
                            statusElement.innerText = message;

                            if (message.toLowerCase().includes('terhubung')) {
                                if (dotElement) {
                                    dotElement.className = 'h-1.5 w-1.5 rounded-full bg-emerald-500';
                                }
                                if (cardElement) {
                                    cardElement.className = 'flex flex-col bg-emerald-200 rounded-2xl p-6 shadow-sm ring-1 ring-slate-200';
                                }
                                statusElement.className = 'status-box text-emerald-600';
                            } else if (message.toLowerCase().includes('terputus')) {
                                if (dotElement) {
                                    dotElement.className = 'h-1.5 w-1.5 rounded-full bg-rose-500';
                                }
                                if (cardElement) {
                                    cardElement.className = 'flex flex-col bg-rose-200 rounded-2xl p-6 shadow-sm ring-1 ring-slate-200';
                                }
                                statusElement.className = 'status-box text-rose-600';
                            } else {
                                statusElement.className = 'status-box text-amber-600';
                                cardElement.className = 'flex flex-col bg-amber-200 rounded-2xl p-6 shadow-sm ring-1 ring-slate-200';
                                dotElement.className = 'h-1.5 w-1.5 rounded-full bg-amber-500';
                            }
                        }
                    }
                });
            });
        </script>
    @endpush
</x-layout>
