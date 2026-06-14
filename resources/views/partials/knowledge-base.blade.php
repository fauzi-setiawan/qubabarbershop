<div id="knowledgeBasePage" class="page-content hidden flex-col gap-6 w-full max-w-5xl mx-auto">
    
    {{-- Aksen/Header Section --}}
    <div class="relative overflow-hidden w-full bg-white p-6 rounded-2xl border border-slate-200/80 shadow-[0_1px_3px_rgba(0,0,0,0.04)] flex-shrink-0">
        <div class="absolute top-0 left-0 w-full h-1 bg-gradient-to-r from-violet-500 via-fuchsia-500 to-pink-500 rounded-t-2xl"></div>
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
            <div class="flex items-start gap-4">
                <div class="w-12 h-12 rounded-2xl bg-gradient-to-br from-violet-500 to-fuchsia-600 flex items-center justify-center shadow-lg shadow-violet-150 flex-shrink-0">
                    <svg class="w-6 h-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                    </svg>
                </div>
                <div>
                    <h3 class="text-lg font-bold text-slate-800 tracking-tight">Knowledge Base & RAG Documents</h3>
                    <p class="text-sm text-slate-500 mt-0.5">Kelola dokumen spesifikasi (URS/FSD) untuk memperkaya konteks pembuatan test case berbasis AI (Retrieval-Augmented Generation).</p>
                </div>
            </div>
            
            <div class="flex items-center gap-3 w-full md:6">
                <input type="file" id="kbUploadInput" accept=".md,.docx" class="hidden">
                <button onclick="document.getElementById('kbUploadInput').click()" 
                    class="flex-1 md:flex-none justify-center inline-flex items-center gap-2 bg-gradient-to-r from-violet-600 to-fuchsia-600 hover:from-violet-700 hover:to-fuchsia-700 text-white text-sm font-semibold px-4.5 py-2.5 rounded-xl shadow-sm transition-all duration-300 active:scale-[0.98]">
                    <svg class="w-4.5 h-4.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12" /></svg>
                    Upload Dokumen
                </button>
                <!-- <button id="kbClearCacheBtn"
                    class="flex-1 md:flex-none justify-center inline-flex items-center gap-2 bg-white border border-slate-200 hover:border-slate-300 hover:bg-slate-50 text-slate-700 text-sm font-semibold px-4.5 py-2.5 rounded-xl shadow-sm transition-all duration-200 active:scale-[0.97]">
                    <svg class="w-4 h-4 text-slate-400 group-hover:text-slate-600 transition-colors" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                    </svg>
                    Bersihkan Cache RAG
                </button> -->
            </div>
        </div>
    </div>

    {{-- Stats Widgets --}}
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 w-full">
        <div class="bg-white p-5 rounded-2xl border border-slate-200/85 shadow-sm flex items-center justify-between">
            <div>
                <p class="text-xs font-semibold text-slate-400 uppercase tracking-wider">Total Dokumen</p>
                <p id="kbTotalDocs" class="text-2xl font-bold text-slate-800 mt-1">0</p>
            </div>
            <div class="w-12 h-12 rounded-xl bg-violet-50 flex items-center justify-center text-violet-600 flex-shrink-0 shadow-sm shadow-violet-100">
                <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                </svg>
            </div>
        </div>
        <div class="bg-white p-5 rounded-2xl border border-slate-200/85 shadow-sm flex items-center justify-between">
            <div>
                <p class="text-xs font-semibold text-slate-400 uppercase tracking-wider">Total Chunks</p>
                <p id="kbTotalChunks" class="text-2xl font-bold text-slate-800 mt-1">0</p>
            </div>
            <div class="w-12 h-12 rounded-xl bg-fuchsia-50 flex items-center justify-center text-fuchsia-600 flex-shrink-0 shadow-sm shadow-fuchsia-100">
                <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z" />
                </svg>
            </div>
        </div>
        <div class="bg-white p-5 rounded-2xl border border-slate-200/85 shadow-sm flex items-center justify-between">
            <div>
                <p class="text-xs font-semibold text-slate-400 uppercase tracking-wider">Dokumen Markdown</p>
                <p id="kbMdCount" class="text-2xl font-bold text-slate-800 mt-1">0</p>
            </div>
            <div class="w-12 h-12 rounded-xl bg-emerald-50 flex items-center justify-center text-emerald-600 flex-shrink-0 shadow-sm shadow-emerald-100">
                <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                </svg>
            </div>
        </div>
        <div class="bg-white p-5 rounded-2xl border border-slate-200/85 shadow-sm flex items-center justify-between">
            <div>
                <p class="text-xs font-semibold text-slate-400 uppercase tracking-wider">Dokumen Word</p>
                <p id="kbDocxCount" class="text-2xl font-bold text-slate-800 mt-1">0</p>
            </div>
            <div class="w-12 h-12 rounded-xl bg-sky-50 flex items-center justify-center text-sky-600 flex-shrink-0 shadow-sm shadow-sky-100">
                <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z" />
                </svg>
            </div>
        </div>
    </div>

    {{-- Main Document list card --}}
    <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm overflow-hidden w-full">
        <div class="px-6 py-4 border-b border-slate-100 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-3">
            <div>
                <h4 class="font-bold text-slate-800 text-sm">Daftar Dokumen Knowledge</h4>
                <span id="kbDocCountLabel" class="text-xs text-slate-400 font-medium mt-0.5 inline-block">0 dokumen ditemukan</span>
            </div>
            {{-- Search Bar --}}
            <div class="relative w-full sm:w-64">
                <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none">
                    <svg class="h-4 w-4 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                </div>
                <input type="text" id="kbSearchInput" placeholder="Cari dokumen..." 
                    class="w-full pl-10 pr-4 py-2 text-xs text-slate-700 placeholder-slate-300 bg-white border border-slate-200 rounded-xl shadow-sm focus:outline-none focus:ring-2 focus:ring-violet-500/30 focus:border-violet-400 hover:border-slate-300 transition-all duration-200">
            </div>
        </div>
        <div id="kbDocumentList" class="divide-y divide-slate-100 transition-all duration-200 min-h-[120px]">
            {{-- Dynamically loaded by knowledge-base.js --}}
        </div>
    </div>

    {{-- Document Viewer Card --}}
    <div id="kbViewerContainer" class="hidden relative bg-white p-6 rounded-2xl border border-slate-200 shadow-md flex-col gap-4 w-full">
        <div class="flex items-start justify-between border-b border-slate-100 pb-3">
            <div class="flex items-center gap-3">
                <h4 id="kbViewerTitle" class="text-base font-bold text-slate-800">Nama Dokumen</h4>
                <span id="kbViewerBadge" class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[10px] font-bold border">Markdown</span>
            </div>
            <button id="kbViewerClose" class="text-slate-400 hover:text-slate-600 transition-colors" title="Tutup Viewer">
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" /></svg>
            </button>
        </div>
        <div id="kbViewerContent" class="overflow-y-auto max-h-[60vh] prose max-w-none text-slate-700 bg-slate-50/50 p-4 rounded-xl border border-slate-150">
            {{-- Content loaded by knowledge-base.js --}}
        </div>
    </div>

</div>
