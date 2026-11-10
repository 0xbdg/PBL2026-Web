<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $title ?? 'Dashboard' }} | Smart Wellness Tourism</title>
    @vite('resources/css/app.css')
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
</head>
<body class="min-h-screen bg-slate-100 font-sans text-slate-800 antialiased">

    <x-sidebar />

    <div id="sidebar-backdrop" class="fixed inset-0 z-30 hidden bg-slate-900/50 lg:hidden"></div>

    <div class="flex min-h-screen flex-col lg:pl-64">
        <x-header />

        <main class="flex-1 space-y-6 p-4 sm:p-6 lg:p-8">
            @if (session('sukses'))
                <div class="rounded-2xl border border-emerald-200 bg-emerald-50 px-5 py-3 text-sm font-medium text-emerald-800">
                    {{ session('sukses') }}
                </div>
            @endif

            {{ $slot }}
        </main>

        <footer class="px-8 py-4 text-center text-xs text-slate-400">
            &copy; {{ date('Y') }} BaliSense &middot; Smart Wellness Tourism &middot; PBL TRPL Politeknik Negeri Bali
        </footer>
    </div>

    <script>
        const sidebar = document.getElementById('sidebar');
        const backdrop = document.getElementById('sidebar-backdrop');
        const toggleSidebar = () => {
            sidebar.classList.toggle('-translate-x-full');
            backdrop.classList.toggle('hidden');
        };
        document.getElementById('sidebar-toggle')?.addEventListener('click', toggleSidebar);
        backdrop.addEventListener('click', toggleSidebar);
        const url = "https://api.weatherapi.com/v1/forecast.json?key=478af3b381054c5298c04907261011&q=Ubud&days=3&aqi=no&alerts=yes";
        async function fetchWeather() {
            try {
                const response = await fetch(url);
                if (!response.ok) {
                    throw new Error(`HTTP error! Status: ${response.status}`);
                }

                const data = await response.json();

                const regionName = data.location.region;
                const cityName = data.location.name;
                let tempC = data.current.temp_c;
                const conditionText = data.current.condition.text;

                document.getElementById("region").textContent = regionName;
                document.getElementById("city").textContent = cityName;
                document.getElementById("temp").textContent = `${tempC} °C`;
                document.getElementById("condition").textContent = conditionText;

            } catch (error) {
                console.error("Terjadi kesalahan saat mengambil data cuaca:", error);
                document.getElementById("city").textContent = "Gagal memuat";
                document.getElementById("condition").textContent = "Periksa koneksi atau API Key";
            }
        }

        // Panggil fungsi saat halaman selesai dimuat
        fetchWeather();
        setInterval(fetchWeather, 10000);
    </script>

    @stack('scripts')
</body>
</html>
