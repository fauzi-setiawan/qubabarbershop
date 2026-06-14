<div id="reportPage" class="page-content hidden flex-col gap-6 w-full max-w-5xl mx-auto">

    {{-- ═══════════════════════════════════════════════════════════ --}}
    {{-- Header: Informasi Halaman & Action Buttons                 --}}
    {{-- ═══════════════════════════════════════════════════════════ --}}
    <div class="relative overflow-hidden bg-gradient-to-br from-white to-slate-50/50 p-6 rounded-2xl border border-slate-200/80 shadow-[0_1px_3px_rgba(0,0,0,0.04)] flex-shrink-0">
        {{-- Decorative accent --}}
        <div class="absolute top-0 left-0 w-full h-1 bg-gradient-to-r from-red-500 via-rose-500 to-pink-500 rounded-t-2xl"></div>
        {{-- Background decoration --}}
        <div class="absolute -right-8 -top-8 w-40 h-40 bg-gradient-to-br from-red-500/5 to-rose-500/5 rounded-full blur-2xl"></div>

        <div class="relative flex flex-col md:flex-row md:items-center md:justify-between gap-4">
            {{-- Kiri: Info --}}
            <div class="flex items-start gap-4">
                <div class="w-12 h-12 rounded-2xl bg-gradient-to-br from-red-500 to-rose-600 flex items-center justify-center shadow-lg shadow-red-200 flex-shrink-0">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z"/>
                    </svg>
                </div>
                <div>
                    <h3 class="text-lg font-bold text-slate-800 tracking-tight">
                        Integrasi & Pelaporan Bug Trello
                    </h3>
                    <p class="text-sm text-slate-500 mt-0.5">
                        Kelola dan lacak siklus hidup kegagalan pengujian secara real-time.
                    </p>
                    <div class="flex items-center gap-2 mt-2.5">
                        <span class="relative flex h-2.5 w-2.5">
                            <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-400 opacity-75"></span>
                            <span class="relative inline-flex rounded-full h-2.5 w-2.5 bg-emerald-500"></span>
                        </span>
                        <span class="text-[11px] font-semibold text-slate-400 uppercase tracking-wider">Terakhir Sinkronisasi:</span>
                        <span id="lastSyncTime" class="text-[11px] font-bold text-slate-600">Belum pernah disinkronisasi</span>
                    </div>
                </div>
            </div>

            {{-- Kanan: Action Buttons --}}
            <div class="flex flex-wrap sm:flex-nowrap items-center gap-3 w-full sm:w-auto flex-shrink-0">
                <button id="refreshBugBtn"
                    class="w-full sm:w-auto justify-center group inline-flex items-center gap-2 bg-white border border-slate-200 hover:border-slate-300 hover:bg-slate-50 text-slate-700 text-sm font-semibold px-4 py-2.5 rounded-xl shadow-sm transition-all duration-200 active:scale-[0.97]">
                    <svg class="h-4 w-4 text-slate-400 group-hover:text-slate-600 transition-colors" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0A8.003 8.003 0 015.64 15m13.779 0H15"/>
                    </svg>
                    Refresh
                </button>

                <button id="syncBugBtn"
                    class="w-full sm:w-auto justify-center group relative overflow-hidden inline-flex items-center gap-2 bg-gradient-to-r from-indigo-600 to-blue-600 hover:from-indigo-700 hover:to-blue-700 text-white text-sm font-semibold px-5 py-2.5 rounded-xl shadow-sm shadow-indigo-200 hover:shadow-md hover:shadow-indigo-200 transition-all duration-300 active:scale-[0.97]">
                    <svg class="h-4 w-4 transition-transform duration-500 group-hover:rotate-180" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M4 4v6h6M20 20v-6h-6M5.64 18.36A9 9 0 1020 12"/>
                    </svg>
                    Sinkronisasi Trello
                </button>
            </div>
        </div>
    </div>

    {{-- ═══════════════════════════════════════════════════════════ --}}
    {{-- Tab Navigation                                             --}}
    {{-- ═══════════════════════════════════════════════════════════ --}}
    <div class="bg-white rounded-2xl border border-slate-200/80 shadow-[0_1px_3px_rgba(0,0,0,0.04)] overflow-hidden flex-shrink-0 w-full">
        <div class="flex border-b border-slate-100 overflow-x-auto whitespace-nowrap scrollbar-none">
            <button id="tabQueueBtn" class="report-tab-btn relative flex items-center gap-2 py-3.5 px-4 sm:px-6 text-sm font-semibold text-slate-500 transition-all duration-200 focus:outline-none hover:text-slate-700 flex-shrink-0">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z"/>
                </svg>
                Antrean Bug
                <span id="queueBadge" class="inline-flex items-center justify-center min-w-[20px] h-5 px-1.5 rounded-full bg-red-100 text-red-700 text-[10px] font-bold">0</span>
            </button>
            <button id="tabArchiveBtn" class="report-tab-btn relative flex items-center gap-2 py-3.5 px-4 sm:px-6 text-sm font-semibold text-slate-500 transition-all duration-200 focus:outline-none hover:text-slate-700 flex-shrink-0">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                </svg>
                Arsip Bug Trello
                <span id="archiveBadge" class="inline-flex items-center justify-center min-w-[20px] h-5 px-1.5 rounded-full bg-slate-100 text-slate-600 text-[10px] font-bold">0</span>
            </button>
        </div>
    </div>

    {{-- ═══════════════════════════════════════════════════════════ --}}
    {{-- Tab 1: Antrean Bug (Failed Tests belum dilaporkan)         --}}
    {{-- ═══════════════════════════════════════════════════════════ --}}
    <div id="bugQueueSection" class="bug-table-wrapper bg-white rounded-2xl border border-slate-200/80 shadow-[0_1px_3px_rgba(0,0,0,0.04)] flex-1 flex flex-col overflow-hidden w-full">
        <div class="flex-1 overflow-auto w-full">
            <table class="min-w-full">
                <thead class="bg-gradient-to-r from-slate-50 to-slate-100/80 sticky top-0 z-10">
                    <tr>
                        <th class="px-6 py-4 text-center text-[11px] font-bold text-slate-500 uppercase tracking-wider w-20 border-b border-slate-200">
                            <span class="inline-flex items-center gap-1.5">
                                <svg class="w-3.5 h-3.5 text-slate-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M7 20l4-16m2 16l4-16M6 9h14M4 15h14"></path></svg>
                                No
                            </span>
                        </th>
                        <th class="px-6 py-4 text-left text-[11px] font-bold text-slate-500 uppercase tracking-wider border-b border-slate-200">
                            <span class="inline-flex items-center gap-1.5">
                                <svg class="w-3.5 h-3.5 text-slate-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z"></path></svg>
                                Nama Skenario
                            </span>
                        </th>
                        <th class="px-6 py-4 text-center text-[11px] font-bold text-slate-500 uppercase tracking-wider w-36 border-b border-slate-200">
                            <span class="inline-flex items-center gap-1.5">
                                <svg class="w-3.5 h-3.5 text-slate-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path></svg>
                                Modul
                            </span>
                        </th>
                        <th class="px-6 py-4 text-center text-[11px] font-bold text-slate-500 uppercase tracking-wider w-36 border-b border-slate-200">
                            <span class="inline-flex items-center gap-1.5">
                                <svg class="w-3.5 h-3.5 text-slate-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                                Tester
                            </span>
                        </th>
                        <th class="px-6 py-4 text-center text-[11px] font-bold text-slate-500 uppercase tracking-wider w-40 border-b border-slate-200">
                            <span class="inline-flex items-center gap-1.5">
                                <svg class="w-3.5 h-3.5 text-slate-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
                                Aksi
                            </span>
                        </th>
                    </tr>
                </thead>
                <tbody id="failedTestList" class="divide-y divide-slate-100"></tbody>
            </table>
        </div>
    </div>

    {{-- ═══════════════════════════════════════════════════════════ --}}
    {{-- Tab 2: Arsip Bug Trello (Sudah Dilaporkan)                 --}}
    {{-- ═══════════════════════════════════════════════════════════ --}}
    <div id="bugArchiveSection" class="hidden bug-table-wrapper bg-white rounded-2xl border border-slate-200/80 shadow-[0_1px_3px_rgba(0,0,0,0.04)] flex-1 flex flex-col overflow-hidden w-full">
        <div class="flex-1 overflow-auto w-full">
            <table class="min-w-full">
                <thead class="bg-gradient-to-r from-slate-50 to-slate-100/80 sticky top-0 z-10">
                    <tr>
                        <th class="px-6 py-4 text-center text-[11px] font-bold text-slate-500 uppercase tracking-wider w-20 border-b border-slate-200">
                            <span class="inline-flex items-center gap-1.5">
                                <svg class="w-3.5 h-3.5 text-slate-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M7 20l4-16m2 16l4-16M6 9h14M4 15h14"></path></svg>
                                No
                            </span>
                        </th>
                        <th class="px-6 py-4 text-left text-[11px] font-bold text-slate-500 uppercase tracking-wider border-b border-slate-200">
                            <span class="inline-flex items-center gap-1.5">
                                <svg class="w-3.5 h-3.5 text-slate-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z"></path></svg>
                                Judul Bug
                            </span>
                        </th>
                        <th class="px-6 py-4 text-center text-[11px] font-bold text-slate-500 uppercase tracking-wider w-28 border-b border-slate-200">
                            <span class="inline-flex items-center gap-1.5">
                                <svg class="w-3.5 h-3.5 text-slate-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3 21v-4m0 0V5a2 2 0 012-2h6.5l1 1H21l-3 6 3 6h-8.5l-1-1H5a2 2 0 00-2 2zm9-13.5V9"></path></svg>
                                Severity
                            </span>
                        </th>
                        <th class="px-6 py-4 text-center text-[11px] font-bold text-slate-500 uppercase tracking-wider w-36 border-b border-slate-200">
                            <span class="inline-flex items-center gap-1.5">
                                <svg class="w-3.5 h-3.5 text-slate-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                                Pelapor
                            </span>
                        </th>
                        <th class="px-6 py-4 text-center text-[11px] font-bold text-slate-500 uppercase tracking-wider w-32 border-b border-slate-200">
                            <span class="inline-flex items-center gap-1.5">
                                <svg class="w-3.5 h-3.5 text-slate-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                Status
                            </span>
                        </th>
                        <th class="px-6 py-4 text-center text-[11px] font-bold text-slate-500 uppercase tracking-wider w-36 border-b border-slate-200">
                            <span class="inline-flex items-center gap-1.5">
                                <svg class="w-3.5 h-3.5 text-slate-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1"></path></svg>
                                Trello Card
                            </span>
                        </th>
                    </tr>
                </thead>
                <tbody id="reportedBugList" class="divide-y divide-slate-100"></tbody>
            </table>
        </div>
    </div>

</div>

{{-- ═══════════════════════════════════════════════════════════ --}}
{{-- Scoped Report Bug Styles                                   --}}
{{-- ═══════════════════════════════════════════════════════════ --}}
<style>
    /* ── Active tab indicator ── */
    .report-tab-btn.active-tab {
        color: #dc2626;
        border-bottom: 2px solid #dc2626;
    }
    .report-tab-btn.active-tab svg {
        color: #dc2626;
    }

    /* ── Table row hover ── */
    .bug-table-wrapper tbody tr {
        transition: background-color 0.15s ease;
    }
    .bug-table-wrapper tbody tr:hover {
        background-color: #f8fafc;
    }
    .bug-table-wrapper tbody tr:nth-child(even) {
        background-color: #fafbfc;
    }
    .bug-table-wrapper tbody tr:nth-child(even):hover {
        background-color: #f1f5f9;
    }

    /* ── Sync button spinner ── */
    @keyframes syncSpin {
        to { transform: rotate(360deg); }
    }
    .sync-spinning svg:first-child {
        animation: syncSpin 0.8s linear infinite;
    }
</style>