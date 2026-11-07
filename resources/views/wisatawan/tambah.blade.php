<x-layout>
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
            <input type="text" id="nama" name="nama_wisatawan" required placeholder="Contoh: Kenji Tanaka" class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-lg text-xs text-slate-800 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:bg-white transition-all">
            @error('nama_wisatawan') <p class="mt-1 text-xs text-rose-600">{{ $message }}</p> @enderror
        </div>

        <!-- Grid 2 Column for Resort & Date (Responsive Full Width) -->
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
          <!-- Lokasi Resort -->
          <div>
            <label for="resort" class="block text-xs font-semibold text-slate-600 mb-1">Lokasi Resort <span class="text-red-500">*</span></label>
            <div class="relative">
              <select id="resort" name="lokasi_resort" required class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-lg text-xs text-slate-800 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:bg-white transition-all appearance-none pr-9">
                <option value="" disabled selected>Pilih Lokasi Resort</option>
                <option value="Ubud Hanging Gardens">Ubud Hanging Gardens</option>
                <option value="Tegallalang Retreat">Tegallalang Retreat</option>
                <option value="Sayan Jungle Resort">Sayan Jungle Resort</option>
                <option value="Kintamani Spa Haven">Kintamani Spa Haven</option>
                <option value="Ubud">Ubud</option>
              </select>
              <i data-lucide="chevron-down" class="w-4 h-4 text-slate-400 absolute right-3 top-1/2 -translate-y-1/2 pointer-events-none"></i>
            </div>
          </div>

          <!-- Tanggal Sesi -->
          <div>
            <label for="tanggal" class="block text-xs font-semibold text-slate-600 mb-1">Tanggal Sesi <span class="text-red-500">*</span></label>
            <input type="date" id="tanggal" name="tanggal_sesi" required class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-lg text-xs text-slate-800 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:bg-white transition-all">
            @error('tanggal_sesi') <p class="mt-1 text-xs text-rose-600">{{ $message }}</p> @enderror

          </div>
        </div>

        <!-- Catatan Opsional (Full Width) -->
        <div>
          <label for="catatan" class="block text-xs font-semibold text-slate-600 mb-1">Catatan Tambahan <span class="text-slate-400 font-normal">(Opsional)</span></label>
          <textarea id="catatan" name="catatan" rows="4" placeholder="Masukkan catatan awal untuk sesi ini jika ada..." class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-lg text-xs text-slate-800 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:bg-white transition-all"></textarea>
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
</x-layout>
