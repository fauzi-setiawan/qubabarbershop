<div id="registerScreen" class="hidden relative overflow-hidden w-full max-w-md p-8 space-y-6 bg-white rounded-2xl border border-slate-200/80 shadow-[0_8px_30px_rgb(0,0,0,0.04)] transition-all duration-300">
    {{-- Aksen Gradien Atas --}}
    <div class="absolute top-0 left-0 w-full h-1.5 bg-gradient-to-r from-red-500 via-rose-500 to-pink-500"></div>

    <div class="text-center">
        <h2 class="text-2xl font-extrabold text-slate-800 tracking-tight">Daftar Akun Baru</h2>
        <p class="text-sm text-slate-400 mt-1.5">Buat akun untuk mengakses seluruh ekosistem QA Dashboard</p>
    </div>

    <form id="registerForm" class="space-y-4">
        {{-- Kolom Nama --}}
        <div>
            <label for="regName" class="block text-[11px] font-semibold text-slate-500 uppercase tracking-wider mb-1.5">Nama Lengkap</label>
            <div class="relative">
                <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none">
                    <svg class="h-4.5 w-4.5 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                    </svg>
                </div>
                <input id="regName" type="text" required 
                    class="w-full pl-10 pr-4 py-2.5 text-sm text-slate-700 placeholder-slate-300 bg-white border border-slate-200 rounded-xl shadow-sm focus:outline-none focus:ring-2 focus:ring-red-500/30 focus:border-red-400 hover:border-slate-300 transition-all duration-200" 
                    placeholder="Nama lengkap Anda">
            </div>
        </div>

        {{-- Kolom Email --}}
        <div>
            <label for="regEmail" class="block text-[11px] font-semibold text-slate-500 uppercase tracking-wider mb-1.5">Alamat Email</label>
            <div class="relative">
                <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none">
                    <svg class="h-4.5 w-4.5 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                    </svg>
                </div>
                <input id="regEmail" type="email" required 
                    class="w-full pl-10 pr-4 py-2.5 text-sm text-slate-700 placeholder-slate-300 bg-white border border-slate-200 rounded-xl shadow-sm focus:outline-none focus:ring-2 focus:ring-red-500/30 focus:border-red-400 hover:border-slate-300 transition-all duration-200" 
                    placeholder="nama@email.com">
            </div>
        </div>

        {{-- Kolom Peran --}}
        <div>
            <label for="regRole" class="block text-[11px] font-semibold text-slate-500 uppercase tracking-wider mb-1.5">Peran Akun</label>
            <div class="relative">
                <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none">
                    <svg class="h-4.5 w-4.5 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m10 0a2 2 0 002 2v3.5a2.25 2.25 0 01-2.25 2.25h-11.5A2.25 2.25 0 012 11.5V8a2 2 0 002-2h12z" />
                    </svg>
                </div>
                <select id="regRole" 
                    class="w-full pl-10 pr-10 py-2.5 text-sm text-slate-700 bg-white border border-slate-200 rounded-xl shadow-sm focus:outline-none focus:ring-2 focus:ring-red-500/30 focus:border-red-400 hover:border-slate-300 transition-all duration-200 appearance-none cursor-pointer">
                    <option value="Software QA">Software QA (Tester)</option>
                    <option value="Project Manager">Project Manager</option>
                    <option value="Admin">Admin</option>
                </select>
                <div class="absolute right-3.5 top-1/2 -translate-y-1/2 pointer-events-none">
                    <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" />
                    </svg>
                </div>
            </div>
        </div>

        {{-- Kolom Password --}}
        <div>
            <label for="regPassword" class="block text-[11px] font-semibold text-slate-500 uppercase tracking-wider mb-1.5">Password</label>
            <div class="relative">
                <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none">
                    <svg class="h-4.5 w-4.5 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                    </svg>
                </div>
                <input id="regPassword" type="password" required 
                    class="w-full pl-10 pr-4 py-2.5 text-sm text-slate-700 placeholder-slate-300 bg-white border border-slate-200 rounded-xl shadow-sm focus:outline-none focus:ring-2 focus:ring-red-500/30 focus:border-red-400 hover:border-slate-300 transition-all duration-200" 
                    placeholder="Minimal 8 karakter">
            </div>
        </div>

        {{-- Tombol Daftar --}}
        <button type="submit" id="registerButton" 
            class="w-full py-2.5 px-4 rounded-xl text-sm font-semibold text-white bg-gradient-to-r from-red-600 to-rose-600 hover:from-red-700 hover:to-rose-700 focus:outline-none transition-all duration-300 shadow-sm shadow-red-200 hover:shadow-md hover:shadow-red-200 active:scale-[0.98] mt-2">
            Daftar Sekarang
        </button>

        {{-- Tautan Masuk --}}
        <p class="text-center text-xs text-slate-400 mt-4">
            Sudah punya akun? 
            <a href="#" id="toLoginBtn" class="font-semibold text-red-600 hover:text-red-700 hover:underline transition-colors ml-1">Login di sini</a>
        </p>
    </form>
</div>