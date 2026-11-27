<x-layout>
    <x-slot:title>Lokasi Spa</x-slot:title>

    @php
        $fields = [
            ['spa_name', 'Nama Lokasi', 'text', 'Contoh: Pantai Sanur', []],
            ['address', 'Alamat', 'text', 'Contoh: Jl. Pantai Sanur, Denpasar', []],
            ['latitude', 'Latitude', 'number', 'Contoh: -8.6933', ['step' => 'any', 'min' => -90, 'max' => 90]],
            ['longitude', 'Longitude', 'number', 'Contoh: 115.2630', ['step' => 'any', 'min' => -180, 'max' => 180]],
        ];
        $inputClass = 'mt-1 block w-full rounded-xl border border-slate-300 px-3 py-2 text-sm text-slate-800 shadow-sm focus:border-emerald-500 focus:outline-none focus:ring-1 focus:ring-emerald-500';
    @endphp

    <div x-data="{
            showModal: false,
            editModal: false,
            deleteModal: false,
            editAction: '',
            deleteAction: '',
            editForm: { spa_name: '', address: '', latitude: '', longitude: '' },
            openEdit(action, data) { this.editAction = action; this.editForm = { ...data }; this.editModal = true; },
            openDelete(action) { this.deleteAction = action; this.deleteModal = true; },
            tab: 'manual',
            addForm: { spa_name: '', address: '', latitude: '', longitude: '' },
            link: '',
            linkLoading: false,
            linkError: '',
            linkDone: false,
            async fetchLink() {
                if (!this.link.trim() || this.linkLoading) return;
                this.linkLoading = true;
                this.linkError = '';
                this.linkDone = false;
                try {
                    const response = await fetch('{{ route('lokasi.parse-link') }}', {
                        method: 'POST',
                        headers: { 'Content-Type': 'application/json', 'Accept': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                        body: JSON.stringify({ link: this.link.trim() })
                    });
                    const data = await response.json();
                    if (!response.ok) throw new Error(data.message);
                    Object.keys(this.addForm).forEach(key => this.addForm[key] = data[key] ?? '');
                    this.linkDone = true;
                } catch (error) {
                    this.linkError = error.message || 'Link gagal dibaca.';
                } finally {
                    this.linkLoading = false;
                }
            }
        }"
        @keydown.escape.window="showModal = false; editModal = false; deleteModal = false">

        <!-- Header -->
        <div class="flex flex-wrap items-end justify-between gap-3">
            <div>
                <h1 class="text-2xl font-bold text-slate-800">Data Lokasi Spa</h1>
                <p class="text-sm text-slate-500">Daftar lokasi Spa beserta alamat dan titik koordinatnya.</p>
            </div>

            <button type="button" @click="showModal = true" class="inline-flex items-center gap-2 rounded-xl bg-emerald-600 px-4 py-2.5 text-sm font-semibold text-white shadow-sm hover:bg-emerald-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-emerald-600 transition-colors">
                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M12 4.5v15m7.5-7.5h-15"/>
                </svg>
                Tambah Lokasi
            </button>
        </div>

        @if ($errors->any())
            <div class="mt-4 rounded-xl bg-red-50 p-4 text-sm text-red-700 ring-1 ring-red-200">
                <p class="font-semibold">Data gagal disimpan:</p>
                <ul class="mt-1 list-disc pl-5">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <!-- Daftar Card -->
        <div class="mt-6 grid grid-cols-1 gap-4 sm:grid-cols-2 xl:grid-cols-3">
            @forelse ($lokasis as $lokasi)
                <div class="flex flex-col rounded-3xl bg-white p-5 shadow-sm ring-1 ring-slate-200 transition-shadow hover:shadow-md">
                    <div class="flex items-start gap-3">
                        <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-emerald-50 text-emerald-600">
                            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M15 10.5a3 3 0 11-6 0 3 3 0 016 0z"/>
                                <path d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1115 0z"/>
                            </svg>
                        </div>
                        <div class="min-w-0">
                            <h2 class="text-base font-bold text-slate-800 break-words">{{ $lokasi->spa_name }}</h2>
                            <p class="mt-1 text-sm text-slate-500 break-words">{{ $lokasi->address }}</p>
                        </div>
                    </div>

                    <dl class="mt-4 grid grid-cols-2 gap-3">
                        <div class="rounded-xl bg-slate-50 px-3 py-2">
                            <dt class="text-xs text-slate-500">Latitude</dt>
                            <dd class="text-sm font-semibold text-slate-800 break-all">{{ $lokasi->latitude }}</dd>
                        </div>
                        <div class="rounded-xl bg-slate-50 px-3 py-2">
                            <dt class="text-xs text-slate-500">Longitude</dt>
                            <dd class="text-sm font-semibold text-slate-800 break-all">{{ $lokasi->longitude }}</dd>
                        </div>
                    </dl>

                    <div class="mt-auto pt-4">
                        <div class="flex justify-end gap-2 border-t border-slate-100 pt-4">
                            <button
                                type="button"
                                @click="openEdit(@js(route('lokasi.update', $lokasi)), @js($lokasi->only(['spa_name', 'address', 'latitude', 'longitude'])))"
                                class="inline-flex items-center gap-2 rounded-xl bg-amber-50 px-4 py-2 text-sm font-semibold text-amber-700 hover:bg-amber-100 transition-colors"
                            >
                                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M16.862 4.487a2.1 2.1 0 112.97 2.97L8.25 19.04 4 20l.96-4.25L16.862 4.487z"/>
                                </svg>
                                Edit
                            </button>
                            <button
                                type="button"
                                @click="openDelete(@js(route('lokasi.destroy', $lokasi)))"
                                class="inline-flex items-center gap-2 rounded-xl bg-red-50 px-4 py-2 text-sm font-semibold text-red-700 hover:bg-red-100 transition-colors"
                            >
                                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M4 7h16M10 11v6m4-6v6M6 7l1 13h10l1-13M9 7V4h6v3"/>
                                </svg>
                                Hapus
                            </button>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-span-full rounded-3xl border border-dashed border-slate-300 p-10 text-center text-sm text-slate-500">
                    Belum ada data lokasi.
                </div>
            @endforelse
        </div>

        <!-- Modal Tambah -->
        <div x-show="showModal" x-transition.opacity x-cloak
             class="fixed inset-0 z-50 flex items-center justify-center bg-slate-900/50 backdrop-blur-sm p-4">
            <div @click.away="showModal = false" x-transition
                 class="w-full max-w-md max-h-full overflow-y-auto rounded-3xl bg-white p-6 shadow-xl ring-1 ring-slate-200">
                <div class="flex items-center justify-between border-b border-slate-100 pb-4">
                    <h3 class="text-lg font-bold text-slate-800">Tambah Lokasi Baru</h3>
                    <button type="button" @click="showModal = false" class="text-slate-400 hover:text-slate-600">
                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>

                <form action="{{ route('lokasi.store') }}" method="POST" class="mt-4 space-y-4">
                    @csrf

                    <!-- Tab -->
                    <div class="grid grid-cols-2 gap-1 rounded-xl bg-slate-100 p-1">
                        <button type="button" @click="tab = 'manual'"
                                :class="tab === 'manual' ? 'bg-white text-emerald-700 shadow-sm' : 'text-slate-500 hover:text-slate-700'"
                                class="rounded-lg px-3 py-2 text-sm font-semibold transition-colors">
                            Manual
                        </button>
                        <button type="button" @click="tab = 'otomatis'"
                                :class="tab === 'otomatis' ? 'bg-white text-emerald-700 shadow-sm' : 'text-slate-500 hover:text-slate-700'"
                                class="rounded-lg px-3 py-2 text-sm font-semibold transition-colors">
                            Dari Link Google
                        </button>
                    </div>

                    <!-- Input Link -->
                    <div x-show="tab === 'otomatis'">
                        <label for="maps_link" class="block text-sm font-medium text-slate-700">Link Google Maps</label>
                        <div class="flex items-end gap-2">
                            <input type="text" id="maps_link" x-model="link"
                                   @input="linkDone = false; linkError = ''"
                                   @keydown.enter.prevent="fetchLink()"
                                   placeholder="https://maps.app.goo.gl/..." class="{{ $inputClass }}">
                            <button type="button" @click="fetchLink()" :disabled="linkLoading || !link.trim()"
                                    class="shrink-0 rounded-xl bg-slate-800 px-4 py-2 text-sm font-semibold text-white hover:bg-slate-700 disabled:opacity-50">
                                <span x-text="linkLoading ? 'Membaca...' : 'Ambil Data'"></span>
                            </button>
                        </div>
                        <p x-show="linkError" x-text="linkError" class="mt-2 text-sm text-red-600"></p>
                        <p x-show="linkDone" class="mt-2 text-sm text-slate-500">Periksa hasilnya. Lengkapi kolom yang masih kosong sebelum menyimpan.</p>
                    </div>

                    <!-- Kolom data: dipakai kedua tab -->
                    <div x-show="tab === 'manual' || linkDone" class="space-y-4">
                        @foreach ($fields as [$name, $label, $type, $placeholder, $extra])
                            <div>
                                <label for="{{ $name }}" class="block text-sm font-medium text-slate-700">{{ $label }}</label>
                                <input type="{{ $type }}" id="{{ $name }}" name="{{ $name }}" required
                                       x-model="addForm.{{ $name }}"
                                       placeholder="{{ $placeholder }}" class="{{ $inputClass }}"
                                       @foreach ($extra as $key => $value) {{ $key }}="{{ $value }}" @endforeach>
                            </div>
                        @endforeach
                    </div>

                    <div class="flex justify-end gap-3 pt-4 border-t border-slate-100">
                        <button type="button" @click="showModal = false" class="rounded-xl px-4 py-2 text-sm font-semibold text-slate-600 hover:bg-slate-100 transition-colors">
                            Batal
                        </button>
                        <button type="submit" :disabled="tab === 'otomatis' && !linkDone" class="rounded-xl bg-emerald-600 px-4 py-2 text-sm font-semibold text-white shadow-sm hover:bg-emerald-500 disabled:opacity-50 transition-colors">
                            Simpan Lokasi
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Modal Edit -->
        <div x-show="editModal" x-transition.opacity x-cloak
             class="fixed inset-0 z-50 flex items-center justify-center bg-slate-900/50 backdrop-blur-sm p-4">
            <div @click.away="editModal = false" x-transition
                 class="w-full max-w-md max-h-full overflow-y-auto rounded-3xl bg-white p-6 shadow-xl ring-1 ring-slate-200">
                <div class="flex items-center justify-between border-b border-slate-100 pb-4">
                    <h3 class="text-lg font-bold text-slate-800">Edit Lokasi</h3>
                    <button type="button" @click="editModal = false" class="text-slate-400 hover:text-slate-600">
                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>

                <form :action="editAction" method="POST" class="mt-4 space-y-4">
                    @csrf
                    @method('PUT')

                    @foreach ($fields as [$name, $label, $type, $placeholder, $extra])
                        <div>
                            <label for="edit_{{ $name }}" class="block text-sm font-medium text-slate-700">{{ $label }}</label>
                            <input type="{{ $type }}" id="edit_{{ $name }}" name="{{ $name }}" required
                                   x-model="editForm.{{ $name }}" class="{{ $inputClass }}"
                                   @foreach ($extra as $key => $value) {{ $key }}="{{ $value }}" @endforeach>
                        </div>
                    @endforeach

                    <div class="flex justify-end gap-3 pt-4 border-t border-slate-100">
                        <button type="button" @click="editModal = false" class="rounded-xl px-4 py-2 text-sm font-semibold text-slate-600 hover:bg-slate-100 transition-colors">
                            Batal
                        </button>
                        <button type="submit" class="rounded-xl bg-emerald-600 px-4 py-2 text-sm font-semibold text-white shadow-sm hover:bg-emerald-500 transition-colors">
                            Simpan Perubahan
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Modal Hapus -->
        <div x-show="deleteModal" x-transition.opacity x-cloak
             class="fixed inset-0 z-50 flex items-center justify-center bg-slate-900/50 backdrop-blur-sm p-4">
            <div @click.away="deleteModal = false" x-transition
                 class="w-full max-w-md rounded-3xl bg-white p-6 shadow-xl ring-1 ring-slate-200">
                <h3 class="text-lg font-bold text-slate-800">Hapus Lokasi?</h3>
                <p class="mt-2 text-sm text-slate-500">Data lokasi yang sudah dihapus tidak bisa dikembalikan.</p>

                <form :action="deleteAction" method="POST" class="mt-6 flex justify-end gap-3 border-t border-slate-100 pt-4">
                    @csrf
                    @method('DELETE')

                    <button type="button" @click="deleteModal = false" class="rounded-xl px-4 py-2 text-sm font-semibold text-slate-600 hover:bg-slate-100 transition-colors">
                        Batal
                    </button>
                    <button type="submit" class="rounded-xl bg-red-600 px-4 py-2 text-sm font-semibold text-white shadow-sm hover:bg-red-500 transition-colors">
                        Ya, Hapus
                    </button>
                </form>
            </div>
        </div>
    </div>
</x-layout>
