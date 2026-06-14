<div id="monitoringPage" class="page-content hidden flex-col gap-6 w-full max-w-5xl mx-auto">

    {{-- ═══════════════════════════════════════════════════════════ --}}
    {{-- Bagian Atas: Filter & Statistik Panel                      --}}
    {{-- ═══════════════════════════════════════════════════════════ --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 flex-shrink-0">

        {{-- ─── Panel Filter ─── --}}
        <div class="monitoring-filter-panel relative overflow-hidden bg-gradient-to-br from-slate-50 to-white p-6 rounded-2xl border border-slate-200/80 shadow-[0_1px_3px_rgba(0,0,0,0.04)] flex flex-col justify-center">
            {{-- Decorative accent --}}
            <div class="absolute top-0 left-0 w-full h-1 bg-gradient-to-r from-indigo-500 via-purple-500 to-pink-500 rounded-t-2xl"></div>

            <div class="flex items-center gap-2 mb-5">
                <div class="w-8 h-8 rounded-lg bg-indigo-50 flex items-center justify-center">
                    <svg class="w-4 h-4 text-indigo-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"></path></svg>
                </div>
                <h4 class="text-sm font-bold text-slate-800 tracking-tight">Filter Berdasarkan</h4>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-1 gap-4">
                <div>
                    <label class="block text-[11px] font-semibold text-slate-500 uppercase tracking-wider mb-1.5">Project</label>
                    <div class="relative">
                        <select id="filterMonitorProject" class="w-full appearance-none bg-white border border-slate-200 rounded-xl px-4 py-2.5 text-sm text-slate-700 font-medium focus:outline-none focus:ring-2 focus:ring-indigo-500/30 focus:border-indigo-400 transition-all duration-200 cursor-pointer hover:border-slate-300">
                            <option value="all">Semua Project</option>
                        </select>
                        <div class="absolute right-3 top-1/2 -translate-y-1/2 pointer-events-none">
                            <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"></path></svg>
                        </div>
                    </div>
                </div>
                <div>
                    <label class="block text-[11px] font-semibold text-slate-500 uppercase tracking-wider mb-1.5">Modul</label>
                    <div class="relative">
                        <select id="filterMonitorModul" class="w-full appearance-none bg-white border border-slate-200 rounded-xl px-4 py-2.5 text-sm text-slate-700 font-medium focus:outline-none focus:ring-2 focus:ring-indigo-500/30 focus:border-indigo-400 transition-all duration-200 cursor-pointer hover:border-slate-300">
                            <option value="all">Semua Modul</option>
                        </select>
                        <div class="absolute right-3 top-1/2 -translate-y-1/2 pointer-events-none">
                            <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"></path></svg>
                        </div>
                    </div>
                </div>
                <div>
                    <label class="block text-[11px] font-semibold text-slate-500 uppercase tracking-wider mb-1.5">Status</label>
                    <div class="relative">
                        <select id="filterMonitorStatus" class="w-full appearance-none bg-white border border-slate-200 rounded-xl px-4 py-2.5 text-sm text-slate-700 font-medium focus:outline-none focus:ring-2 focus:ring-indigo-500/30 focus:border-indigo-400 transition-all duration-200 cursor-pointer hover:border-slate-300">
                            <option value="all">Semua Status</option>
                            <option value="Pass">Passed</option>
                            <option value="Fail">Fail</option>
                            <option value="Blocked">Blocked</option>
                            <option value="Not Executed">Not Execute</option>
                        </select>
                        <div class="absolute right-3 top-1/2 -translate-y-1/2 pointer-events-none">
                            <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"></path></svg>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- ─── Panel Statistik & Pie Chart ─── --}}
        <div class="bg-gradient-to-br from-white to-slate-50/50 p-6 rounded-2xl border border-slate-200/80 shadow-[0_1px_3px_rgba(0,0,0,0.04)] lg:col-span-2 flex flex-col sm:flex-row items-center justify-between gap-6">

            {{-- Angka Statistik (4 cards) --}}
            <div class="grid grid-cols-2 gap-4 flex-1 w-full">

                {{-- Pass --}}
                <div class="monitoring-stat-card group relative overflow-hidden bg-gradient-to-br from-emerald-50 to-green-50/50 border border-emerald-200/60 rounded-xl p-4 transition-all duration-300 hover:shadow-md hover:shadow-emerald-100 hover:-translate-y-0.5">
                    <div class="absolute top-0 right-0 w-16 h-16 bg-emerald-500/5 rounded-bl-[40px]"></div>
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-xl bg-emerald-500 flex items-center justify-center shadow-sm shadow-emerald-200">
                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"></path></svg>
                        </div>
                        <div>
                            <p class="text-[11px] font-semibold text-emerald-600/80 uppercase tracking-wider">Pass</p>
                            <p id="countPass" class="text-2xl font-extrabold text-slate-800 leading-none mt-0.5 tabular-nums">0</p>
                        </div>
                    </div>
                </div>

                {{-- Fail --}}
                <div class="monitoring-stat-card group relative overflow-hidden bg-gradient-to-br from-red-50 to-rose-50/50 border border-red-200/60 rounded-xl p-4 transition-all duration-300 hover:shadow-md hover:shadow-red-100 hover:-translate-y-0.5">
                    <div class="absolute top-0 right-0 w-16 h-16 bg-red-500/5 rounded-bl-[40px]"></div>
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-xl bg-red-500 flex items-center justify-center shadow-sm shadow-red-200">
                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"></path></svg>
                        </div>
                        <div>
                            <p class="text-[11px] font-semibold text-red-600/80 uppercase tracking-wider">Fail</p>
                            <p id="countFail" class="text-2xl font-extrabold text-slate-800 leading-none mt-0.5 tabular-nums">0</p>
                        </div>
                    </div>
                </div>

                {{-- Blocked --}}
                <div class="monitoring-stat-card group relative overflow-hidden bg-gradient-to-br from-slate-50 to-gray-50/50 border border-slate-200/60 rounded-xl p-4 transition-all duration-300 hover:shadow-md hover:shadow-slate-100 hover:-translate-y-0.5">
                    <div class="absolute top-0 right-0 w-16 h-16 bg-slate-500/5 rounded-bl-[40px]"></div>
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-xl bg-slate-700 flex items-center justify-center shadow-sm shadow-slate-300">
                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><circle cx="12" cy="12" r="9" stroke-width="2"></circle><path stroke-linecap="round" stroke-width="2" d="M7.5 16.5l9-9"></path></svg>
                        </div>
                        <div>
                            <p class="text-[11px] font-semibold text-slate-500 uppercase tracking-wider">Blocked</p>
                            <p id="countBlocked" class="text-2xl font-extrabold text-slate-800 leading-none mt-0.5 tabular-nums">0</p>
                        </div>
                    </div>
                </div>

                {{-- Not Execute --}}
                <div class="monitoring-stat-card group relative overflow-hidden bg-gradient-to-br from-amber-50 to-yellow-50/50 border border-amber-200/60 rounded-xl p-4 transition-all duration-300 hover:shadow-md hover:shadow-amber-100 hover:-translate-y-0.5">
                    <div class="absolute top-0 right-0 w-16 h-16 bg-amber-500/5 rounded-bl-[40px]"></div>
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-xl bg-amber-500 flex items-center justify-center shadow-sm shadow-amber-200">
                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><circle cx="12" cy="12" r="9" stroke-width="2"></circle><path stroke-linecap="square" stroke-width="3" d="M8 12h8"></path></svg>
                        </div>
                        <div>
                            <p class="text-[11px] font-semibold text-amber-600/80 uppercase tracking-wider">Not Execute</p>
                            <p id="countNotExecute" class="text-2xl font-extrabold text-slate-800 leading-none mt-0.5 tabular-nums">0</p>
                        </div>
                    </div>
                </div>

            </div>

            {{-- Area Pie Chart (Doughnut) --}}
            <div class="flex-shrink-0 flex flex-col items-center gap-3">
                <div class="relative w-40 h-40 flex items-center justify-center">
                    <canvas id="monitoringPieChart"></canvas>
                    {{-- Center label overlay --}}
                    <div id="pieChartCenter" class="absolute inset-0 flex flex-col items-center justify-center pointer-events-none">
                        <span id="pieTotalCount" class="text-3xl font-extrabold text-slate-800 leading-none">0</span>
                        <span class="text-[10px] font-semibold text-slate-400 uppercase tracking-widest mt-1">Total</span>
                    </div>
                </div>
                {{-- Mini legend --}}
                <div class="flex flex-wrap justify-center gap-x-3 gap-y-1">
                    <span class="flex items-center gap-1.5 text-[10px] font-semibold text-slate-500"><span class="w-2 h-2 rounded-full bg-emerald-500"></span>Pass</span>
                    <span class="flex items-center gap-1.5 text-[10px] font-semibold text-slate-500"><span class="w-2 h-2 rounded-full bg-red-500"></span>Fail</span>
                    <span class="flex items-center gap-1.5 text-[10px] font-semibold text-slate-500"><span class="w-2 h-2 rounded-full bg-slate-700"></span>Blocked</span>
                    <span class="flex items-center gap-1.5 text-[10px] font-semibold text-slate-500"><span class="w-2 h-2 rounded-full bg-amber-500"></span>Not Exec</span>
                </div>
            </div>
        </div>
    </div>

    {{-- ═══════════════════════════════════════════════════════════ --}}
    {{-- Bagian Tengah: Judul & Tombol Download                     --}}
    {{-- ═══════════════════════════════════════════════════════════ --}}
    <div class="bg-white p-4 px-6 rounded-2xl border border-slate-200/80 shadow-[0_1px_3px_rgba(0,0,0,0.04)] flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 flex-shrink-0">
        <div class="flex items-center gap-3">
            <div class="w-8 h-8 rounded-lg bg-slate-100 flex items-center justify-center">
                <svg class="w-4 h-4 text-slate-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path></svg>
            </div>
            <div>
                <h3 class="font-bold text-slate-800 text-sm">Detail Semua Testcase</h3>
                <p id="tableRowCount" class="text-[11px] text-slate-400 font-medium">Menampilkan 0 data</p>
            </div>
        </div>
        <button id="downloadMonitorReport" class="w-full sm:w-auto justify-center group relative overflow-hidden bg-gradient-to-r from-emerald-600 to-green-600 text-white text-sm font-semibold py-2.5 px-5 rounded-xl hover:from-emerald-700 hover:to-green-700 flex items-center gap-2 transition-all duration-300 shadow-sm shadow-emerald-200 hover:shadow-md hover:shadow-emerald-200 active:scale-[0.97]">
            <svg class="w-4 h-4 transition-transform duration-300 group-hover:translate-y-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
            Download Laporan
        </button>
    </div>

    {{-- ═══════════════════════════════════════════════════════════ --}}
    {{-- Bagian Bawah: Tabel Data Premium                           --}}
    {{-- ═══════════════════════════════════════════════════════════ --}}
    <div class="monitoring-table-wrapper bg-white rounded-2xl border border-slate-200/80 shadow-[0_1px_3px_rgba(0,0,0,0.04)] flex-1 flex flex-col overflow-hidden w-full">
        <div class="flex-1 overflow-auto w-full">
            <table class="min-w-full">
                <thead class="bg-gradient-to-r from-slate-50 to-slate-100/80 sticky top-0 z-10">
                    <tr>
                        <th class="px-5 py-4 text-center text-[11px] font-bold text-slate-500 uppercase tracking-wider w-20 border-b border-slate-200">
                            <span class="inline-flex items-center gap-1.5">
                                <svg class="w-3.5 h-3.5 text-slate-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M7 20l4-16m2 16l4-16M6 9h14M4 15h14"></path></svg>
                                ID
                            </span>
                        </th>
                        <th class="px-5 py-4 text-left text-[11px] font-bold text-slate-500 uppercase tracking-wider border-b border-slate-200">
                            <span class="inline-flex items-center gap-1.5">
                                <svg class="w-3.5 h-3.5 text-slate-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                                Nama Test Case
                            </span>
                        </th>
                        <th class="px-5 py-4 text-center text-[11px] font-bold text-slate-500 uppercase tracking-wider w-36 border-b border-slate-200">
                            <span class="inline-flex items-center gap-1.5">
                                <svg class="w-3.5 h-3.5 text-slate-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path></svg>
                                Modul
                            </span>
                        </th>
                        <th class="px-5 py-4 text-center text-[11px] font-bold text-slate-500 uppercase tracking-wider w-28 border-b border-slate-200">
                            <span class="inline-flex items-center gap-1.5">
                                <svg class="w-3.5 h-3.5 text-slate-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3 21v-4m0 0V5a2 2 0 012-2h6.5l1 1H21l-3 6 3 6h-8.5l-1-1H5a2 2 0 00-2 2zm9-13.5V9"></path></svg>
                                Priority
                            </span>
                        </th>
                        <th class="px-5 py-4 text-center text-[11px] font-bold text-slate-500 uppercase tracking-wider w-36 border-b border-slate-200">
                            <span class="inline-flex items-center gap-1.5">
                                <svg class="w-3.5 h-3.5 text-slate-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                                Tester
                            </span>
                        </th>
                        <th class="px-5 py-4 text-center text-[11px] font-bold text-slate-500 uppercase tracking-wider w-36 border-b border-slate-200">
                            <span class="inline-flex items-center gap-1.5">
                                <svg class="w-3.5 h-3.5 text-slate-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                Status
                            </span>
                        </th>
                        <th class="px-5 py-4 text-left text-[11px] font-bold text-slate-500 uppercase tracking-wider w-52 border-b border-slate-200">
                            <span class="inline-flex items-center gap-1.5">
                                <svg class="w-3.5 h-3.5 text-slate-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z"></path></svg>
                                Catatan
                            </span>
                        </th>
                    </tr>
                </thead>
                <tbody id="monitoringTableBody" class="divide-y divide-slate-100">
                    {{-- Data dimasukkan oleh JavaScript --}}
                </tbody>
            </table>
        </div>
    </div>

</div>

{{-- ═══════════════════════════════════════════════════════════ --}}
{{-- Scoped Monitoring Styles                                   --}}
{{-- ═══════════════════════════════════════════════════════════ --}}
<style>
    /* ── Stat card entrance animation ── */
    .monitoring-stat-card {
        animation: monitorFadeUp 0.5s cubic-bezier(0.16, 1, 0.3, 1) both;
    }
    .monitoring-stat-card:nth-child(1) { animation-delay: 0.05s; }
    .monitoring-stat-card:nth-child(2) { animation-delay: 0.10s; }
    .monitoring-stat-card:nth-child(3) { animation-delay: 0.15s; }
    .monitoring-stat-card:nth-child(4) { animation-delay: 0.20s; }

    @keyframes monitorFadeUp {
        from { opacity: 0; transform: translateY(12px); }
        to   { opacity: 1; transform: translateY(0); }
    }

    /* ── Table row hover state ── */
    .monitoring-table-wrapper tbody tr {
        transition: background-color 0.15s ease, box-shadow 0.15s ease;
    }
    .monitoring-table-wrapper tbody tr:hover {
        background-color: #f8fafc; /* slate-50 */
    }

    /* ── Zebra striping ── */
    .monitoring-table-wrapper tbody tr:nth-child(even) {
        background-color: #fafbfc;
    }
    .monitoring-table-wrapper tbody tr:nth-child(even):hover {
        background-color: #f1f5f9;
    }

    /* ── Status badge pulse for "Fail" ── */
    @keyframes gentlePulse {
        0%, 100% { box-shadow: 0 0 0 0 rgba(239, 68, 68, 0.2); }
        50%      { box-shadow: 0 0 0 4px rgba(239, 68, 68, 0); }
    }
    .status-badge-fail {
        animation: gentlePulse 2.5s ease-in-out infinite;
    }

    /* ── Smooth filter panel ── */
    .monitoring-filter-panel select {
        background-image: none;
    }

    /* ── Chart canvas ── */
    #monitoringPieChart {
        max-width: 160px !important;
        max-height: 160px !important;
    }
</style>