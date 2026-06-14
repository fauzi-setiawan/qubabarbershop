import { apiRequest, showToast } from "./api.js";
import {
    loadReportedBugs,
    attachFailedActions,
    setupReportingTabs
} from "./bug-report.js";

let charts = {};

export async function loadReportingDashboard() {
    try {
        const res = await apiRequest("/projects");
        const list = document.getElementById("projectList");
        if (list) {
            list.innerHTML = "";
            (Array.isArray(res) ? res : (res.data || [])).forEach(p => {
                const prioClass = p.priority === 'High'
                    ? 'bg-rose-50 text-rose-700 border border-rose-200/50'
                    : (p.priority === 'Low' ? 'bg-sky-50 text-sky-700 border border-sky-200/50' : 'bg-amber-50 text-amber-700 border border-amber-200/50');

                const card = document.createElement("div");
                card.className = "group relative overflow-hidden bg-gradient-to-br from-white to-slate-50/50 p-6 rounded-2xl border border-slate-200/80 shadow-[0_1px_3px_rgba(0,0,0,0.04)] cursor-pointer hover:shadow-lg hover:border-indigo-500/50 transition-all duration-300 hover:-translate-y-1";
                card.innerHTML = `
                    <div class="absolute top-0 left-0 w-full h-1 bg-gradient-to-r from-blue-500 to-indigo-500 opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                    <div class="flex items-center gap-2 mb-3">
                        <div class="w-8 h-8 rounded-lg bg-indigo-50 text-indigo-600 flex items-center justify-center group-hover:bg-indigo-600 group-hover:text-white transition-all duration-300 flex-shrink-0">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2z"></path></svg>
                        </div>
                        <h3 class="font-bold text-sm md:text-base text-slate-800 group-hover:text-indigo-600 transition-colors truncate">${p.name}</h3>
                    </div>
                    <p class="text-xs text-slate-400 leading-relaxed h-10 overflow-hidden line-clamp-2">${p.description || 'Tidak ada deskripsi.'}</p>
                    <div class="flex justify-between items-center mt-5 pt-3 border-t border-slate-100">
                        <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-[10px] font-bold ${prioClass}">
                            ${p.priority || 'Medium'} Priority
                        </span>
                        <span class="text-[11px] text-indigo-600 font-semibold flex items-center gap-1 group-hover:translate-x-1 transition-transform">
                            Lihat Laporan
                            <svg class="w-3 h-3" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"></path></svg>
                        </span>
                    </div>
                `;
                card.addEventListener("click", () => openProjectReport(p.id));
                list.appendChild(card);
            });
        }
    } catch (err) {
        showToast("Gagal mengambil data proyek.", false);
    }

    setupReportingTabs();
    await loadFailedTests();


}

export async function loadFailedTests() {
    try {
        const res = await apiRequest("/executions");
        const tbody = document.getElementById("failedTestList");
        if (!tbody) return;

        tbody.innerHTML = "";

        // Filter: hanya test yang Fail dan belum punya bug_report
        const failedTests = res.data.filter(exec =>
            exec.status === "Fail" && !exec.bug_report
        );

        // Update queue badge count
        const queueBadge = document.getElementById("queueBadge");
        if (queueBadge) queueBadge.textContent = failedTests.length;

        if (failedTests.length === 0) {
            tbody.innerHTML = `
                <tr>
                    <td colspan="5" class="text-center py-16">
                        <div class="flex flex-col items-center gap-3">
                            <div class="w-14 h-14 rounded-2xl bg-emerald-50 flex items-center justify-center">
                                <svg class="w-7 h-7 text-emerald-300" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                            </div>
                            <p class="text-sm font-semibold text-slate-400">Tidak ada bug dalam antrean</p>
                            <p class="text-xs text-slate-300">Semua test case yang gagal sudah dilaporkan</p>
                        </div>
                    </td>
                </tr>`;
            return;
        }

        failedTests.forEach((exec, index) => {
            const tcName = exec.test_case?.name ?? "-";
            const tcModule = exec.test_case?.module?.name ?? "-";
            const testerName = exec.tester?.name ?? "Sistem";

            const row = document.createElement("tr");
            row.className = "group transition-colors duration-150";

            row.innerHTML = `
                <td class="px-6 py-3.5 text-center">
                    <span class="inline-flex items-center justify-center min-w-[36px] px-2 py-1 rounded-lg bg-slate-100 text-[11px] font-bold text-slate-600 font-mono">
                        ${index + 1}
                    </span>
                </td>
                <td class="px-6 py-3.5">
                    <div>
                        <span class="text-sm font-medium text-slate-700 group-hover:text-slate-900 transition-colors">${tcName}</span>
                        <div class="flex items-center gap-1.5 mt-1">
                            <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded bg-red-50 text-[10px] font-bold text-red-600 border border-red-100">
                                <span class="w-1 h-1 rounded-full bg-red-500"></span>
                                FAIL
                            </span>
                        </div>
                    </div>
                </td>
                <td class="px-6 py-3.5 text-center">
                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-lg bg-indigo-50 text-[11px] font-semibold text-indigo-600">
                        <svg class="w-3 h-3" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path></svg>
                        ${tcModule}
                    </span>
                </td>
                <td class="px-6 py-3.5 text-center">
                    <div class="flex items-center justify-center gap-2">
                        <div class="w-6 h-6 rounded-full bg-gradient-to-br from-indigo-400 to-purple-500 flex items-center justify-center text-[9px] font-bold text-white shadow-sm">
                            ${testerName.charAt(0).toUpperCase()}
                        </div>
                        <span class="text-xs font-medium text-slate-600">${testerName}</span>
                    </div>
                </td>
                <td class="px-6 py-3.5 text-center">
                    <button
                        class="trello-report-btn group/btn inline-flex items-center gap-1.5 bg-gradient-to-r from-red-600 to-rose-600 hover:from-red-700 hover:to-rose-700 text-white text-[11px] font-semibold px-3.5 py-2 rounded-xl shadow-sm shadow-red-200 hover:shadow-md hover:shadow-red-200 transition-all duration-200 active:scale-[0.97]"
                        data-id="${exec.id}"
                        data-name="${tcName}">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z"></path></svg>
                        Report Trello
                    </button>
                </td>
            `;

            tbody.appendChild(row);
        });

        attachFailedActions();

    } catch (err) {
        console.error(err);
        showToast("Gagal memuat antrean bug", false);
    }
}

async function openProjectReport(id) {
    document.getElementById("projectSelectionContainer").classList.add("hidden");
    document.getElementById("projectDashboardContainer").classList.replace("hidden", "flex");

    try {
        const result = await apiRequest(`/projects/${id}/report`);
        const project = result.data;

        document.getElementById("dashboardProjectName").textContent = project.projectName;
        const sumData = project.datasets.map(ds => ds.data.reduce((a, b) => a + b, 0));

        renderDoughnutChart(sumData);
        renderBarChart(project.labels, project.datasets);
    } catch (err) {
        showToast("Gagal memproses data laporan proyek.", false);
    }
}

function renderDoughnutChart(data) {
    if (charts.doughnut) charts.doughnut.destroy();
    const ctx = document.getElementById("statusDoughnutChart").getContext("2d");
    charts.doughnut = new Chart(ctx, {
        type: "doughnut",
        data: {
            labels: ["Pass", "Fail", "Blocked"],
            datasets: [{ data, backgroundColor: ["#10B981", "#EF4444", "#F59E0B"] }]
        },
        options: { responsive: true, maintainAspectRatio: false }
    });
}

function renderBarChart(labels, datasets) {
    if (charts.bar) charts.bar.destroy();
    const ctx = document.getElementById("moduleBarChart").getContext("2d");
    charts.bar = new Chart(ctx, {
        type: "bar",
        data: { labels, datasets },
        options: { responsive: true, maintainAspectRatio: false, scales: { x: { stacked: true }, y: { stacked: true } } }
    });
}

export function setupReporting() {
    const backBtn = document.getElementById("backToProjects");
    if (backBtn) {
        backBtn.addEventListener("click", () => {
            document.getElementById("projectDashboardContainer").classList.replace("flex", "hidden");
            document.getElementById("projectSelectionContainer").classList.remove("hidden");
        });
    }
}
