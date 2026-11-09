<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>BaliSense - Login</title>
  <!-- Tailwind CSS CDN -->
  <script src="https://cdn.tailwindcss.com"></script>
  <!-- Google Fonts: Inter -->
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet" />
  <style>
    body {
      font-family: 'Inter', sans-serif;
    }
  </style>
</head>
<body>

  <!-- MAIN WRAPPER -->
  <div class="bg-[#f0f4f8] antialiased text-slate-800 min-h-screen flex flex-col lg:flex-row">

    <!-- LEFT PANEL (DESKTOP ONLY) -->
    <div class="hidden lg:flex lg:w-5/12 bg-[#064e3b] p-8 lg:p-12 flex-col justify-between relative overflow-hidden text-white">

      <!-- Top Branding -->
      <div class="flex items-start space-x-3 z-10">
        <div class="p-1.5 rounded-full border border-emerald-400/30 bg-emerald-800/30">
          <svg class="w-6 h-6 text-emerald-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z" />
          </svg>
        </div>
        <div>
          <h1 class="font-bold text-lg leading-tight tracking-wide">BaliSense</h1>
          <p class="text-[11px] text-emerald-200 uppercase tracking-widest font-medium">SMART WELLNESS</p>
        </div>
      </div>

      <!-- Main Feature Card -->
      <div class="z-10 bg-gradient-to-br from-[#0d9488]/70 to-[#047857]/80 backdrop-blur-md border border-emerald-400/20 rounded-3xl p-8 shadow-xl max-w-lg my-auto">
        <span class="text-xs font-semibold uppercase tracking-wider text-emerald-200 block mb-3">
          SMART WELLNESS TOURISM
        </span>
        <h2 class="text-2xl lg:text-3xl font-bold text-white leading-snug mb-4">
          Pemantauan Respons Relaksasi Wisatawan Berbasis IoT
        </h2>
        <p class="text-emerald-100/80 text-xs sm:text-sm">
          Platform Smart Wellness Tourism BaliSense.
        </p>
      </div>

      <!-- Bottom Sensor Info -->
      <div class="z-10 bg-emerald-900/40 border border-emerald-500/20 rounded-2xl p-4 max-w-lg backdrop-blur-sm">
        <p class="text-xs font-medium text-emerald-200 mb-1">Sensor Terintegrasi</p>
        <p class="text-xs text-emerald-300/70">
          Grove-GSR &bull; MAX30102 &bull; MAX30205 &bull; ESP32
        </p>
      </div>

    </div>

    <!-- RIGHT PANEL / MAIN CONTENT (DESKTOP & MOBILE) -->
    <div class="flex-1 flex flex-col justify-between p-6 sm:p-12 lg:p-16 bg-[#f0f4f8]">

      <!-- Spacer for Centering -->
      <div class="hidden sm:block"></div>

      <!-- Login Form Card -->
      <div class="w-full max-w-md mx-auto bg-white rounded-3xl p-8 sm:p-10 shadow-sm border border-slate-100 my-auto">

        <h2 class="text-2xl font-bold text-slate-900 mb-2">
          Welcome Back
        </h2>
        <p class="text-xs sm:text-sm text-slate-400 mb-8 leading-relaxed">
          Masuk untuk mengelola sesi dan memantau respons relaksasi wisatawan.
        </p>

        <form action="{{ route('login')}}" method="POST" class="space-y-5">
          @csrf
          <div>
            <label class="block text-xs font-medium text-slate-500 mb-2">
              Email atau Username
            </label>
            <input
              type="text"
              name="login"
              placeholder="Masukkan email atau username"
              class="w-full px-4 py-3 text-xs sm:text-sm rounded-xl border border-slate-200 focus:outline-none focus:border-emerald-500 focus:ring-1 focus:ring-emerald-500 placeholder:text-slate-300 text-slate-700 transition"
            />
          </div>

          <!-- Password Input -->
          <div>
            <label class="block text-xs font-medium text-slate-500 mb-2">
              Password
            </label>
            <div class="relative">
              <input
                type="password"
                name="password"
                placeholder="Masukkan password"
                class="w-full px-4 py-3 text-xs sm:text-sm rounded-xl border border-slate-200 focus:outline-none focus:border-emerald-500 focus:ring-1 focus:ring-emerald-500 placeholder:text-slate-300 text-slate-700 pr-10 transition"
              />
              <button type="button" class="absolute right-3 top-1/2 -translate-y-1/2 text-slate-400 hover:text-slate-600">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                </svg>
              </button>
            </div>
          </div>

          <div class="flex items-center justify-between text-xs pt-1">
            <label class="flex items-center space-x-2 cursor-pointer text-slate-500">
              <input type="checkbox" name="remember" class="w-4 h-4 rounded border-slate-300 text-emerald-600 focus:ring-emerald-500 accent-emerald-600" />
              <span>Remember Me</span>
            </label>
            <a href="#" class="font-semibold text-[#0d9488] hover:underline">
              Forgot Password
            </a>
          </div>

          <button
            type="submit"
            class="w-full mt-4 bg-[#059669] hover:bg-[#047857] text-white font-medium py-3 rounded-xl shadow-md transition duration-200 text-xs sm:text-sm"
          >
            Login
          </button>
        </form>

      </div>

      <div class="text-center text-[11px] text-slate-400 mt-8 leading-relaxed">
        <p>&copy; 2026 BaliSense &bull; Smart Wellness Tourism</p>
        <p>PBL TRPL Politeknik Negeri Bali</p>
      </div>

    </div>

  </div>

</body>
</html>
