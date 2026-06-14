import { apiRequest, showToast} from "./api.js";
import { loadExecutions } from "./monitoring.js";
// import { loadFailedTests } from "./reporting.js";

export async function setupBugReport() {
    const form = document.getElementById("bugReportForm");
    const modal = document.getElementById("bugReportModal");
    const closeBtn = document.getElementById("closeBugModalBtn");
    const cancelBtn = document.getElementById("cancelBugModalBtn");
    const syncBtn = document.getElementById("syncBugBtn");
    const refreshBtn = document.getElementById("refreshBugBtn");

    // ── Sync Trello ──
    if (syncBtn) {
        syncBtn.addEventListener("click", async () => {
            syncBtn.disabled = true;
            syncBtn.classList.add("sync-spinning");
            syncBtn.innerHTML = `
                <svg class="h-4 w-4 animate-spin" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M4 4v6h6M20 20v-6h-6M5.64 18.36A9 9 0 1020 12"/>
                </svg>
                Sinkronisasi...
            `;

            try {
                await apiRequest("/bugs/sync", "POST");
                showToast("Sinkronisasi berhasil");
                loadReportedBugs();

                // Update sync time
                const syncTimeEl = document.getElementById("lastSyncTime");
                if (syncTimeEl) syncTimeEl.textContent = new Date().toLocaleString("id-ID");
            } catch (err) {
                showToast("Sinkronisasi gagal", false);
            } finally {
                syncBtn.disabled = false;
                syncBtn.classList.remove("sync-spinning");
                syncBtn.innerHTML = `
                    <svg class="h-4 w-4 transition-transform duration-500 group-hover:rotate-180" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M4 4v6h6M20 20v-6h-6M5.64 18.36A9 9 0 1020 12"/>
                    </svg>
                    Sinkronisasi Trello
                `;
            }
        });
    }

    // ── Refresh Button ──
    if (refreshBtn) {
        refreshBtn.addEventListener("click", () => {
            loadExecutions();
            showToast("Dashboard berhasil diperbarui");
        });
    }

    // ── Set initial sync time ──
    const syncTimeEl = document.getElementById("lastSyncTime");
    if (syncTimeEl) syncTimeEl.textContent = new Date().toLocaleString("id-ID");

    // ── Modal close handlers ──
    const closeModal = () => {
        modal.classList.add("hidden");
        modal.classList.remove("flex");
        form.reset();
    };

    if (closeBtn) closeBtn.addEventListener("click", closeModal);
    if (cancelBtn) cancelBtn.addEventListener("click", closeModal);

    // ── Tangkap event global dari monitoring.js ──
    window.addEventListener("openBugReport", (e) => {
        document.getElementById("bugExecId").value = e.detail.id;
        document.getElementById("bugTitle").value = `Defek di ${e.detail.name}`;
        document.getElementById("bugDesc").value = `Skenario pengujian "${e.detail.name}" gagal saat dieksekusi oleh tim Software QA. Harap periksa log sistem segera.`;

        modal.classList.remove("hidden");
        modal.classList.add("flex");
    });

    // ── Form submit handler ──
    if (form) {
        form.addEventListener("submit", async (e) => {
            e.preventDefault();
            const btn = document.getElementById("submitBugBtn");
            const test_execution_id = document.getElementById("bugExecId").value;
            const title = document.getElementById("bugTitle").value;
            const description = document.getElementById("bugDesc").value;
            const severity = document.getElementById("bugSeverity").value;

            btn.disabled = true;
            btn.innerHTML = `
                <svg class="w-4 h-4 animate-spin" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path></svg>
                Mengirim...
            `;

            try {
                await apiRequest("/bugs/report", "POST", {
                    test_execution_id,
                    title,
                    description,
                    severity
                });

                showToast("Card bug sukses dibuat di Trello!");
                closeModal();
                loadExecutions();
            } catch (err) {
                showToast(err.message, false);
            } finally {
                btn.disabled = false;
                btn.innerHTML = `
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 12L3.269 3.126A59.768 59.768 0 0121.485 12 59.77 59.77 0 013.27 20.876L5.999 12zm0 0h7.5"></path></svg>
                    Kirim ke Trello
                `;
            }
        });
    }
}

/**
 * Attach event listeners ke tombol "Report Trello" di tabel queue.
 */
export async function attachFailedActions() {
    document.querySelectorAll(".trello-report-btn").forEach(btn => {
        btn.addEventListener("click", () => {
            window.dispatchEvent(
                new CustomEvent("openBugReport", {
                    detail: {
                        id: btn.dataset.id,
                        name: btn.dataset.name
                    }
                })
            );
        });
    });
}

let globalReportedBugs = [];


/**
 * Memuat arsip bug yang sudah dilaporkan ke Trello — Tab 2.
 * Data dari: bug_reports (title, severity, status, trello_card_url, reporter.name)
 */
export async function loadReportedBugs() {
    try {
        const res = await apiRequest("/bugs");
        globalReportedBugs = Array.isArray(res) ? res : (res.data || []);
        renderReportedBugsTable();
    } catch (err) {
        console.error(err);
        showToast("Gagal memuat arsip bug", false);
    }
}

function renderReportedBugsTable() {
    const tbody = document.getElementById("reportedBugList");
    if (!tbody) return;

    tbody.innerHTML = "";

    // Update archive badge count
    const archiveBadge = document.getElementById("archiveBadge");
    if (archiveBadge) archiveBadge.textContent = globalReportedBugs.length;

    if (globalReportedBugs.length === 0) {
        tbody.innerHTML = `
            <tr>
                <td colspan="6" class="text-center py-16">
                    <div class="flex flex-col items-center gap-3">
                        <div class="w-14 h-14 rounded-2xl bg-slate-100 flex items-center justify-center">
                            <svg class="w-7 h-7 text-slate-300" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                            </svg>
                        </div>
                        <p class="text-sm font-semibold text-slate-400">Belum ada bug yang dilaporkan</p>
                        <p class="text-xs text-slate-300">Laporan bug akan muncul di sini setelah dikirim ke Trello</p>
                    </div>
                </td>
            </tr>`;
        return;
    }

    const paginated = globalReportedBugs;

    paginated.forEach((bug, index) => {
        const globalIndex = index + 1;
        const severityConfig = getSeverityConfig(bug.severity);
        const statusConfig = getBugStatusConfig(bug.status);
        const reporterName = bug.reporter?.name ?? '-';

        const row = document.createElement("tr");
        row.className = "group transition-colors duration-150";

        row.innerHTML = `
            <td class="px-6 py-3.5 text-center">
                <span class="inline-flex items-center justify-center min-w-[36px] px-2 py-1 rounded-lg bg-slate-100 text-[11px] font-bold text-slate-600 font-mono">
                    ${globalIndex}
                </span>
            </td>
            <td class="px-6 py-3.5">
                <div>
                    <span class="text-sm font-medium text-slate-700 group-hover:text-slate-900 transition-colors">${bug.title}</span>
                    ${bug.description ? `<p class="text-[11px] text-slate-400 mt-0.5 line-clamp-1">${bug.description}</p>` : ''}
                </div>
            </td>
            <td class="px-6 py-3.5 text-center">
                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg text-[11px] font-bold ${severityConfig.classes}">
                    <span class="w-1.5 h-1.5 rounded-full ${severityConfig.dotColor}"></span>
                    ${bug.severity}
                </span>
            </td>
            <td class="px-6 py-3.5 text-center">
                <div class="flex items-center justify-center gap-2">
                    <div class="w-6 h-6 rounded-full bg-gradient-to-br from-indigo-400 to-purple-500 flex items-center justify-center text-[9px] font-bold text-white shadow-sm">
                        ${reporterName.charAt(0).toUpperCase()}
                    </div>
                    <span class="text-xs font-medium text-slate-600">${reporterName}</span>
                </div>
            </td>
            <td class="px-6 py-3.5 text-center">
                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-[11px] font-bold ${statusConfig.classes}">
                    <span class="w-1.5 h-1.5 rounded-full ${statusConfig.dotColor}"></span>
                    ${bug.status}
                </span>
            </td>
            <td class="px-6 py-3.5 text-center">
                <a href="${bug.trello_card_url}" target="_blank"
                   class="group/link inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg bg-blue-50 text-blue-600 text-[11px] font-semibold hover:bg-blue-100 transition-colors duration-200">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1"></path></svg>
                    Buka Card
                    <svg class="w-3 h-3 transition-transform group-hover/link:translate-x-0.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path></svg>
                </a>
            </td>
        `;

        tbody.appendChild(row);
    });
}

/**
 * Setup tab navigation — queue vs archive.
 */
export function setupReportingTabs(loadFailedTests) {
    const queueBtn = document.getElementById("tabQueueBtn");
    const archiveBtn = document.getElementById("tabArchiveBtn");
    const queueSection = document.getElementById("bugQueueSection");
    const archiveSection = document.getElementById("bugArchiveSection");

    if (!queueBtn || !archiveBtn) return;

    function setActiveTab(activeBtn, inactiveBtn) {
        activeBtn.classList.add("active-tab");
        inactiveBtn.classList.remove("active-tab");
    }

    queueBtn.addEventListener("click", () => {
        queueSection.classList.remove("hidden");
        archiveSection.classList.add("hidden");
        setActiveTab(queueBtn, archiveBtn);
    });

    archiveBtn.addEventListener("click", () => {
        archiveSection.classList.remove("hidden");
        queueSection.classList.add("hidden");
        setActiveTab(archiveBtn, queueBtn);
        loadReportedBugs();
    });

    // Default: activate queue tab
    setActiveTab(queueBtn, archiveBtn);

    if (loadFailedTests) loadFailedTests();
}

// ═══════════════════════════════════════════════════════
// Helper: Konfigurasi warna severity
// Sesuai enum database: Critical, Major, Minor
// ═══════════════════════════════════════════════════════
function getSeverityConfig(severity) {
    const configs = {
        'Critical': {
            classes: 'bg-red-50 text-red-700 border border-red-200/60',
            dotColor: 'bg-red-500'
        },
        'Major': {
            classes: 'bg-amber-50 text-amber-700 border border-amber-200/60',
            dotColor: 'bg-amber-500'
        },
        'Minor': {
            classes: 'bg-sky-50 text-sky-700 border border-sky-200/60',
            dotColor: 'bg-sky-500'
        }
    };
    return configs[severity] || configs['Minor'];
}

// ═══════════════════════════════════════════════════════
// Helper: Konfigurasi warna bug status
// Default status dari database: Open, dan status lain dari Trello sync
// ═══════════════════════════════════════════════════════
function getBugStatusConfig(status) {
    const normalized = (status || '').toLowerCase();
    if (normalized.includes('close') || normalized.includes('done') || normalized.includes('resolve')) {
        return { classes: 'bg-emerald-50 text-emerald-700 border border-emerald-200/60', dotColor: 'bg-emerald-500' };
    }
    if (normalized.includes('progress') || normalized.includes('review')) {
        return { classes: 'bg-amber-50 text-amber-700 border border-amber-200/60', dotColor: 'bg-amber-500' };
    }
    // Default: Open
    return { classes: 'bg-red-50 text-red-700 border border-red-200/60', dotColor: 'bg-red-500' };
}

