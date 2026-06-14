<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>QA Dashboard - Terintegrasi & Modular</title>
    
    <!-- ================= SEKARANG MENGGUNAKAN VITE UNTUK TAILWIND LOKAL ================= -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <!-- Memuat Google Fonts: Inter -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <!-- Memuat Chart.js untuk visualisasi data -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        body { font-family: 'Inter', sans-serif; }
        ::-webkit-scrollbar { width: 8px; height: 8px; }
        ::-webkit-scrollbar-track { background: #f1f1f1; }
        ::-webkit-scrollbar-thumb { background: #a8a8a8; border-radius: 4px; }
        ::-webkit-scrollbar-thumb:hover { background: #555; }
        .modal-backdrop { transition: opacity 0.3s ease; }
        [contenteditable]:focus {
            outline: 2px solid #4F46E5;
            border-radius: 4px;
            box-shadow: 0 0 0 2px #c7d2fe;
        }
    </style>
</head>
<body class="bg-gray-100">

    <!-- 1. LAYAR LOGIN & DAFTAR (Tampil bergantian jika belum login) -->
    <div id="authContainer" class="min-h-screen flex items-center justify-center">
        @include('partials.login')
        @include('partials.register')
    </div>

    <!-- 2. LAYAR DASBOR UTAMA (Terproteksi, sembunyi secara default) -->
    <div id="mainDashboard" class="hidden h-screen overflow-hidden flex">
        
        <!-- Backdrop untuk Sidebar Mobile -->
        <div id="sidebarBackdrop" class="fixed inset-0 bg-black/40 backdrop-blur-xs z-30 hidden lg:hidden transition-opacity duration-300"></div>

        <!-- Sidebar Menu Navigasi -->
        @include('partials.sidebar')

        <!-- Area Konten Utama -->
        <div class="flex-1 lg:ml-64 flex flex-col h-full overflow-hidden w-full">
            <!-- Header Atas (User Profile & Logout) -->
            <header class="bg-white shadow-sm p-4 flex justify-between items-center z-10 flex-shrink-0">
                <div class="flex items-center gap-3">
                    <!-- Hamburger Menu Button (Mobile) -->
                    <button id="mobileMenuBtn" class="lg:hidden p-2 text-gray-600 hover:text-gray-900 hover:bg-gray-100 rounded-lg focus:outline-none" title="Buka Menu">
                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16" />
                        </svg>
                    </button>
                    <h1 id="pageTitle" class="text-xl font-bold text-gray-800">Dashboard Reporting</h1>
                </div>
                <div class="flex items-center space-x-4">
                    <span id="userDisplayName" class="text-sm text-gray-600 font-medium"></span>
                    <div id="userAvatar" class="h-8 w-8 rounded-full bg-indigo-600 text-white flex items-center justify-center font-bold text-sm"></div>
                    <button id="logoutButton" class="text-gray-500 hover:text-red-600" title="Keluar">
                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" /></svg>
                    </button>
                </div>
            </header>

            <!-- Wadah Konten Fitur Dinamis (SPA Switcher) -->
            <main class="flex-1 p-4 sm:p-6 overflow-y-auto">
                @include('partials.reporting')
                @include('partials.project')
                @include('partials.user')
                @include('partials.attachReportBug')
                @include('partials.monitoring')
                @include('partials.ai-generate')
                @include('partials.knowledge-base')
                @include('partials.testcase')
                @include('partials.automation')
            </main>
        </div>
    </div>

    <!-- 3. MODAL POPUP MODULAR -->
    @include('partials.report-bug')

    <!-- NOTIFIKASI TOAST GLOBAL -->
    <div id="globalToast" class="fixed bottom-5 right-5 bg-gray-800 text-white py-3 px-5 rounded-lg shadow-lg transform translate-y-20 opacity-0 transition-all duration-300 z-50"></div>

    <!-- PANGGIL FILE LOGIKA JAVASCRIPT EKSTERNAL -->
    <!-- <script type="module" src="{{ asset('js/dashboard.js') }}"></script> -->
</body>
</html>