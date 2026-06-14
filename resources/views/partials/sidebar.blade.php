<aside id="sidebarMenu" class="w-64 bg-gray-800 text-white flex flex-col fixed h-full z-40 transform -translate-x-full lg:translate-x-0 transition-transform duration-300 ease-in-out">
    <div class="h-16 flex items-center justify-between px-4 border-b border-gray-700 flex-shrink-0">
        <div class="flex items-center space-x-2">
            <svg class="h-8 w-8 text-indigo-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M9 17.25v1.007a3 3 0 01-.879 2.122L7.5 21h9l-1.621-.621A3 3 0 0115 18.257V17.25m6-12V15a2.25 2.25 0 01-2.25 2.25H5.25A2.25 2.25 0 013 15V5.25A2.25 2.25 0 015.25 3h4.5M12 3v13.5" /></svg>
            <span class="font-bold text-lg">QA Dashboard</span>
        </div>
        <!-- Tombol Tutup Sidebar (Mobile) -->
        <button id="closeSidebarBtn" class="lg:hidden p-1.5 rounded-lg text-gray-400 hover:text-white hover:bg-gray-700 focus:outline-none" title="Tutup Menu">
            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
            </svg>
        </button>
    </div>
    <nav id="sidebarNav" class="flex-1 px-4 py-4 space-y-2 overflow-y-auto">
        <a href="#" data-page="reportingPage" class="nav-link flex items-center space-x-3 px-4 py-2.5 bg-gray-900 text-white rounded-xl transition-all duration-200 font-medium"><svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.5 6a7.5 7.5 0 100 15 7.5 7.5 0 000-15z" /></svg><span>Dashboard Reporting</span></a>
        <a href="#" data-page="manageProjectPage" class="nav-link flex items-center space-x-3 px-4 py-2.5 text-gray-300 hover:bg-gray-700 rounded-xl transition-all duration-200 font-medium"><svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2z" /></svg><span>Kelola Project</span></a>
        <a href="#" data-page="manageUserPage" class="nav-link flex items-center space-x-3 px-4 py-2.5 text-gray-300 hover:bg-gray-700 rounded-xl transition-all duration-200 font-medium"><svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a3 3 0 11-6 0 3 3 0 016 0z" /></svg><span>Kelola User</span></a>
        <a href="#" data-page="monitoringPage" class="nav-link flex items-center space-x-3 px-4 py-2.5 text-gray-300 hover:bg-gray-700 rounded-xl transition-all duration-200 font-medium"><svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" /></svg><span>Monitoring Testcase</span></a>
        <a href="#" data-page="automationPage" class="nav-link flex items-center space-x-3 px-4 py-2.5 text-gray-300 hover:bg-gray-700 rounded-xl transition-all duration-200 font-medium"><svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5.25 5.653c0-.856.917-1.398 1.667-.986l11.54 6.348a1.125 1.125 0 010 1.971l-11.54 6.347a1.125 1.125 0 01-1.667-.985V5.653z" /></svg><span>Test Automation</span></a>
        <a href="#" data-page="aiPage" class="nav-link flex items-center space-x-3 px-4 py-2.5 text-gray-300 hover:bg-gray-700 rounded-xl transition-all duration-200 font-medium"><svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M13 10V3L4 14h7v7l9-11h-7z" /></svg><span>AI Generate Testcase</span></a>
        <a href="#" data-page="knowledgeBasePage" class="nav-link flex items-center space-x-3 px-4 py-2.5 text-gray-300 hover:bg-gray-700 rounded-xl transition-all duration-200 font-medium"><svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg><span>Knowledge Base</span></a>
        <a href="#" data-page="reportPage" class="nav-link flex items-center space-x-3 px-4 py-2.5 text-gray-300 hover:bg-gray-700 rounded-xl transition-all duration-200 font-medium">
            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.375 12.739l-7.693 7.693a4.5 4.5 0 01-6.364-6.364l10.94-10.94A3 3 0 1119.5 7.372L8.552 18.32m.009-.01l-.01.01m5.699-9.941l-7.81 7.81a1.5 1.5 0 002.122 2.122l7.81-7.81" />
            </svg>
            <span>Attach Report Bug</span>
        </a>
        <a href="#" data-page="managePage" class="nav-link flex items-center space-x-3 px-4 py-2.5 text-gray-300 hover:bg-gray-700 rounded-xl transition-all duration-200 font-medium"><svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16" /></svg><span>Kelola Testcase</span></a>
    </nav>
</aside>