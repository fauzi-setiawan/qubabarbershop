<div id="aiPage" class="page-content hidden flex-col gap-6 w-full max-w-5xl mx-auto">

    {{-- FORM INPUT AI GENERATOR --}}
    <div id="aiFormContainer" class="relative overflow-hidden w-full bg-white p-8 rounded-2xl border border-slate-200/80 shadow-[0_1px_3px_rgba(0,0,0,0.04)] transition-all duration-300">
        {{-- Aksen Gradien Atas --}}
        <div class="absolute top-0 left-0 w-full h-1.5 bg-gradient-to-r from-indigo-500 via-purple-500 to-pink-500"></div>

        <div class="flex items-start gap-4 mb-6">
            <div class="w-12 h-12 rounded-2xl bg-gradient-to-br from-indigo-500 to-purple-600 flex items-center justify-center shadow-lg shadow-indigo-150 flex-shrink-0">
                <svg class="w-6 h-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9.813 15.904L9 21l-.813-5.096L3.091 15.09l5.096-.813L9 9.187l.813 5.096 5.096.813-5.096.813zM19.071 7.929L18.5 11.5l-.571-3.571L14.358 7.358l3.571-.571.571-3.571.571 3.571 3.571.571-3.571.571z" />
                </svg>
            </div>
            <div>
                <h3 class="text-lg font-bold text-slate-800 tracking-tight">Automated AI Test Case Generator</h3>
                <p class="text-sm text-slate-500 mt-0.5">Gunakan LLM Gemini AI untuk menghasilkan skenario uji fungsional secara instan dan komprehensif.</p>
            </div>
        </div>

        <form id="aiGenerateForm" class="space-y-5">

            {{-- Baris 1: Project & Modul --}}
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                {{-- Pilih Project --}}
                <div>
                    <label for="aiProjectSelect" class="block text-[11px] font-semibold text-slate-500 uppercase tracking-wider mb-1.5">Pilih Project</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none">
                            <svg class="h-4 w-4 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2z" />
                            </svg>
                        </div>
                        <select id="aiProjectSelect" required
                            class="w-full pl-10 pr-10 py-2.5 text-sm text-slate-700 bg-white border border-slate-200 rounded-xl shadow-sm focus:outline-none focus:ring-2 focus:ring-indigo-500/30 focus:border-indigo-400 hover:border-slate-300 transition-all duration-200 appearance-none cursor-pointer">
                            <option value="">— Memuat project... —</option>
                        </select>
                        <div class="absolute right-3.5 top-1/2 -translate-y-1/2 pointer-events-none">
                            <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" /></svg>
                        </div>
                    </div>
                </div>

                {{-- Pilih Modul --}}
                <div>
                    <label for="aiModuleSelect" class="block text-[11px] font-semibold text-slate-500 uppercase tracking-wider mb-1.5">
                        Pilih Modul
                        <span id="aiModuleHint" class="ml-1 text-indigo-400 normal-case font-normal">(pilih project dahulu)</span>
                    </label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none">
                            <svg class="h-4 w-4 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                            </svg>
                        </div>
                        <select id="aiModuleSelect" required disabled
                            class="w-full pl-10 pr-10 py-2.5 text-sm text-slate-400 bg-slate-50 border border-slate-200 rounded-xl shadow-sm focus:outline-none focus:ring-2 focus:ring-indigo-500/30 focus:border-indigo-400 transition-all duration-200 appearance-none cursor-not-allowed">
                            <option value="">— Pilih project dahulu —</option>
                        </select>
                        <div class="absolute right-3.5 top-1/2 -translate-y-1/2 pointer-events-none">
                            <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" /></svg>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Nama Modul (teks bebas, diisi otomatis dari modul yang dipilih) --}}
            <div>
                <label for="aiModuleName" class="block text-[11px] font-semibold text-slate-500 uppercase tracking-wider mb-1.5">
                    Nama Fitur / Konteks Pengujian
                </label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none">
                        <svg class="h-4 w-4 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z" />
                        </svg>
                    </div>
                    <input type="text" id="aiModuleName" required
                        class="w-full pl-10 pr-4 py-2.5 text-sm text-slate-700 placeholder-slate-300 bg-white border border-slate-200 rounded-xl shadow-sm focus:outline-none focus:ring-2 focus:ring-indigo-500/30 focus:border-indigo-400 hover:border-slate-300 transition-all duration-200"
                        placeholder="Contoh: Modul Login Pengguna, Fitur Checkout, dll.">
                </div>
            </div>

            {{-- Deskripsi Alur --}}
            <div>
                <label for="aiFeatureDesc" class="block text-[11px] font-semibold text-slate-500 uppercase tracking-wider mb-1.5">
                    Deskripsi Alur / User Story
                    <span class="ml-1 text-slate-400 normal-case font-normal">(minimal 20 karakter)</span>
                </label>
                <textarea id="aiFeatureDesc" rows="4" required
                    class="w-full px-4 py-2.5 text-sm text-slate-700 placeholder-slate-300 bg-white border border-slate-200 rounded-xl shadow-sm focus:outline-none focus:ring-2 focus:ring-indigo-500/30 focus:border-indigo-400 hover:border-slate-300 transition-all duration-200 resize-none"
                    placeholder="Contoh: Sebagai pengguna, saya ingin menekan tombol bayar agar dapat melakukan transaksi pembayaran dengan virtual account. Sistem harus menampilkan invoice dan mengirim kode pembayaran ke email."></textarea>
                {{-- Counter karakter --}}
                <div class="flex justify-between mt-1">
                    <span id="aiDescWarning" class="hidden text-[11px] text-rose-500 font-medium">Minimal 20 karakter diperlukan.</span>
                    <span id="aiDescCounter" class="ml-auto text-[11px] text-slate-400">0 karakter</span>
                </div>
            </div>

            {{-- Kategori Pengujian --}}
            <div>
                <label for="aiTestType" class="block text-[11px] font-semibold text-slate-500 uppercase tracking-wider mb-1.5">Kategori Pengujian</label>
                <div class="grid grid-cols-2 sm:grid-cols-4 gap-2" id="aiTestTypeGroup">
                    <label class="ai-type-card relative flex flex-col items-center gap-1.5 p-3 rounded-xl border-2 border-indigo-500 bg-indigo-50 cursor-pointer transition-all duration-200 hover:border-indigo-400">
                        <input type="radio" name="aiTestType" value="Positive" checked class="sr-only ai-type-radio">
                        <svg class="w-5 h-5 text-indigo-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        <span class="text-[11px] font-bold text-indigo-700 text-center leading-tight">Positive</span>
                    </label>
                    <label class="ai-type-card relative flex flex-col items-center gap-1.5 p-3 rounded-xl border-2 border-slate-200 bg-white cursor-pointer transition-all duration-200 hover:border-rose-300">
                        <input type="radio" name="aiTestType" value="Negative" class="sr-only ai-type-radio">
                        <svg class="w-5 h-5 text-rose-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        <span class="text-[11px] font-bold text-slate-600 text-center leading-tight">Negative</span>
                    </label>
                    <label class="ai-type-card relative flex flex-col items-center gap-1.5 p-3 rounded-xl border-2 border-slate-200 bg-white cursor-pointer transition-all duration-200 hover:border-amber-300">
                        <input type="radio" name="aiTestType" value="Boundary" class="sr-only ai-type-radio">
                        <svg class="w-5 h-5 text-amber-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        <span class="text-[11px] font-bold text-slate-600 text-center leading-tight">Boundary</span>
                    </label>
                    <label class="ai-type-card relative flex flex-col items-center gap-1.5 p-3 rounded-xl border-2 border-slate-200 bg-white cursor-pointer transition-all duration-200 hover:border-sky-300">
                        <input type="radio" name="aiTestType" value="UI/UX" class="sr-only ai-type-radio">
                        <svg class="w-5 h-5 text-sky-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                        <span class="text-[11px] font-bold text-slate-600 text-center leading-tight">UI/UX</span>
                    </label>
                </div>
                {{-- Hidden input untuk menyimpan nilai aktual --}}
                <input type="hidden" id="aiTestType" value="Positive">
            </div>

            {{-- Tombol Aksi --}}
            <div class="flex justify-end pt-2">
                <button type="submit" id="generateBtn"
                    class="w-full sm:w-auto justify-center group relative overflow-hidden inline-flex items-center gap-2 bg-gradient-to-r from-indigo-600 to-purple-600 hover:from-indigo-700 hover:to-purple-700 text-white text-sm font-semibold px-6 py-3 rounded-xl shadow-sm shadow-indigo-200 hover:shadow-md hover:shadow-indigo-200 transition-all duration-300 active:scale-[0.98] disabled:opacity-60 disabled:cursor-not-allowed">
                    <svg id="generateBtnIcon" class="w-4 h-4 animate-pulse" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9.813 15.904L9 21l-.813-5.096L3.091 15.09l5.096-.813L9 9.187l.813 5.096 5.096.813-5.096.813z" />
                    </svg>
                    <span id="generateBtnText">Generate Skenario AI</span>
                </button>
            </div>
        </form>
    </div>

    {{-- KELUARAN / HASIL GENERATOR AI --}}
    <div id="aiResultContainer" class="hidden flex-col gap-6 w-full bg-white p-8 rounded-2xl border border-slate-200/80 shadow-[0_1px_3px_rgba(0,0,0,0.04)] transition-all duration-300">

        {{-- Header Hasil --}}
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 border-b border-slate-100 pb-5">
            <div>
                <div class="flex items-center gap-2 mb-1">
                    <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full bg-emerald-50 text-[10px] font-bold text-emerald-600 border border-emerald-100">
                        <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 24 24"><path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        AI Generated
                    </span>
                    <span id="aiResultModuleLabel" class="inline-flex items-center px-2 py-0.5 rounded-full bg-indigo-50 text-[10px] font-bold text-indigo-600 border border-indigo-100"></span>
                    {{-- Badge RAG: tampil hanya jika konteks URS digunakan --}}
                    <span id="aiRagBadge" class="hidden inline-flex items-center gap-1 px-2 py-0.5 rounded-full bg-violet-50 text-[10px] font-bold text-violet-600 border border-violet-200" title="Test case dihasilkan berdasarkan dokumen requirement (URS/FSD) proyek Anda">
                        <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                        </svg>
                        RAG • Berbasis URS
                    </span>
                </div>
                <h3 class="text-lg font-bold text-slate-800 tracking-tight">Rekomendasi Skenario Uji AI</h3>
                <p class="text-xs text-slate-400 mt-1">Anda dapat mengubah isinya secara langsung (<em>inline</em>) sebelum disimpan ke database.</p>
            </div>
            <button id="regenerateBtn"
                class="w-full sm:w-auto justify-center inline-flex items-center gap-2 bg-slate-100 hover:bg-slate-200 text-slate-700 text-sm font-semibold px-5 py-2.5 rounded-xl transition-all duration-200 active:scale-[0.97]">
                <svg class="w-4 h-4 text-slate-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0A8.003 8.003 0 015.64 15m13.779 0H15" />
                </svg>
                Generate Ulang
            </button>
        </div>

        {{-- Toolbar Seleksi --}}
        <div class="flex items-center justify-between -mt-2">
            <div class="flex items-center gap-3">
                <button id="aiSelectAllBtn" class="text-[11px] font-semibold text-indigo-600 hover:text-indigo-800 underline underline-offset-2 transition-colors">Pilih Semua</button>
                <span class="text-slate-300">|</span>
                <button id="aiDeselectAllBtn" class="text-[11px] font-semibold text-slate-500 hover:text-slate-700 underline underline-offset-2 transition-colors">Batalkan Semua</button>
            </div>
            <span id="aiSelectedCount" class="text-[11px] text-slate-400 font-medium">5 dari 5 dipilih</span>
        </div>

        {{-- Daftar Hasil Uji --}}
        <div id="aiOutputList" class="space-y-4"></div>

        {{-- Footer Simpan --}}
        <div class="flex justify-end pt-5 border-t border-slate-100">
            <button id="saveAiCasesBtn"
                class="w-full sm:w-auto justify-center group relative overflow-hidden inline-flex items-center gap-2 bg-gradient-to-r from-emerald-600 to-green-600 hover:from-emerald-700 hover:to-green-700 text-white text-sm font-semibold px-6 py-3 rounded-xl shadow-sm shadow-emerald-200 hover:shadow-md hover:shadow-emerald-200 transition-all duration-300 active:scale-[0.98] disabled:opacity-60 disabled:cursor-not-allowed">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4" />
                </svg>
                <span id="saveBtnText">Simpan ke Database</span>
            </button>
        </div>
    </div>
</div>