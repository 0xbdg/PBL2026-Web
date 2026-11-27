<x-layout>
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
<style>
  .resort-pin { width: 22px; height: 22px; border-radius: 9999px 9999px 9999px 0; transform: rotate(-45deg); border: 3px solid #fff; box-shadow: 0 2px 6px rgba(0,0,0,.35); background: #64748b; transition: background .15s; }
  .resort-pin.active { background: #059669; }
  .resort-pin-wrap { display: flex; align-items: center; justify-content: center; }
</style>

<div class="w-full p-4 sm:p-6 lg:p-8 space-y-6">

    <!-- Header Section & Navigation (Full Width) -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
      <div>
        <div class="flex items-center gap-2 text-xs font-semibold text-slate-400 uppercase tracking-wider mb-1">
          <a href="#" class="hover:text-slate-600 transition-colors">Data Wisatawan</a>
          <span>/</span>
          <span class="text-emerald-600">Tambah Sesi Baru</span>
        </div>
        <h1 class="text-2xl font-bold text-slate-900 tracking-tight">Tambah Sesi Wisatawan</h1>
        <p class="text-xs text-slate-500">Masukkan data wisatawan dan lokasi resort untuk mendaftarkan sesi baru.</p>
      </div>

      <div class="flex items-center gap-3">
        <button type="button" onclick="history.back()" class="px-4 py-2 bg-white text-slate-600 hover:bg-slate-50 border border-slate-200 rounded-lg text-xs font-semibold shadow-sm transition-all flex items-center gap-1.5">
          <i data-lucide="arrow-left" class="w-4 h-4"></i> Kembali
        </button>
      </div>
    </div>

    <!-- Main Form Card (Full Width) -->
    <form id="addForm" method="POST" action="{{route('wisatawan.store')}}" class="w-full bg-white rounded-2xl p-6 border border-slate-100 shadow-sm space-y-5">
      @csrf
      <div class="flex items-center gap-2 border-b border-slate-100 pb-3">
        <i data-lucide="user-plus" class="w-5 h-5 text-emerald-600"></i>
        <h2 class="font-semibold text-slate-800 text-sm">Informasi Wisatawan & Sesi</h2>
      </div>

      <div class="space-y-4">
        <!-- Nama Wisatawan (Full Width) -->
        <div>
          <label for="nama" class="block text-xs font-semibold text-slate-600 mb-1">Nama Wisatawan <span class="text-red-500">*</span></label>
            <input type="text" id="nama" name="nama_wisatawan" value="{{ old('nama_wisatawan') }}" required placeholder="Contoh: Kenji Tanaka" class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-lg text-xs text-slate-800 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:bg-white transition-all">
            @error('nama_wisatawan') <p class="mt-1 text-xs text-rose-600">{{ $message }}</p> @enderror
        </div>

        <!-- Lokasi Resort (Peta Leaflet) -->
        <div>
          <label class="block text-xs font-semibold text-slate-600 mb-1">Lokasi Resort <span class="text-red-500">*</span></label>

          <input type="hidden" id="lokasi_resort" name="lokasi_resort" value="{{ old('lokasi_resort') }}">

          <div id="resortMap" class="w-full h-80 rounded-xl border border-slate-200 z-0"></div>

          <div id="resortSelected" class="mt-2 flex items-center gap-2 px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-lg text-xs text-slate-500">
            <i data-lucide="map-pin" class="w-4 h-4 text-slate-400"></i>
            <span id="resortSelectedText">Klik pin pada peta untuk memilih lokasi resort</span>
          </div>
          <p id="resortError" class="mt-1 text-xs text-rose-600 hidden">Silakan pilih lokasi resort pada peta.</p>
          @error('lokasi_resort') <p class="mt-1 text-xs text-rose-600">{{ $message }}</p> @enderror
        </div>

        <!-- Tanggal Sesi -->
        <div>
          <label for="tanggal" class="block text-xs font-semibold text-slate-600 mb-1">Tanggal Sesi <span class="text-red-500">*</span></label>
          <input type="date" id="tanggal" name="tanggal_sesi" value="{{ old('tanggal_sesi') }}" required class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-lg text-xs text-slate-800 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:bg-white transition-all">
          @error('tanggal_sesi') <p class="mt-1 text-xs text-rose-600">{{ $message }}</p> @enderror
        </div>

        <!-- Device -->
        <div>
            <label for="device" class="block text-xs font-semibold text-slate-600 mb-1">Device <span class="text-red-500">*</span></label>
            <div class="relative">
                <select id="device" name="device_id" required class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-lg text-xs text-slate-800 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:bg-white transition-all appearance-none pr-9">
                    <option value="" disabled {{ old('device_id') ? '' : 'selected' }}>Pilih Device</option>
                    @foreach ($devices as $device)
                        <option value="{{ $device->id }}"
                            @disabled($device->sedang_dipakai)
                            @selected(old('device_id') == $device->id)>
                            {{ $device->device_name }}{{ $device->sedang_dipakai ? ' (sedang dipakai)' : '' }}
                        </option>
                    @endforeach
                </select>
                <i data-lucide="chevron-down" class="w-4 h-4 text-slate-400 absolute right-3 top-1/2 -translate-y-1/2 pointer-events-none"></i>
            </div>
            @error('device_id') <p class="mt-1 text-xs text-rose-600">{{ $message }}</p> @enderror
        </div>
      </div>

      <!-- Submit & Cancel Action Bar -->
      <div class="pt-4 border-t border-slate-100 flex items-center justify-end gap-3">
        <button type="button" onclick="history.back()" class="px-4 py-2.5 bg-white border border-slate-200 hover:bg-slate-50 text-slate-600 rounded-xl text-xs font-semibold transition-all">
          Batal
        </button>
        <button type="submit" class="px-5 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white rounded-xl text-xs font-semibold shadow-sm shadow-emerald-200 transition-all flex items-center gap-2">
          <i data-lucide="plus" class="w-4 h-4"></i> Tambah Sesi Baru
        </button>
      </div>

    </form>

  </div>

  @push('scripts')
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    <script>
      document.addEventListener('DOMContentLoaded', function () {
        // Data lokasi dari tabel spa_locations (dikirim controller sebagai $spaLocations)
        const resorts = {{ Js::from($spaLocations->map(fn ($s) => [
          'name'    => $s->spa_name,
          'address' => $s->address,
          'lat'     => (float) $s->latitude,
          'lng'     => (float) $s->longitude,
        ])->values()) }};

        const input    = document.getElementById('lokasi_resort');
        const textEl   = document.getElementById('resortSelectedText');
        const errorEl  = document.getElementById('resortError');
        const selBox   = document.getElementById('resortSelected');

        // Batasi peta hanya untuk pulau Bali
        const baliBounds = L.latLngBounds([-8.95, 114.35], [-7.95, 115.75]);

        const map = L.map('resortMap', {
          scrollWheelZoom: false,
          maxBounds: baliBounds,
          maxBoundsViscosity: 1.0,
          minZoom: 9
        }).setView([-8.45, 115.27], 11);
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
          maxZoom: 19,
          attribution: '&copy; OpenStreetMap contributors'
        }).addTo(map);

        const markers = {};

        function pinIcon(active) {
          return L.divIcon({
            className: 'resort-pin-wrap',
            html: '<div class="resort-pin' + (active ? ' active' : '') + '"></div>',
            iconSize: [26, 26],
            iconAnchor: [13, 26],
            tooltipAnchor: [0, -26]
          });
        }

        function select(name) {
          input.value = name;
          textEl.textContent = name;
          selBox.classList.remove('text-slate-500');
          selBox.classList.add('text-slate-800', 'font-semibold');
          errorEl.classList.add('hidden');
          Object.keys(markers).forEach(function (n) {
            markers[n].setIcon(pinIcon(n === name));
          });
        }

        resorts.forEach(function (r) {
          const m = L.marker([r.lat, r.lng], { icon: pinIcon(false), title: r.name }).addTo(map);
          const tip = document.createElement('div');
          const tipName = document.createElement('strong');
          tipName.textContent = r.name;
          tip.appendChild(tipName);
          if (r.address) {
            tip.appendChild(document.createElement('br'));
            tip.appendChild(document.createTextNode(r.address));
          }
          m.bindTooltip(tip, { direction: 'top' });
          m.on('click', function () { select(r.name); });
          markers[r.name] = m;
        });

        if (resorts.length) {
          map.fitBounds(L.featureGroup(Object.values(markers)).getBounds().pad(0.2), { maxZoom: 14 });
        } else {
          textEl.textContent = 'Belum ada data lokasi spa.';
        }

        // Pulihkan pilihan jika validasi gagal (old input)
        if (input.value && markers[input.value]) {
          select(input.value);
        }

        // Validasi: hidden input tidak bisa pakai "required" bawaan browser
        document.getElementById('addForm').addEventListener('submit', function (e) {
          if (!input.value) {
            e.preventDefault();
            errorEl.classList.remove('hidden');
            document.getElementById('resortMap').scrollIntoView({ behavior: 'smooth', block: 'center' });
          }
        });

        if (window.lucide) lucide.createIcons();
      });
    </script>
  @endpush
</x-layout>
