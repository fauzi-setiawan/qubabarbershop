<div id="automationPage" class="page-content hidden flex-col gap-6 w-full max-w-5xl mx-auto">
    
    {{-- RUNNER PANEL --}}
    <div class="relative overflow-hidden bg-white p-8 rounded-2xl border border-slate-200/80 shadow-[0_1px_3px_rgba(0,0,0,0.04)] transition-all duration-300">
        {{-- Aksen Gradien Atas --}}
        <div class="absolute top-0 left-0 w-full h-1.5 bg-gradient-to-r from-blue-500 via-indigo-500 to-purple-500"></div>

        <div class="flex items-start gap-4 mb-6">
            <div class="w-12 h-12 rounded-2xl bg-gradient-to-br from-blue-500 to-indigo-600 flex items-center justify-center shadow-lg shadow-blue-150 flex-shrink-0">
                {{-- DevOps / Play Icon --}}
                <svg class="w-6 h-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z" />
                    <path stroke-linecap="round" stroke-linejoin="round" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
            </div>
            <div>
                <h3 class="text-lg font-bold text-slate-800 tracking-tight">Test Automation Runner</h3>
                <p class="text-sm text-slate-500 mt-0.5">Picu dan jalankan rangkaian uji otomatis yang berada di GitHub Repository Anda secara remote.</p>
            </div>
        </div>
        
        <form id="automationForm" class="space-y-5">
            {{-- GitHub URL --}}
            <div>
                <label for="repoUrl" class="block text-[11px] font-semibold text-slate-500 uppercase tracking-wider mb-1.5">GitHub Repository URL</label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none">
                        {{-- GitHub Icon --}}
                        <svg class="h-4.5 w-4.5 text-slate-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M9 19c-5 1.5-5-2.5-7-3m14 6v-3.87a3.37 3.37 0 0 0-.94-2.61c3.14-.35 6.44-1.54 6.44-7A5.44 5.44 0 0 0 20 4.77 5.07 5.07 0 0 0 19.91 1S18.73.65 16 2.48a13.38 13.38 0 0 0-7 0C6.27.65 5.09 1 5.09 1A5.07 5.07 0 0 0 5 4.77a5.44 5.44 0 0 0-1.5 3.78c0 5.42 3.3 6.61 6.44 7A3.37 3.37 0 0 0 9 18.13V22"></path>
                        </svg>
                    </div>
                    <input type="url" id="repoUrl" required 
                        class="w-full pl-10 pr-4 py-2.5 text-sm text-slate-700 placeholder-slate-300 bg-white border border-slate-200 rounded-xl shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500/30 focus:border-blue-400 hover:border-slate-300 transition-all duration-200" 
                        placeholder="https://github.com/username/repository">
                </div>
            </div>

            {{-- Branch & Env Grid --}}
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                {{-- Branch --}}
                <div>
                    <label for="repoBranch" class="block text-[11px] font-semibold text-slate-500 uppercase tracking-wider mb-1.5">Branch</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none">
                            {{-- Git Branch Icon --}}
                            <svg class="h-4.5 w-4.5 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M8 7a3 3 0 100-6 3 3 0 000 6zM8 7v7a3 3 0 106 0v-1m0 0a3 3 0 100-6 3 3 0 000 6z" />
                            </svg>
                        </div>
                        <input type="text" id="repoBranch" value="main" required
                            class="w-full pl-10 pr-4 py-2.5 text-sm text-slate-700 placeholder-slate-300 bg-white border border-slate-200 rounded-xl shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500/30 focus:border-blue-400 hover:border-slate-300 transition-all duration-200">
                    </div>
                </div>

                {{-- Target Environment --}}
                <div>
                    <label for="testEnv" class="block text-[11px] font-semibold text-slate-500 uppercase tracking-wider mb-1.5">Target Environment</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none">
                            {{-- Server Cloud Icon --}}
                            <svg class="h-4.5 w-4.5 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M3 15a4 4 0 004 4h9a5 5 0 10-.1-9.999 5.002 5.002 0 10-9.78 2.096A4.001 4.001 0 003 15z" />
                            </svg>
                        </div>
                        <select id="testEnv" 
                            class="w-full pl-10 pr-10 py-2.5 text-sm text-slate-700 bg-white border border-slate-200 rounded-xl shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500/30 focus:border-blue-400 hover:border-slate-300 transition-all duration-200 appearance-none cursor-pointer">
                            <option value="Staging">Staging</option>
                            <option value="Development">Development</option>
                            <option value="Production">Production</option>
                        </select>
                        <div class="absolute right-3.5 top-1/2 -translate-y-1/2 pointer-events-none">
                            <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" />
                            </svg>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Action Submit --}}
            <div class="flex justify-end pt-2">
                <button type="submit" id="runAutoBtn" 
                    class="w-full sm:w-auto justify-center group relative overflow-hidden inline-flex items-center gap-2 bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 text-white text-sm font-semibold px-6 py-3 rounded-xl shadow-sm shadow-blue-200 hover:shadow-md hover:shadow-blue-200 transition-all duration-300 active:scale-[0.98]">
                    <svg class="w-4 h-4 transition-transform duration-300 group-hover:scale-110" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M13 10V3L4 14h7v7l9-11h-7z" />
                    </svg>
                    Picu Automation Run
                </button>
            </div>
        </form>
    </div>

    {{-- SIMULASI RIWAYAT EKSEKUSI PIPELINE --}}
    <div class="bg-white p-6 rounded-2xl border border-slate-200/80 shadow-[0_1px_3px_rgba(0,0,0,0.04)]">
        <div class="flex items-center gap-2.5 mb-5 border-b border-slate-100 pb-4">
            <div class="w-8 h-8 rounded-lg bg-slate-50 flex items-center justify-center">
                <svg class="w-4 h-4 text-slate-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
            </div>
            <h4 class="text-sm font-bold text-slate-800 tracking-tight">Riwayat Pengujian Otomatis (GitHub Actions)</h4>
        </div>

        <div class="overflow-x-auto w-full">
            <table class="min-w-full">
                <thead>
                    <tr class="bg-slate-50 border-b border-slate-100">
                        <th class="px-5 py-3 text-left text-[11px] font-bold text-slate-500 uppercase tracking-wider">Run ID</th>
                        <th class="px-5 py-3 text-left text-[11px] font-bold text-slate-500 uppercase tracking-wider">Branch / Commit</th>
                        <th class="px-5 py-3 text-center text-[11px] font-bold text-slate-500 uppercase tracking-wider">Env</th>
                        <th class="px-5 py-3 text-center text-[11px] font-bold text-slate-500 uppercase tracking-wider">Status</th>
                        <th class="px-5 py-3 text-right text-[11px] font-bold text-slate-500 uppercase tracking-wider">Waktu Run</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-sm">
                    <tr>
                        <td class="px-5 py-3.5 font-mono text-xs font-semibold text-slate-600">#RUN-0421</td>
                        <td class="px-5 py-3.5">
                            <span class="inline-flex items-center gap-1 text-xs text-indigo-600 font-semibold bg-indigo-50 px-2 py-0.5 rounded-md">main</span>
                            <span class="text-xs text-slate-400 font-mono ml-1.5">a9fbc18</span>
                        </td>
                        <td class="px-5 py-3.5 text-center"><span class="text-xs font-semibold text-slate-600">Staging</span></td>
                        <td class="px-5 py-3.5 text-center">
                            <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-xs font-bold bg-emerald-50 text-emerald-700 border border-emerald-200/50">Passed</span>
                        </td>
                        <td class="px-5 py-3.5 text-right text-xs text-slate-400">10 Jun 2026 21:00</td>
                    </tr>
                    <tr>
                        <td class="px-5 py-3.5 font-mono text-xs font-semibold text-slate-600">#RUN-0420</td>
                        <td class="px-5 py-3.5">
                            <span class="inline-flex items-center gap-1 text-xs text-indigo-600 font-semibold bg-indigo-50 px-2 py-0.5 rounded-md">main</span>
                            <span class="text-xs text-slate-400 font-mono ml-1.5">d01ea34</span>
                        </td>
                        <td class="px-5 py-3.5 text-center"><span class="text-xs font-semibold text-slate-600">Staging</span></td>
                        <td class="px-5 py-3.5 text-center">
                            <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-xs font-bold bg-red-50 text-red-700 border border-red-200/50">Failed</span>
                        </td>
                        <td class="px-5 py-3.5 text-right text-xs text-slate-400">09 Jun 2026 18:42</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>