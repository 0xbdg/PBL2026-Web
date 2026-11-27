<x-layout>
  <div class="w-full p-4 sm:p-6 lg:p-8 space-y-6">

    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
      <div>
        <div class="flex items-center gap-2 text-xs font-semibold text-slate-400 uppercase tracking-wider mb-1">
          <a href="{{ route('akun.index') }}" class="hover:text-slate-600 transition-colors">Data User</a>
          <span>/</span>
          <span class="text-emerald-600">Edit User</span>
        </div>
        <h1 class="text-2xl font-bold text-slate-900 tracking-tight">Edit User</h1>
        <p class="text-xs text-slate-500">Perbarui data akun, password, atau hak akses user.</p>
      </div>

      <a href="{{ route('akun.index') }}" class="px-4 py-2 bg-white text-slate-600 hover:bg-slate-50 border border-slate-200 rounded-lg text-xs font-semibold shadow-sm transition-all flex items-center gap-1.5">
        <i data-lucide="arrow-left" class="w-4 h-4"></i> Kembali
      </a>
    </div>

    @if (session('error'))
      <div class="px-3.5 py-2.5 bg-rose-50 border border-rose-200 rounded-lg text-xs text-rose-600">
        {{ session('error') }}
      </div>
    @endif

    <form method="POST" action="{{ route('akun.update', $user) }}" class="w-full bg-white rounded-2xl p-6 border border-slate-100 shadow-sm space-y-5">
      @csrf
      @method('PUT')

      <div class="flex items-center gap-2 border-b border-slate-100 pb-3">
        <i data-lucide="pencil" class="w-5 h-5 text-emerald-600"></i>
        <h2 class="font-semibold text-slate-800 text-sm">Informasi User</h2>
      </div>

      <div class="space-y-4">
        <!-- Nama -->
        <div>
          <label for="name" class="block text-xs font-semibold text-slate-600 mb-1">Nama <span class="text-red-500">*</span></label>
          <input type="text" id="name" name="name" required
                 value="{{ old('name', $user->name) }}"
                 class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-lg text-xs text-slate-800 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:bg-white transition-all">
          @error('name') <p class="mt-1 text-xs text-rose-600">{{ $message }}</p> @enderror
        </div>

        <!-- Email -->
        <div>
          <label for="email" class="block text-xs font-semibold text-slate-600 mb-1">Email <span class="text-red-500">*</span></label>
          <input type="email" id="email" name="email" required
                 value="{{ old('email', $user->email) }}"
                 class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-lg text-xs text-slate-800 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:bg-white transition-all">
          @error('email') <p class="mt-1 text-xs text-rose-600">{{ $message }}</p> @enderror
        </div>

        <!-- Password -->
        <div>
          <label for="password" class="block text-xs font-semibold text-slate-600 mb-1">Password Baru</label>
          <input type="password" id="password" name="password" autocomplete="new-password"
                 class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-lg text-xs text-slate-800 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:bg-white transition-all">
          <p class="mt-1 text-xs text-slate-400">Kosongkan jika tidak ingin mengganti password.</p>
          @error('password') <p class="mt-1 text-xs text-rose-600">{{ $message }}</p> @enderror
        </div>

        <!-- Konfirmasi Password -->
        <div>
          <label for="password_confirmation" class="block text-xs font-semibold text-slate-600 mb-1">Konfirmasi Password Baru</label>
          <input type="password" id="password_confirmation" name="password_confirmation" autocomplete="new-password"
                 class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-lg text-xs text-slate-800 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:bg-white transition-all">
        </div>

        <!-- Hak Akses -->
        <div>
          <input type="hidden" name="is_admin" value="0">
          <label for="is_admin" class="flex items-center gap-2 px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-lg text-xs font-semibold text-slate-600 cursor-pointer">
            <input type="checkbox" id="is_admin" name="is_admin" value="1" class="w-4 h-4 accent-emerald-600"
                   @checked(old('is_admin', $user->is_admin))>
            Jadikan admin
          </label>
          @error('is_admin') <p class="mt-1 text-xs text-rose-600">{{ $message }}</p> @enderror
        </div>
      </div>

      <div class="pt-4 border-t border-slate-100 flex items-center justify-end gap-3">
        <a href="{{ route('akun.index') }}" class="px-4 py-2.5 bg-white border border-slate-200 hover:bg-slate-50 text-slate-600 rounded-xl text-xs font-semibold transition-all">
          Batal
        </a>
        <button type="submit" class="px-5 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white rounded-xl text-xs font-semibold shadow-sm shadow-emerald-200 transition-all flex items-center gap-2">
          <i data-lucide="save" class="w-4 h-4"></i> Simpan Perubahan
        </button>
      </div>
    </form>
  </div>

  @push('scripts')
    <script>
      document.addEventListener('DOMContentLoaded', function () {
        if (window.lucide) lucide.createIcons();
      });
    </script>
  @endpush
</x-layout>
