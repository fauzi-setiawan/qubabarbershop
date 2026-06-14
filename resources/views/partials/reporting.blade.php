<div id="reportingPage" class="page-content flex flex-col gap-6 w-full max-w-5xl mx-auto">
    
    {{-- PROJECT SELECTION CONTAINER --}}
    <div id="projectSelectionContainer" class="w-full relative overflow-hidden bg-white p-6 rounded-2xl border border-slate-200/80 shadow-[0_1px_3px_rgba(0,0,0,0.04)]">
        {{-- Aksen Gradien Atas --}}
        <div class="absolute top-0 left-0 w-full h-1 bg-gradient-to-r from-indigo-500 via-purple-500 to-pink-500 rounded-t-2xl"></div>

        <div class="flex items-center gap-3 mb-6 border-b border-slate-100 pb-5">
            <div class="w-10 h-10 rounded-xl bg-indigo-50 flex items-center justify-center flex-shrink-0">
                <svg class="w-5 h-5 text-indigo-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 19v-6a2 2 0 00-2 2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path></svg>
            </div>
            <div>
                <h2 class="text-base font-bold text-slate-800 tracking-tight">Pilih Proyek Aktif</h2>
                <p class="text-xs text-slate-400 mt-0.5">Pilih salah satu proyek di bawah untuk memuat visualisasi dasbor & analisis metrik.</p>
            </div>
        </div>

        <div id="projectList" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            {{-- Dimasukkan secara dinamis oleh JavaScript --}}
        </div>
    </div>
    
    {{-- PROJECT DASHBOARD METRICS DETAIL --}}
    <div id="projectDashboardContainer" class="hidden flex flex-col gap-6 w-full">
        {{-- Header Atas Dashboard Proyek --}}
        <div class="bg-white p-6 rounded-2xl border border-slate-200/80 shadow-[0_1px_3px_rgba(0,0,0,0.04)] flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 flex-shrink-0 relative overflow-hidden">
            <div class="absolute top-0 left-0 w-full h-1 bg-gradient-to-r from-emerald-500 to-indigo-500"></div>

            <div class="space-y-1">
                <button id="backToProjects" 
                    class="group inline-flex items-center text-xs font-bold text-indigo-600 hover:text-indigo-800 transition-colors focus:outline-none mb-1">
                    <svg class="h-3.5 w-3.5 mr-1 transform transition-transform group-hover:-translate-x-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7" />
                    </svg>
                    Kembali ke Daftar Proyek
                </button>
                <h2 id="dashboardProjectName" class="text-lg font-bold text-slate-800 tracking-tight">Nama Proyek</h2>
            </div>

            <button id="downloadProjectReport" 
                class="w-full sm:w-auto justify-center inline-flex items-center gap-2 bg-gradient-to-r from-emerald-600 to-green-600 hover:from-emerald-700 hover:to-green-700 text-white text-sm font-semibold px-4.5 py-2.5 rounded-xl shadow-sm shadow-emerald-150 hover:shadow-md transition-all duration-300 active:scale-[0.98]">
                <svg class="h-4.5 w-4.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                </svg>
                Unduh Laporan PM
            </button>
        </div>

        {{-- Summary Metrics --}}
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-2">
            <div class="bg-white p-5 rounded-2xl border border-slate-200/80 shadow-[0_1px_3px_rgba(0,0,0,0.04)]">
                <p class="text-[11px] font-semibold text-slate-500 uppercase tracking-wider mb-1">Total Modul</p>
                <p id="metricTotalModul" class="text-3xl font-extrabold text-slate-800">0</p>
            </div>
            <div class="bg-white p-5 rounded-2xl border border-slate-200/80 shadow-[0_1px_3px_rgba(0,0,0,0.04)]">
                <p class="text-[11px] font-semibold text-slate-500 uppercase tracking-wider mb-1">Total Test Case</p>
                <p id="metricTotalTest" class="text-3xl font-extrabold text-slate-800">0</p>
            </div>
            <div class="bg-gradient-to-br from-emerald-50 to-green-50/50 p-5 rounded-2xl border border-emerald-200/60 shadow-[0_1px_3px_rgba(0,0,0,0.04)]">
                <p class="text-[11px] font-semibold text-emerald-600/80 uppercase tracking-wider mb-1">Pass Rate</p>
                <p id="metricPassRate" class="text-3xl font-extrabold text-emerald-700">0%</p>
            </div>
            <div class="bg-gradient-to-br from-red-50 to-rose-50/50 p-5 rounded-2xl border border-red-200/60 shadow-[0_1px_3px_rgba(0,0,0,0.04)]">
                <p class="text-[11px] font-semibold text-red-600/80 uppercase tracking-wider mb-1">Failed Cases</p>
                <p id="metricFailed" class="text-3xl font-extrabold text-red-700">0</p>
            </div>
        </div>

        {{-- Grid Grafik Visualisasi --}}
        <div class="grid grid-cols-1 lg:grid-cols-5 gap-6">
            {{-- Grafik Distribusi Status --}}
            <div class="bg-white p-6 rounded-2xl border border-slate-200/80 shadow-[0_1px_3px_rgba(0,0,0,0.04)] lg:col-span-2 h-96 flex flex-col">
                <div class="flex items-center gap-2 mb-4">
                    <div class="w-1.5 h-4 rounded-full bg-indigo-500"></div>
                    <h3 class="font-bold text-sm text-slate-700">Distribusi Status Uji</h3>
                </div>
                <div class="relative flex-1 min-h-0">
                    <canvas id="statusDoughnutChart"></canvas>
                </div>
            </div>

            {{-- Grafik Pencapaian per Modul --}}
            <div class="bg-white p-6 rounded-2xl border border-slate-200/80 shadow-[0_1px_3px_rgba(0,0,0,0.04)] lg:col-span-3 h-96 flex flex-col">
                <div class="flex items-center gap-2 mb-4">
                    <div class="w-1.5 h-4 rounded-full bg-emerald-500"></div>
                    <h3 class="font-bold text-sm text-slate-700">Pencapaian per Modul</h3>
                </div>
                <div class="relative flex-1 min-h-0">
                    <canvas id="moduleBarChart"></canvas>
                </div>
            </div>
        </div>
    </div>
</div>