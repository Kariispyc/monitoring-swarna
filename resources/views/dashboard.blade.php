<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>SWARNA - Smart Farming Warnasari</title>
    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Chart.js CDN -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="bg-slate-100 text-slate-800 font-sans pb-16">

<!-- Header Navigation -->
<header class="bg-emerald-700 text-white shadow-md sticky top-0 z-50">
    <div class="max-w-7xl mx-auto px-3 sm:px-4 py-2.5 flex justify-between items-center gap-2">
        
        <!-- Logo & Branding -->
        <div class="flex items-center space-x-2 sm:space-x-3 min-w-0">
            <img src="{{ asset('images/logo-swarna.jpg') }}" 
                 alt="Logo SWARNA" 
                 class="h-9 w-9 sm:h-10 sm:w-10 object-contain rounded-full bg-white p-0.5 shadow-sm shrink-0"
                 onerror="this.onerror=null; this.src='https://ui-avatars.com/api/?name=SWARNA&background=10b981&color=fff';">
            <div class="truncate">
                <h1 class="text-sm sm:text-xl font-bold tracking-wide leading-tight truncate">SWARNA</h1>
                <!-- Subtitle disembunyikan di HP (hidden sm:block) agar tidak makan tempat -->
                <p class="hidden sm:block text-[11px] text-emerald-200 truncate">Smart Farming Warnasari &bull; PPK Ormawa BEM Fakultas Saintek UMMI</p>
            </div>
        </div>

        <!-- Tombol Aksi & Navigasi -->
        <div class="flex items-center space-x-1.5 sm:space-x-2 shrink-0">
            <a href="#profil-tim" class="bg-emerald-800 hover:bg-emerald-900 text-white text-[11px] sm:text-xs font-semibold px-2.5 py-1.5 sm:px-3 sm:py-2 rounded-lg transition flex items-center">
                <i class="fa-solid fa-users mr-1"></i> Tim kami
            </a>

            @auth
                <!-- Khusus Operator -->
                <a href="{{ route('export.csv') }}" class="bg-emerald-600 hover:bg-emerald-500 border border-emerald-400 text-white text-[11px] sm:text-xs font-semibold px-2.5 py-1.5 sm:px-3 sm:py-2 rounded-lg flex items-center shadow-sm transition">
                    <i class="fa-solid fa-file-excel mr-1"></i>Export Data <span class="hidden xs:inline">Rekap</span>
                </a>
                <form action="{{ route('logout') }}" method="POST" class="inline">
                    @csrf
                    <button type="submit" class="bg-red-600 hover:bg-red-700 text-white text-[11px] sm:text-xs font-semibold px-2.5 py-1.5 sm:px-3 sm:py-2 rounded-lg transition flex items-center">
                        <i class="fa-solid fa-right-from-bracket sm:mr-1"></i> <span class="hidden sm:inline">Logout</span>
                    </button>
                </form>
            @else
                <!-- Khusus Guest -->
                <a href="{{ route('login') }}" class="bg-blue-600 hover:bg-blue-500 text-white text-[11px] sm:text-xs font-semibold px-2.5 py-1.5 sm:px-3 sm:py-2 rounded-lg shadow-sm transition flex items-center">
                    <i class="fa-solid fa-lock mr-1"></i> Login
                </a>
            @endauth
        </div>

    </div>
</header>

    <main class="max-w-7xl mx-auto px-4 mt-6 space-y-6">

        <!-- Baris Parameter Lingkungan & Air -->
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
            <!-- Suhu Air -->
            <div class="bg-white p-4 rounded-xl shadow-sm border border-slate-200 flex items-center justify-between">
                <div>
                    <div class="flex items-center space-x-2">
                        <p class="text-xs font-semibold text-slate-500 uppercase tracking-wider">Suhu Air</p>
                        <span id="badge_water_status" class="text-[10px] font-bold px-2 py-0.5 rounded bg-emerald-100 text-emerald-700">Optimal</span>
                    </div>
                    <h3 id="val_water_temp" class="text-2xl md:text-3xl font-extrabold text-blue-600 mt-1">0.0 °C</h3>
                </div>
                <div class="p-3 bg-blue-50 text-blue-600 rounded-xl text-xl">
                    <i class="fa-solid fa-temperature-three-quarters"></i>
                </div>
            </div>
            <!-- Suhu Udara -->
            <div class="bg-white p-4 rounded-xl shadow-sm border border-slate-200 flex items-center justify-between">
                <div>
                    <div class="flex items-center space-x-2">
                        <p class="text-xs font-semibold text-slate-500 uppercase tracking-wider">Suhu Udara</p>
                        <span id="badge_air_temp_status" class="text-[10px] font-bold px-2 py-0.5 rounded bg-emerald-100 text-emerald-700">Optimal</span>
                    </div>
                    <h3 id="val_air_temp" class="text-2xl md:text-3xl font-extrabold text-orange-500 mt-1">0.0 °C</h3>
                </div>
                <div class="p-3 bg-orange-50 text-orange-500 rounded-xl text-xl">
                    <i class="fa-solid fa-sun"></i>
                </div>
            </div>
            
            <!-- Kelembapan Udara -->
            <div class="bg-white p-4 rounded-xl shadow-sm border border-slate-200 flex items-center justify-between">
                <div>
                    <div class="flex items-center space-x-2">
                        <p class="text-xs font-semibold text-slate-500 uppercase tracking-wider">Kelembapan Udara</p>
                        <span id="badge_air_hum_status" class="text-[10px] font-bold px-2 py-0.5 rounded bg-emerald-100 text-emerald-700">Ideal</span>
                    </div>
                    <h3 id="val_air_humidity" class="text-2xl md:text-3xl font-extrabold text-teal-600 mt-1">0.0 %</h3>
                </div>
                <div class="p-3 bg-teal-50 text-teal-600 rounded-xl text-xl">
                    <i class="fa-solid fa-wind"></i>
                </div>
            </div>
        </div>

        <!-- Panel Kontrol Aktuator & Valve -->
        <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-5">
            <div class="flex flex-col sm:flex-row sm:items-center justify-between pb-4 border-b border-slate-100 gap-3">
                <div>
                    <h2 class="text-base md:text-lg font-bold text-slate-800">Kontrol Valve & Pompa</h2>
                    <p class="text-xs text-slate-500">Auto: Rata-rata &le;35% Buka, &ge;50% Tutup | Manual: Override Web</p>
                </div>
                
                @auth
                    <!-- Tombol Mode AUTO / MANUAL (Hanya Operator) -->
                    <div class="flex items-center space-x-2 bg-slate-100 p-1 rounded-lg self-start sm:self-auto">
                        <button id="btn_mode_auto" onclick="setMode('auto')" class="px-3 py-1 text-xs font-bold rounded-md transition shadow-sm bg-emerald-600 text-white">AUTO</button>
                        <button id="btn_mode_manual" onclick="setMode('manual')" class="px-3 py-1 text-xs font-bold rounded-md transition text-slate-600 hover:text-slate-900">MANUAL</button>
                    </div>
                @else
                    <!-- Tampilan Mode untuk Masyarakat Umum (Guest) -->
                    <div class="px-3 py-1 text-xs font-bold rounded-md bg-slate-100 text-slate-500 border border-slate-200">
                        🔒 Mode Publik (Ter-kunci)
                    </div>
                @endauth
            </div>

            <!-- Tombol Valve A - G -->
            <div class="mt-4">
                <p class="text-xs font-semibold text-slate-400 mb-2 uppercase tracking-wide">Katup Valve (Block A - G)</p>
                <div class="grid grid-cols-2 sm:grid-cols-4 lg:grid-cols-7 gap-2.5">
                    @foreach(['a','b','c','d','e','f','g'] as $block)
                    <div class="bg-slate-50 border border-slate-200 rounded-lg p-3 text-center flex flex-col justify-between">
                        <span class="text-xs font-bold text-slate-600 uppercase">Valve {{ strtoupper($block) }}</span>
                        <div class="my-2">
                            <span id="badge_valve_{{ $block }}" class="text-[10px] font-bold px-2 py-0.5 rounded-full bg-slate-200 text-slate-600">OFF</span>
                        </div>
                        
                        @auth
                            <!-- Sakelar Aktif untuk Operator -->
                            <button id="btn_valve_{{ $block }}" onclick="toggleDevice('valve_{{ $block }}')" class="w-full py-1 text-xs font-semibold rounded bg-slate-200 text-slate-700 hover:bg-slate-300 transition">
                                Switch
                            </button>
                        @else
                            <!-- Tampilan Saja untuk Guest -->
                            <button disabled class="w-full py-1 text-xs font-semibold rounded bg-slate-100 text-slate-400 cursor-not-allowed">
                                Read Only
                            </button>
                        @endauth
                    </div>
                    @endforeach
                </div>
            </div>

            <!-- Tombol Pompa -->
            <div class="mt-4 pt-4 border-t border-slate-100">
                <p class="text-xs font-semibold text-slate-400 mb-2 uppercase tracking-wide">Pompa Utama</p>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                    <div class="bg-slate-50 border border-slate-200 rounded-lg p-3 flex items-center justify-between">
                        <div class="flex items-center space-x-3">
                            <div class="p-2 bg-blue-100 text-blue-600 rounded-lg text-lg"><i class="fa-solid fa-faucet-drip"></i></div>
                            <div>
                                <h4 class="text-sm font-bold text-slate-700">Pompa Air Utama</h4>
                                <span id="badge_pump_water" class="text-[10px] font-bold px-2 py-0.5 rounded-full bg-slate-200 text-slate-600">OFF</span>
                            </div>
                        </div>
                        @auth
                            <button id="btn_pump_water" onclick="toggleDevice('pump_water')" class="px-4 py-1.5 text-xs font-semibold rounded bg-slate-200 text-slate-700 hover:bg-slate-300 transition">Switch</button>
                        @else
                            <button disabled class="px-4 py-1.5 text-xs font-semibold rounded bg-slate-100 text-slate-400 cursor-not-allowed">Read Only</button>
                        @endauth
                    </div>

                    <div class="bg-slate-50 border border-slate-200 rounded-lg p-3 flex items-center justify-between">
                        <div class="flex items-center space-x-3">
                            <div class="p-2 bg-purple-100 text-purple-600 rounded-lg text-lg"><i class="fa-solid fa-flask"></i></div>
                            <div>
                                <h4 class="text-sm font-bold text-slate-700">Pompa Pupuk Cair</h4>
                                <span id="badge_pump_fertilizer" class="text-[10px] font-bold px-2 py-0.5 rounded-full bg-slate-200 text-slate-600">OFF</span>
                            </div>
                        </div>
                        @auth
                            <button id="btn_pump_fertilizer" onclick="toggleDevice('pump_fertilizer')" class="px-4 py-1.5 text-xs font-semibold rounded bg-slate-200 text-slate-700 hover:bg-slate-300 transition">Switch</button>
                        @else
                            <button disabled class="px-4 py-1.5 text-xs font-semibold rounded bg-slate-100 text-slate-400 cursor-not-allowed">Read Only</button>
                        @endauth
                    </div>
                </div>
            </div>
        </div>

        <!-- Monitoring Kelembapan Tanah 7 Block -->
        <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-5">
            <h2 class="text-base md:text-lg font-bold text-slate-800 mb-3">Kelembapan Tanah Tiap Block (14 Sensor)</h2>
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3">
                @foreach(['a','b','c','d','e','f','g'] as $b)
                <div class="border border-slate-200 bg-slate-50/50 rounded-lg p-3.5 flex flex-col justify-between">
                    <div class="flex justify-between items-center pb-2 border-b border-slate-200">
                        <span class="text-xs font-extrabold text-emerald-800">BLOCK {{ strtoupper($b) }}</span>
                        <span id="badge_stat_{{ $b }}" class="text-[10px] font-bold px-2 py-0.5 rounded bg-amber-100 text-amber-700">Normal</span>
                    </div>
                    <div class="my-2 flex justify-between items-end">
                        <div>
                            <span class="text-[11px] text-slate-500">Rata-rata:</span>
                            <h4 id="avg_{{ $b }}" class="text-xl font-black text-slate-800">0%</h4>
                        </div>
                        <div class="text-right text-[11px] text-slate-500">
                            <div>{{ strtoupper($b) }}1: <span id="soil_{{ $b }}1" class="font-bold text-slate-700">0%</span></div>
                            <div>{{ strtoupper($b) }}2: <span id="soil_{{ $b }}2" class="font-bold text-slate-700">0%</span></div>
                        </div>
                    </div>
                    <div class="w-full bg-slate-200 rounded-full h-1.5 overflow-hidden">
                        <div id="bar_{{ $b }}" class="bg-emerald-500 h-1.5 rounded-full transition-all duration-500" style="width: 0%"></div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>

        <!-- Grafik Realtime Database -->
        <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-5">
            <h2 class="text-base md:text-lg font-bold text-slate-800 mb-4">Grafik Tren Real-time</h2>
            <div class="h-64 md:h-80 w-full">
                <canvas id="sensorChart"></canvas>
            </div>
        </div>

        <!-- SECTION PROFIL TIM PPK ORMAWA -->
        <section id="profil-tim" class="scroll-mt-20 bg-white rounded-xl shadow-sm border border-slate-200 p-6 space-y-6">
            <!-- Header Profil -->
            <div class="text-center max-w-3xl mx-auto space-y-2">
                <span class="text-xs font-bold uppercase tracking-wider text-emerald-700 bg-emerald-50 px-3 py-1 rounded-full border border-emerald-200">
                    Program Penguatan Kapasitas Organisasi Mahasiswa
                </span>
                <h2 class="text-xl md:text-2xl font-black text-slate-800">Tim Pelaksana SWARNA</h2>
                <p class="text-xs md:text-sm text-slate-600 leading-relaxed">
                    Kami adalah tim pelaksana yang berhasil meraih pendanaan Program Penguatan Kapasitas Organisasi Ormawa melalui “INOVASI DESA SMART FARMING: INTEGRASI GREENHOUSE CERDAS BERBASIS IoT DAN PELATIHAN KOMPOS DI DESA WARNASARI”
                </p>
            </div>

            <!-- Banner Foto Bersama Tim -->
            <div class="rounded-xl overflow-hidden shadow-sm border border-slate-200 bg-slate-100">
                <img src="{{ asset('images/foto-tim-bersama.jpg') }}" 
                     alt="Foto Bersama Tim PPK Ormawa SWARNA" 
                     class="w-full h-48 md:h-80 object-cover"
                     onerror="this.onerror=null; this.src='https://placehold.co/1200x500/047857/ffffff?text=Foto+Bersama+Tim+PPK+Ormawa+SWARNA';">
            </div>

            <!-- Grid 12 Anggota Tim -->
            <div>
                <h3 class="text-sm font-bold text-slate-700 uppercase tracking-wide mb-4 text-center">Struktur Anggota Tim</h3>
                <div class="flex flex-wrap justify-center gap-4">
                    @foreach($members as $index => $m)
                    <div class="w-[calc(50%-0.5rem)] sm:w-[calc(33.333%-0.75rem)] md:w-44 bg-slate-50 border border-slate-200 rounded-xl p-3 text-center flex flex-col items-center hover:shadow-md transition">
                        <!-- Foto Anggota -->
                        <div class="w-16 h-16 md:w-20 md:h-20 rounded-full overflow-hidden mb-2.5 border-2 border-emerald-600 bg-slate-200">
                            <img src="{{ asset('images/' . $m['foto']) }}" 
                                 alt="{{ $m['nama'] }}" 
                                 class="w-full h-full object-cover"
                                 onerror="this.onerror=null; this.src='https://ui-avatars.com/api/?name={{ urlencode($m['nama']) }}&background=047857&color=fff';">
                        </div>
                        <h4 class="text-xs font-bold text-slate-800 line-clamp-1">{{ $m['nama'] }}</h4>
                        <p class="text-[10px] text-emerald-700 font-semibold mt-0.5">{{ $m['jabatan'] }}</p>
                        <p class="text-[9px] text-slate-500 mt-0.5">{{ $m['jurusan'] }}</p>
                    </div>
                    @endforeach
                </div>
            </div>
        </section>

    </main>

    <!-- Script AJAX Polling & Chart -->
    <script>
        let currentControls = {};
        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

        const ctx = document.getElementById('sensorChart').getContext('2d');
        const sensorChart = new Chart(ctx, {
            type: 'line',
            data: {
                labels: [],
                datasets: [
                    { label: 'Rata-rata Tanah A (%)', data: [], borderColor: '#10b981', tension: 0.3, fill: false },
                    { label: 'Rata-rata Tanah B (%)', data: [], borderColor: '#06b6d4', tension: 0.3, fill: false },
                    { label: 'Suhu Air (°C)', data: [], borderColor: '#3b82f6', tension: 0.3, fill: false },
                    { label: 'Kelembapan Udara (%)', data: [], borderColor: '#8b5cf6', tension: 0.3, fill: false }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    y: { beginAtZero: true, max: 100 }
                }
            }
        });

        async function fetchLiveData() {
            try {
                const res = await fetch('{{ route("live.data") }}');
                const data = await res.json();

                if (data.latest) {
                    // Update Parameter & Status Suhu Air
                    const waterTemp = data.latest.water_temp || 0;
                    document.getElementById('val_water_temp').innerText = waterTemp + ' °C';

                    const waterBadge = document.getElementById('badge_water_status');
                    if (waterBadge) {
                        if (waterTemp < 20) {
                            waterBadge.className = 'text-[10px] font-bold px-2 py-0.5 rounded bg-blue-100 text-blue-700';
                            waterBadge.innerText = 'Dingin';
                        } else if (waterTemp >= 20 && waterTemp <= 28) {
                            waterBadge.className = 'text-[10px] font-bold px-2 py-0.5 rounded bg-emerald-100 text-emerald-700';
                            waterBadge.innerText = 'Optimal';
                        } else if (waterTemp > 28 && waterTemp <= 32) {
                            waterBadge.className = 'text-[10px] font-bold px-2 py-0.5 rounded bg-amber-100 text-amber-700';
                            waterBadge.innerText = 'Hangat';
                        } else {
                            waterBadge.className = 'text-[10px] font-bold px-2 py-0.5 rounded bg-red-100 text-red-700';
                            waterBadge.innerText = 'Panas';
                        }
                    }

                    // Update Parameter & Status Suhu Udara
                    const airTemp = data.latest.air_temp || 0;
                    document.getElementById('val_air_temp').innerText = airTemp + ' °C';
                                    
                    const airTempBadge = document.getElementById('badge_air_temp_status');
                    if (airTempBadge) {
                        if (airTemp < 20) {
                            airTempBadge.className = 'text-[10px] font-bold px-2 py-0.5 rounded bg-blue-100 text-blue-700';
                            airTempBadge.innerText = 'Dingin';
                        } else if (airTemp >= 20 && airTemp <= 30) {
                            airTempBadge.className = 'text-[10px] font-bold px-2 py-0.5 rounded bg-emerald-100 text-emerald-700';
                            airTempBadge.innerText = 'Optimal';
                        } else if (airTemp > 30 && airTemp <= 34) {
                            airTempBadge.className = 'text-[10px] font-bold px-2 py-0.5 rounded bg-amber-100 text-amber-700';
                            airTempBadge.innerText = 'Hangat';
                        } else {
                            airTempBadge.className = 'text-[10px] font-bold px-2 py-0.5 rounded bg-red-100 text-red-700';
                            airTempBadge.innerText = 'Panas';
                        }
                    }
                    
                    // Update Parameter & Status Kelembapan Udara
                    const airHum = data.latest.air_humidity || 0;
                    document.getElementById('val_air_humidity').innerText = airHum + ' %';
                    
                    const airHumBadge = document.getElementById('badge_air_hum_status');
                    if (airHumBadge) {
                        if (airHum < 50) {
                            airHumBadge.className = 'text-[10px] font-bold px-2 py-0.5 rounded bg-red-100 text-red-700';
                            airHumBadge.innerText = 'Sangat Kering';
                        } else if (airHum >= 50 && airHum < 60) {
                            airHumBadge.className = 'text-[10px] font-bold px-2 py-0.5 rounded bg-amber-100 text-amber-700';
                            airHumBadge.innerText = 'Kering';
                        } else if (airHum >= 60 && airHum <= 80) {
                            airHumBadge.className = 'text-[10px] font-bold px-2 py-0.5 rounded bg-emerald-100 text-emerald-700';
                            airHumBadge.innerText = 'Ideal';
                        } else {
                            airHumBadge.className = 'text-[10px] font-bold px-2 py-0.5 rounded bg-blue-100 text-blue-700';
                            airHumBadge.innerText = 'Terlalu Lembap';
                        }
                    }

                    ['a','b','c','d','e','f','g'].forEach(b => {
                        const avg = data.latest['avg_' + b] || 0;
                        document.getElementById('avg_' + b).innerText = avg + '%';
                        document.getElementById('soil_' + b + '1').innerText = (data.latest['soil_' + b + '1'] || 0) + '%';
                        document.getElementById('soil_' + b + '2').innerText = (data.latest['soil_' + b + '2'] || 0) + '%';
                        document.getElementById('bar_' + b).style.width = Math.min(avg, 100) + '%';

                        const badge = document.getElementById('badge_stat_' + b);
                        if (avg <= 35) {
                            badge.className = 'text-[10px] font-bold px-2 py-0.5 rounded bg-red-100 text-red-700';
                            badge.innerText = 'Kering';
                        } else if (avg >= 50) {
                            badge.className = 'text-[10px] font-bold px-2 py-0.5 rounded bg-emerald-100 text-emerald-700';
                            badge.innerText = 'Cukup';
                        } else {
                            badge.className = 'text-[10px] font-bold px-2 py-0.5 rounded bg-amber-100 text-amber-700';
                            badge.innerText = 'Sedang';
                        }
                    });
                }

                if (data.control) {
                    currentControls = data.control;
                    updateControlUI(data.control);
                }

                if (data.history && data.history.length > 0) {
                    sensorChart.data.labels = data.history.map(item => new Date(item.created_at).toLocaleTimeString());
                    sensorChart.data.datasets[0].data = data.history.map(item => item.avg_a);
                    sensorChart.data.datasets[1].data = data.history.map(item => item.avg_b);
                    sensorChart.data.datasets[2].data = data.history.map(item => item.water_temp);
                    sensorChart.data.datasets[3].data = data.history.map(item => item.air_humidity);
                    sensorChart.update();
                }
            } catch (err) {
                console.error("Gagal mengambil data live:", err);
            }
        }

        function updateControlUI(control) {
            const btnAuto = document.getElementById('btn_mode_auto');
            const btnManual = document.getElementById('btn_mode_manual');
            
            if (btnAuto && btnManual) {
                if (control.mode === 'auto') {
                    btnAuto.className = 'px-3 py-1 text-xs font-bold rounded-md transition shadow-sm bg-emerald-600 text-white';
                    btnManual.className = 'px-3 py-1 text-xs font-bold rounded-md transition text-slate-600 hover:text-slate-900';
                } else {
                    btnManual.className = 'px-3 py-1 text-xs font-bold rounded-md transition shadow-sm bg-emerald-600 text-white';
                    btnAuto.className = 'px-3 py-1 text-xs font-bold rounded-md transition text-slate-600 hover:text-slate-900';
                }
            }

            ['a','b','c','d','e','f','g'].forEach(b => {
                const isOn = control['valve_' + b];
                const badge = document.getElementById('badge_valve_' + b);
                const btn = document.getElementById('btn_valve_' + b);

                if (isOn) {
                    badge.className = 'text-[10px] font-bold px-2 py-0.5 rounded-full bg-emerald-100 text-emerald-700';
                    badge.innerText = 'ON / BUKA';
                    if (btn) btn.className = 'w-full py-1 text-xs font-semibold rounded bg-red-500 text-white hover:bg-red-600 transition';
                } else {
                    badge.className = 'text-[10px] font-bold px-2 py-0.5 rounded-full bg-slate-200 text-slate-600';
                    badge.innerText = 'OFF / TUTUP';
                    if (btn) btn.className = 'w-full py-1 text-xs font-semibold rounded bg-emerald-600 text-white hover:bg-emerald-700 transition';
                }
            });

            ['pump_water', 'pump_fertilizer'].forEach(p => {
                const isOn = control[p];
                const badge = document.getElementById('badge_' + p);
                const btn = document.getElementById('btn_' + p);

                if (isOn) {
                    badge.className = 'text-[10px] font-bold px-2 py-0.5 rounded-full bg-emerald-100 text-emerald-700';
                    badge.innerText = 'AKTIF (ON)';
                    if (btn) btn.className = 'px-4 py-1.5 text-xs font-semibold rounded bg-red-500 text-white hover:bg-red-600 transition';
                } else {
                    badge.className = 'text-[10px] font-bold px-2 py-0.5 rounded-full bg-slate-200 text-slate-600';
                    badge.innerText = 'MATI (OFF)';
                    if (btn) btn.className = 'px-4 py-1.5 text-xs font-semibold rounded bg-emerald-600 text-white hover:bg-emerald-700 transition';
                }
            });
        }

        async function setMode(mode) {
            await fetch('/api/controls/update', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrfToken },
                body: JSON.stringify({ mode: mode })
            });
            fetchLiveData();
        }

        async function toggleDevice(field) {
            const currentVal = currentControls[field] || false;
            const payload = {};
            payload[field] = !currentVal;

            await fetch('/api/controls/update', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrfToken },
                body: JSON.stringify(payload)
            });
            fetchLiveData();
        }

        fetchLiveData();
        setInterval(fetchLiveData, 3000);
    </script>
</body>
</html>