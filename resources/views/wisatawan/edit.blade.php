<x-layout>
<div class="w-full p-4 sm:p-6 lg:p-8 space-y-6">

    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
      <div>
        <div class="flex items-center gap-2 text-xs font-semibold text-slate-400 uppercase tracking-wider mb-1">
          <a href="{{ route('wisatawan.index') }}" class="hover:text-slate-600 transition-colors">Data Wisatawan</a>
          <span>/</span>
          <span class="text-emerald-600">Edit Sesi</span>
        </div>
        <h1 class="text-2xl font-bold text-slate-900 tracking-tight">Edit Sesi Wisatawan</h1>
        <p class="text-xs text-slate-500">Perbarui data wisatawan, device, atau status sesi.</p>
      </div>

      <a href="{{ route('wisatawan.index') }}" class="px-4 py-2 bg-white text-slate-600 hover:bg-slate-50 border border-slate-200 rounded-lg text-xs font-semibold shadow-sm transition-all flex items-center gap-1.5">
        <i data-lucide="arrow-left" class="w-4 h-4"></i> Kembali
      </a>
    </div>

    <form method="POST" action="{{ route('wisatawan.update', $sesi) }}" class="w-full bg-white rounded-2xl p-6 border border-slate-100 shadow-sm space-y-5">
      @csrf
      @method('PUT')

      <div class="flex items-center gap-2 border-b border-slate-100 pb-3">
        <i data-lucide="pencil" class="w-5 h-5 text-emerald-600"></i>
        <h2 class="font-semibold text-slate-800 text-sm">Informasi Wisatawan & Sesi</h2>
      </div>

      <div class="space-y-4">
        <!-- Nama -->
        <div>
          <label for="nama" class="block text-xs font-semibold text-slate-600 mb-1">Nama Wisatawan <span class="text-red-500">*</span></label>
          <input type="text" id="nama" name="nama_wisatawan" required
                 value="{{ old('nama_wisatawan', $sesi->nama_wisatawan) }}"
                 class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-lg text-xs text-slate-800 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:bg-white transition-all">
          @error('nama_wisatawan') <p class="mt-1 text-xs text-rose-600">{{ $message }}</p> @enderror
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
          <!-- Resort -->
          <div>
            <label for="resort" class="block text-xs font-semibold text-slate-600 mb-1">Lokasi Resort <span class="text-red-500">*</span></label>
            <div class="relative">
              <select id="resort" name="lokasi_resort" required class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-lg text-xs text-slate-800 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:bg-white transition-all appearance-none pr-9">
                @foreach (['Ubud Hanging Gardens', 'Tegallalang Retreat', 'Sayan Jungle Resort', 'Kintamani Spa Haven', 'Ubud'] as $resort)
                  <option value="{{ $resort }}" @selected(old('lokasi_resort', $sesi->lokasi_resort) === $resort)>{{ $resort }}</option>
                @endforeach
              </select>
              <i data-lucide="chevron-down" class="w-4 h-4 text-slate-400 absolute right-3 top-1/2 -translate-y-1/2 pointer-events-none"></i>
            </div>
            @error('lokasi_resort') <p class="mt-1 text-xs text-rose-600">{{ $message }}</p> @enderror
          </div>

          <!-- Tanggal -->
          <div>
            <label for="tanggal" class="block text-xs font-semibold text-slate-600 mb-1">Tanggal Sesi <span class="text-red-500">*</span></label>
            <input type="date" id="tanggal" name="tanggal_sesi" required
                   value="{{ old('tanggal_sesi', $sesi->tanggal_sesi->format('Y-m-d')) }}"
                   class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-lg text-xs text-slate-800 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:bg-white transition-all">
            @error('tanggal_sesi') <p class="mt-1 text-xs text-rose-600">{{ $message }}</p> @enderror
          </div>
        </div>

        <!-- Device -->
<div>
  <label for="device" class="block text-xs font-semibold text-slate-600 mb-1">Device <span class="text-red-500">*</span></label>
  <div class="relative">
    <select id="device" name="device_id" required class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-lg text-xs text-slate-800 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:bg-white transition-all appearance-none pr-9">
      <option value="" disabled {{ old('device_id', $sesi->device_id) ? '' : 'selected' }}>Pilih Device</option>
      @foreach ($devices as $device)
        <option value="{{ $device->id }}"
                @disabled($device->sedang_dipakai)
                @selected(old('device_id', $sesi->device_id) == $device->id)>
          {{ $device->device_name }}{{ $device->sedang_dipakai ? ' (sedang dipakai)' : '' }}
        </option>
      @endforeach
    </select>
    <i data-lucide="chevron-down" class="w-4 h-4 text-slate-400 absolute right-3 top-1/2 -translate-y-1/2 pointer-events-none"></i>
  </div>
  @error('device_id') <p class="mt-1 text-xs text-rose-600">{{ $message }}</p> @enderror
</div>
      </div>

      <div class="pt-4 border-t border-slate-100 flex items-center justify-end gap-3">
        <a href="{{ route('wisatawan.index') }}" class="px-4 py-2.5 bg-white border border-slate-200 hover:bg-slate-50 text-slate-600 rounded-xl text-xs font-semibold transition-all">
          Batal
        </a>
        <button type="submit" class="px-5 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white rounded-xl text-xs font-semibold shadow-sm shadow-emerald-200 transition-all flex items-center gap-2">
          <i data-lucide="save" class="w-4 h-4"></i> Simpan Perubahan
        </button>
      </div>
    </form>
  </div>
</x-layout>
