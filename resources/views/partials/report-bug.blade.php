<div id="bugReportModal" class="fixed inset-0 z-50 hidden items-center justify-center p-4">
    <div class="modal-backdrop fixed inset-0 bg-slate-900/60 backdrop-blur-sm transition-opacity duration-300"></div>
    <div class="relative w-full max-w-lg bg-white rounded-2xl shadow-2xl border border-slate-100 overflow-hidden transform transition-all duration-300 z-10 p-6 my-8">
        {{-- Accent top border --}}
        <div class="absolute top-0 left-0 w-full h-1.5 bg-gradient-to-r from-red-500 via-rose-500 to-pink-500"></div>
        
        <form id="bugReportForm" class="space-y-4">
            <div class="flex justify-between items-start mt-2">
                <div>
                    <h3 class="text-lg font-bold text-slate-800 tracking-tight">Form Laporan Bug</h3>
                    <p class="text-xs text-slate-400 mt-1">Lengkapi detail di bawah untuk membuat card di Trello.</p>
                </div>
                <button type="button" id="closeBugModalBtn" class="text-slate-400 hover:text-slate-600 transition-colors" title="Tutup Modal">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
            
            <div class="space-y-4 max-h-[60vh] overflow-y-auto pr-1">
                <div class="space-y-1.5">
                    <label for="bugTitle" class="block text-[11px] font-semibold text-slate-500 uppercase tracking-wider">Judul Bug</label>
                    <input type="text" id="bugTitle" required 
                        class="w-full px-3.5 py-2 text-sm text-slate-700 placeholder-slate-300 bg-white border border-slate-200 rounded-xl shadow-sm focus:outline-none focus:ring-2 focus:ring-indigo-500/30 focus:border-indigo-400 hover:border-slate-300 transition-all duration-200" 
                        placeholder="Contoh: Gagal memuat data project">
                </div>
                <div class="space-y-1.5">
                    <label for="bugDesc" class="block text-[11px] font-semibold text-slate-500 uppercase tracking-wider">Deskripsi Bug</label>
                    <textarea id="bugDesc" rows="3" 
                        class="w-full px-3.5 py-2 text-sm text-slate-700 placeholder-slate-300 bg-white border border-slate-200 rounded-xl shadow-sm focus:outline-none focus:ring-2 focus:ring-indigo-500/30 focus:border-indigo-400 hover:border-slate-300 transition-all duration-200 resize-none" 
                        placeholder="Deskripsikan langkah reproduksi bug..."></textarea>
                </div>
                <div class="space-y-1.5">
                    <label for="bugSeverity" class="block text-[11px] font-semibold text-slate-500 uppercase tracking-wider">Tingkat Keparahan</label>
                    <div class="relative">
                        <select id="bugSeverity" 
                            class="w-full px-3.5 py-2 text-sm text-slate-700 bg-white border border-slate-200 rounded-xl shadow-sm focus:outline-none focus:ring-2 focus:ring-indigo-500/30 focus:border-indigo-400 hover:border-slate-300 transition-all duration-200 cursor-pointer appearance-none">
                            <option value="Critical">Critical</option>
                            <option value="Major">Major</option>
                            <option value="Minor" selected>Minor</option>
                        </select>
                        <div class="absolute right-3.5 top-1/2 -translate-y-1/2 pointer-events-none">
                            <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" />
                            </svg>
                        </div>
                    </div>
                </div>
                <input type="hidden" id="bugExecId">
            </div>

            <div class="flex justify-end gap-3 pt-3 border-t border-slate-100 mt-6">
                <button type="button" id="cancelBugModalBtn" 
                    class="px-4.5 py-2.5 text-sm font-semibold text-slate-500 hover:text-slate-700 bg-slate-100 hover:bg-slate-200/80 rounded-xl transition-all duration-200 active:scale-[0.97]">
                    Batal
                </button>
                <button type="submit" id="submitBugBtn" 
                    class="px-5 py-2.5 text-sm font-semibold text-white bg-gradient-to-r from-red-600 to-rose-600 hover:from-red-700 hover:to-rose-700 rounded-xl shadow-sm shadow-red-150 transition-all duration-200 active:scale-[0.97]">
                    Kirim ke Trello
                </button>
            </div>
        </form>
    </div>
</div>