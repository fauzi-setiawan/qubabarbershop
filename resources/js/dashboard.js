

import { token, showToast } from "./modules/api.js";
import { setupAuth } from "./modules/auth.js";
import { setupProject } from "./modules/project.js";
import { setupUser } from "./modules/user.js";
import { setupReporting } from "./modules/reporting.js";
import { setupAIGenerate } from "./modules/ai-generate.js";
import { setupTestCase } from "./modules/testcase.js";
import { setupAutomation } from "./modules/automation.js";
import { setupBugReport, loadReportedBugs } from "./modules/bug-report.js";
import { setupMonitoring } from "./modules/monitoring.js";
import { setupKnowledgeBase } from "./modules/knowledge-base.js";


// Event Listener utama saat halaman selesai dimuat
document.addEventListener("DOMContentLoaded",  () => {
    // Inisialisasi Event Listener Fitur
    setupAuth();
    setupProject();
    setupUser();
    setupReporting();
    setupAIGenerate();
    setupTestCase();
    setupAutomation();
    setupBugReport();
    setupMonitoring();
    setupKnowledgeBase();


    // Logika Sidebar Mobile Responsif
    const mobileMenuBtn = document.getElementById("mobileMenuBtn");
    const closeSidebarBtn = document.getElementById("closeSidebarBtn");
    const sidebarMenu = document.getElementById("sidebarMenu");
    const sidebarBackdrop = document.getElementById("sidebarBackdrop");

    function openSidebar() {
        if (sidebarMenu) sidebarMenu.classList.remove("-translate-x-full");
        if (sidebarBackdrop) sidebarBackdrop.classList.remove("hidden");
    }

    function closeSidebar() {
        if (sidebarMenu) sidebarMenu.classList.add("-translate-x-full");
        if (sidebarBackdrop) sidebarBackdrop.classList.add("hidden");
    }

    if (mobileMenuBtn) mobileMenuBtn.addEventListener("click", openSidebar);
    if (closeSidebarBtn) closeSidebarBtn.addEventListener("click", closeSidebar);
    if (sidebarBackdrop) sidebarBackdrop.addEventListener("click", closeSidebar);

    document.querySelectorAll(".nav-link").forEach(link => {
        link.addEventListener("click", (e) => {
            e.preventDefault();
            
            console.log("Klik:", link.dataset.page);
            switchPage(link.dataset.page);
            closeSidebar(); // Tutup menu setelah diklik pada perangkat mobile
        });
    });

    // Event global pasca-login berhasil
    window.addEventListener("userLoggedIn", () => {
        showDashboard();
        console.log("berhasil");
    });

    // Cek status login saat aplikasi pertama kali dibuka
    if (token) {
        showDashboard();
    } else {
        showLogin();
    }
});



function showDashboard() {
    console.log("showDashboard dipanggil");
    document.getElementById("authContainer").classList.add("hidden");
    document.getElementById("mainDashboard").classList.remove("hidden");
    document.getElementById("userDisplayName").textContent = localStorage.getItem("user_name");
    document.getElementById("userAvatar").textContent = localStorage.getItem("user_avatar");
    
    // Default arahkan ke halaman utama PM Dashboard Reporting
    switchPage("reportingPage");
}

function showLogin() {
    document.getElementById("mainDashboard").classList.add("hidden");
    document.getElementById("authContainer").classList.remove("hidden");
    document.getElementById("loginScreen").classList.remove("hidden");
    document.getElementById("registerScreen").classList.add("hidden");
}

// Handler Navigasi SPA global di entry-point
function switchPage(pageId) {
    console.log("switchPage:", pageId);
    
    document.querySelectorAll(".page-content").forEach(page => page.classList.add("hidden"));
    
    const targetPage = document.getElementById(pageId);
    if (targetPage) {
        targetPage.classList.remove("hidden");
        targetPage.classList.add("flex");
    }

    const pageTitles = {
        reportingPage: "Dashboard Reporting",
        manageProjectPage: "Kelola Project",
        manageUserPage: "Kelola User",
        monitoringPage: "Monitoring Testcase",
        aiPage: "AI Generate Testcase",
        knowledgeBasePage: "Knowledge Base",
        managePage: "Kelola Testcase",
        automationPage: "Test Automation",
        reportPage: "Attach Report Bug"
    };
    
    const titleEl = document.getElementById("pageTitle");
    if (titleEl) titleEl.textContent = pageTitles[pageId] || "Dashboard";

    // Pemicu pemuatan asinkron data berdasarkan halaman aktif secara modular
    const moduleName = getFileByPage(pageId);
    import(`./modules/${moduleName}.js`).then(module => {
        const loadFunctions = {
            reportingPage:     module.loadReportingDashboard,
            manageProjectPage: module.loadProjects,
            manageUserPage:    module.loadUsers,
            monitoringPage:    module.loadExecutions,
            managePage:        module.loadTestCases,
            automationPage:    module.loadAutomationConfig,
            reportPage:        module.loadReportedBugs,
            aiPage:            module.loadAIGeneratePage,
            knowledgeBasePage: module.loadKnowledgeBase,
        };
        if (loadFunctions[pageId]) loadFunctions[pageId]();
    }).catch(err => {
        console.error("Gagal mengimpor modul fitur:", err);
    });

    setActiveNav(pageId);
}

// Tambahkan fungsi baru ini
function setActiveNav(pageId) {
    document.querySelectorAll(".nav-link").forEach(link => {
        // Reset semua link ke state non-aktif
        link.classList.remove("bg-gray-900", "bg-gray-700", "bg-slate-800", "text-white");
        link.classList.add("text-gray-300");
    });

    // Set link yang aktif
    const activeLink = document.querySelector(`.nav-link[data-page="${pageId}"]`);
    if (activeLink) {
        activeLink.classList.remove("text-gray-300", "text-slate-400");
        activeLink.classList.add("bg-gray-900", "text-white");
    }
}

function getFileByPage(pageId) {
    const mapping = {
        reportingPage: "reporting",
        manageProjectPage: "project",
        manageUserPage: "user",
        monitoringPage: "monitoring",
        aiPage: "ai-generate",
        knowledgeBasePage: "knowledge-base",
        managePage: "testcase",
        automationPage: "automation",
        reportPage: "bug-report"
    };
    return mapping[pageId];
}
