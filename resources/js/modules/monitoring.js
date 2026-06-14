import { apiRequest, showToast } from "./api.js";

let globalExecutionsData = [];
let monitorPieChart = null;
let globalProjectsList = [];
let globalModulesList = [];

/**
 * Memuat data eksekusi dari API dan menyiapkan UI monitoring.
 */
export async function loadExecutions() {
    try {
        const res = await apiRequest("/executions");
        globalExecutionsData = Array.isArray(res) ? res : (res.data || []);

        // Load projects & modules dynamically from database
        try {
            const projRes = await apiRequest("/projects");
            globalProjectsList = Array.isArray(projRes) ? projRes : (projRes.data || []);

            const filterProj = document.getElementById("filterMonitorProject");
            if (filterProj) {
                filterProj.innerHTML = '<option value="all">Semua Project</option>';
                globalProjectsList.forEach(p => {
                    filterProj.innerHTML += `<option value="${p.id}">${p.name}</option>`;
                });
            }

            // Fetch modules for all projects
            globalModulesList = [];
            for (const p of globalProjectsList) {
                const modRes = await apiRequest(`/projects/${p.id}/modules`);
                const mods = Array.isArray(modRes) ? modRes : (modRes.data || []);
                mods.forEach(m => {
                    globalModulesList.push({
                        id: m.id,
                        name: m.name,
                        project_id: p.id
                    });
                });
            }
        } catch (e) {
            console.error("Gagal memuat filter proyek & modul di monitoring:", e);
        }

        updateMonitorModuleOptions();
        renderMonitoringDashboard();
    } catch (err) {
        showToast("Gagal memuat data monitoring.", false);
    }
}

/**
 * Update dropdown modul berdasarkan proyek yang dipilih
 */
function updateMonitorModuleOptions() {
    const filterProj = document.getElementById("filterMonitorProject");
    const filterModul = document.getElementById("filterMonitorModul");
    if (!filterModul) return;

    const selectedProjectId = filterProj ? filterProj.value : "all";
    filterModul.innerHTML = '<option value="all">Semua Modul</option>';

    const filtered = selectedProjectId === "all"
        ? globalModulesList
        : globalModulesList.filter(m => String(m.project_id) === String(selectedProjectId));

    filtered.forEach(m => {
        filterModul.innerHTML += `<option value="${m.name}">${m.name}</option>`;
    });
}

/**
 * Render seluruh dashboard monitoring: statistik, chart, dan tabel.
 */
function renderMonitoringDashboard() {
    const selectedProjectId = document.getElementById("filterMonitorProject")?.value || "all";
    const selectedModul = document.getElementById("filterMonitorModul")?.value || "all";
    const selectedStatus = document.getElementById("filterMonitorStatus")?.value || "all";

    // ── Filter Data ──
    const filteredData = globalExecutionsData.filter(exec => {
        const matchProject = selectedProjectId === "all" || 
            (exec.test_case?.module?.project_id && String(exec.test_case.module.project_id) === String(selectedProjectId));
        const matchModul = selectedModul === "all" || (exec.test_case?.module?.name === selectedModul);
        const matchStatus = selectedStatus === "all" || (exec.status === selectedStatus);
        return matchProject && matchModul && matchStatus;
    });

    // ── Hitung Statistik ──
    const counts = { Pass: 0, Fail: 0, Blocked: 0, "Not Executed": 0 };
    filteredData.forEach(exec => {
        if (counts[exec.status] !== undefined) counts[exec.status]++;
    });

    const total = counts.Pass + counts.Fail + counts.Blocked + counts["Not Executed"];

    // ── Update angka dengan animasi counter ──
    animateCounter("countPass", counts.Pass);
    animateCounter("countFail", counts.Fail);
    animateCounter("countBlocked", counts.Blocked);
    animateCounter("countNotExecute", counts["Not Executed"]);
    animateCounter("pieTotalCount", total);

    // ── Update row count text ──
    const rowCountEl = document.getElementById("tableRowCount");
    if (rowCountEl) rowCountEl.textContent = `Menampilkan ${filteredData.length} data`;

    // ── Gambar Doughnut Chart ──
    drawMonitorPieChart([counts.Pass, counts.Fail, counts.Blocked, counts["Not Executed"]]);

    // ── Render Tabel ──
    renderMonitoringTable(filteredData);
}

/**
 * Animasi counter dari angka lama ke angka baru.
 */
function animateCounter(elementId, targetValue) {
    const el = document.getElementById(elementId);
    if (!el) return;

    const currentValue = parseInt(el.textContent) || 0;
    if (currentValue === targetValue) return;

    const duration = 500; // ms
    const startTime = performance.now();

    function step(now) {
        const progress = Math.min((now - startTime) / duration, 1);
        const eased = progress === 1 ? 1 : 1 - Math.pow(2, -10 * progress);
        const value = Math.round(currentValue + (targetValue - currentValue) * eased);
        el.textContent = value;
        if (progress < 1) requestAnimationFrame(step);
    }

    requestAnimationFrame(step);
}

/**
 * Menggambar doughnut chart dengan tema premium.
 */
function drawMonitorPieChart(dataArr) {
    const ctx = document.getElementById("monitoringPieChart");
    if (!ctx) return;

    if (monitorPieChart) monitorPieChart.destroy();

    const total = dataArr.reduce((a, b) => a + b, 0);

    monitorPieChart = new Chart(ctx, {
        type: 'doughnut',
        data: {
            labels: ['Pass', 'Fail', 'Blocked', 'Not Execute'],
            datasets: [{
                data: total === 0 ? [1] : dataArr,
                backgroundColor: total === 0
                    ? ['#e2e8f0']
                    : ['#10b981', '#ef4444', '#334155', '#f59e0b'],
                borderColor: '#ffffff',
                borderWidth: 3,
                borderRadius: total === 0 ? 0 : 6,
                hoverBorderWidth: 0,
                hoverOffset: 8,
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            cutout: '68%',
            plugins: {
                legend: { display: false },
                tooltip: {
                    enabled: total > 0,
                    backgroundColor: 'rgba(15, 23, 42, 0.9)',
                    titleFont: { family: "'Inter', sans-serif", size: 12, weight: '600' },
                    bodyFont: { family: "'Inter', sans-serif", size: 11 },
                    padding: { top: 8, bottom: 8, left: 12, right: 12 },
                    cornerRadius: 10,
                    displayColors: true,
                    boxWidth: 8,
                    boxHeight: 8,
                    boxPadding: 4,
                    callbacks: {
                        label: function (context) {
                            const value = context.raw;
                            const percentage = total > 0 ? ((value / total) * 100).toFixed(1) : 0;
                            return ` ${context.label}: ${value} (${percentage}%)`;
                        }
                    }
                }
            },
            animation: {
                animateRotate: true,
                animateScale: true,
                duration: 800,
                easing: 'easeOutQuart'
            }
        }
    });
}

/**
 * Render tabel HTML dengan styling premium dan status badges.
 */
function renderMonitoringTable(filteredData) {
    const tbody = document.getElementById("monitoringTableBody");
    if (!tbody) return;

    tbody.innerHTML = "";

    if (filteredData.length === 0) {
        tbody.innerHTML = `
            <tr>
                <td colspan="7" class="text-center py-16">
                    <div class="flex flex-col items-center gap-3">
                        <div class="w-14 h-14 rounded-2xl bg-slate-100 flex items-center justify-center">
                            <svg class="w-7 h-7 text-slate-300" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z"></path>
                            </svg>
                        </div>
                        <p class="text-sm font-semibold text-slate-400">Tidak ada data yang sesuai</p>
                        <p class="text-xs text-slate-300">Coba ubah filter untuk melihat hasil lainnya</p>
                    </div>
                </td>
            </tr>`;
        return;
    }

    filteredData.forEach((exec, index) => {
        const tcId = `TC-${String(exec.test_case?.id || exec.id).padStart(3, '0')}`;
        const tcName = exec.test_case?.name || "Skenario Manual";
        const tcModule = exec.test_case?.module?.name || "-";
        const tcPriority = exec.test_case?.priority || "Medium";
        const testerName = exec.tester?.name || "Sistem";
        const notes = exec.notes || "-";

        let statusText = exec.status;
        if (statusText === 'Pass') statusText = 'Passed';
        if (statusText === 'Not Executed') statusText = 'Not Execute';

        const statusConfig = getStatusConfig(exec.status);
        const priorityConfig = getPriorityConfig(tcPriority);

        const row = document.createElement("tr");
        row.className = "group transition-colors duration-150";
        row.style.animationDelay = `${index * 30}ms`;

        row.innerHTML = `
            <td class="px-5 py-3.5 text-center">
                <span class="inline-flex items-center justify-center min-w-[52px] px-2 py-1 rounded-lg bg-slate-100 text-[11px] font-bold text-slate-600 tracking-wide font-mono">
                    ${tcId}
                </span>
            </td>
            <td class="px-5 py-3.5">
                <span class="text-sm font-medium text-slate-700 group-hover:text-slate-900 transition-colors duration-150">${tcName}</span>
            </td>
            <td class="px-5 py-3.5 text-center">
                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-lg bg-indigo-50 text-[11px] font-semibold text-indigo-600">
                    <svg class="w-3.5 h-3.5 text-slate-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path></svg>
                    ${tcModule}
                </span>
            </td>
            <td class="px-5 py-3.5 text-center">
                <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-lg text-[11px] font-bold ${priorityConfig.classes}">
                    ${priorityConfig.icon}
                    ${tcPriority}
                </span>
            </td>
            <td class="px-5 py-3.5 text-center">
                <div class="flex items-center justify-center gap-2">
                    <div class="w-6 h-6 rounded-full bg-gradient-to-br from-indigo-400 to-purple-500 flex items-center justify-center text-[9px] font-bold text-white shadow-sm">
                        ${testerName.charAt(0).toUpperCase()}
                    </div>
                    <span class="text-xs font-medium text-slate-600">${testerName}</span>
                </div>
            </td>
            <td class="px-5 py-3.5 text-center">
                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-[11px] font-bold tracking-wide ${statusConfig.classes} ${statusConfig.animation}">
                    <span class="w-1.5 h-1.5 rounded-full ${statusConfig.dotColor}"></span>
                    ${statusText}
                </span>
            </td>
            <td class="px-5 py-3.5">
                <span class="text-xs text-slate-500 line-clamp-2" title="${notes}">${notes}</span>
            </td>
        `;

        tbody.appendChild(row);
    });
}

/**
 * Mengembalikan konfigurasi warna & styling berdasarkan status.
 */
function getStatusConfig(status) {
    const configs = {
        'Pass': {
            classes: 'bg-emerald-50 text-emerald-700 border border-emerald-200/60',
            dotColor: 'bg-emerald-500',
            animation: ''
        },
        'Fail': {
            classes: 'bg-red-50 text-red-700 border border-red-200/60',
            dotColor: 'bg-red-500',
            animation: 'status-badge-fail'
        },
        'Blocked': {
            classes: 'bg-slate-100 text-slate-700 border border-slate-200/60',
            dotColor: 'bg-slate-500',
            animation: ''
        },
        'Not Executed': {
            classes: 'bg-amber-50 text-amber-700 border border-amber-200/60',
            dotColor: 'bg-amber-500',
            animation: ''
        }
    };
    return configs[status] || configs['Not Executed'];
}

/**
 * Mengembalikan konfigurasi warna & styling berdasarkan priority.
 */
function getPriorityConfig(priority) {
    const configs = {
        'High': {
            classes: 'bg-red-50 text-red-700 border border-red-200/60',
            icon: '<svg class="w-3 h-3" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M5 15l7-7 7 7"></path></svg>'
        },
        'Medium': {
            classes: 'bg-amber-50 text-amber-700 border border-amber-200/60',
            icon: '<svg class="w-3 h-3" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M20 12H4"></path></svg>'
        },
        'Low': {
            classes: 'bg-sky-50 text-sky-700 border border-sky-200/60',
            icon: '<svg class="w-3 h-3" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"></path></svg>'
        }
    };
    return configs[priority] || configs['Medium'];
}

/**
 * Mengunduh data monitoring yang aktif sesuai filter dalam format CSV.
 */
function downloadCSV() {
    const selectedProjectId = document.getElementById("filterMonitorProject")?.value || "all";
    const selectedModul = document.getElementById("filterMonitorModul")?.value || "all";
    const selectedStatus = document.getElementById("filterMonitorStatus")?.value || "all";

    const filteredData = globalExecutionsData.filter(exec => {
        const matchProject = selectedProjectId === "all" || 
            (exec.test_case?.module?.project_id && String(exec.test_case.module.project_id) === String(selectedProjectId));
        const matchModul = selectedModul === "all" || (exec.test_case?.module?.name === selectedModul);
        const matchStatus = selectedStatus === "all" || (exec.status === selectedStatus);
        return matchProject && matchModul && matchStatus;
    });

    if (filteredData.length === 0) {
        showToast("Tidak ada data untuk diunduh.", false);
        return;
    }

    // CSV Headers
    const headers = ["Test Case ID", "Nama Test Case", "Modul", "Prioritas", "Tester", "Status", "Catatan"];
    const csvRows = [headers.join(",")];
    
    filteredData.forEach(exec => {
        const tcId = `TC-${String(exec.test_case?.id || exec.id).padStart(3, '0')}`;
        const tcName = exec.test_case?.name || "Skenario Manual";
        const tcModule = exec.test_case?.module?.name || "-";
        const tcPriority = exec.test_case?.priority || "Medium";
        const testerName = exec.tester?.name || "Sistem";
        
        let statusText = exec.status;
        if (statusText === 'Pass') statusText = 'Passed';
        if (statusText === 'Not Executed') statusText = 'Not Execute';
        
        const notes = exec.notes || "-";
        
        const row = [
            `"${tcId.replace(/"/g, '""')}"`,
            `"${tcName.replace(/"/g, '""')}"`,
            `"${tcModule.replace(/"/g, '""')}"`,
            `"${tcPriority.replace(/"/g, '""')}"`,
            `"${testerName.replace(/"/g, '""')}"`,
            `"${statusText.replace(/"/g, '""')}"`,
            `"${notes.replace(/"/g, '""')}"`
        ];
        csvRows.push(row.join(","));
    });

    const csvContent = csvRows.join("\n");
    const blob = new Blob(["\ufeff" + csvContent], { type: 'text/csv;charset=utf-8;' });
    const url = URL.createObjectURL(blob);
    
    const link = document.createElement("a");
    link.setAttribute("href", url);
    
    const dateStr = new Date().toISOString().slice(0, 10);
    link.setAttribute("download", `Laporan_Monitoring_${dateStr}.csv`);
    document.body.appendChild(link);
    link.click();
    document.body.removeChild(link);
    URL.revokeObjectURL(url);
    
    showToast("Laporan berhasil diunduh.");
}

/**
 * Inisialisasi event listener untuk fitur monitoring.
 */
export function setupMonitoring() {
    const filterProj = document.getElementById("filterMonitorProject");
    const filterModul = document.getElementById("filterMonitorModul");
    const filterStatus = document.getElementById("filterMonitorStatus");
    const btnDownload = document.getElementById("downloadMonitorReport");

    if (filterProj) {
        filterProj.addEventListener("change", () => {
            updateMonitorModuleOptions();
            renderMonitoringDashboard();
        });
    }
    if (filterModul) filterModul.addEventListener("change", renderMonitoringDashboard);
    if (filterStatus) filterStatus.addEventListener("change", renderMonitoringDashboard);

    if (btnDownload) {
        btnDownload.addEventListener("click", () => {
            downloadCSV();
        });
    }
}