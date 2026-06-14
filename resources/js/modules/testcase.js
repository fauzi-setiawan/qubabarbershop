import { apiRequest, showToast } from "./api.js";
import { showFormModal, showConfirmModal, showDetailModal } from "./modal.js";

let globalTestCasesData = [];
let projectsList = [];
let modulesList = []; // list of all modules from all projects

export async function loadTestCases() {
    try {
        const res = await apiRequest("/test-cases");
        globalTestCasesData = Array.isArray(res) ? res : (res.data || []);
        
        // Load filter data dynamically from database
        await loadFilterData();
        
        renderTestCasesTable();
    } catch (err) {
        showToast("Gagal memuat data test case.", false);
    }
}

async function loadFilterData() {
    try {
        const projRes = await apiRequest("/projects");
        projectsList = Array.isArray(projRes) ? projRes : (projRes.data || []);
        
        const projectFilter = document.getElementById("tcProjectFilter");
        if (projectFilter) {
            projectFilter.innerHTML = '<option value="">Semua Project</option>';
            projectsList.forEach(p => {
                projectFilter.innerHTML += `<option value="${p.id}">${p.name}</option>`;
            });
        }

        // Fetch all modules for all projects
        modulesList = [];
        for (const p of projectsList) {
            try {
                const modRes = await apiRequest(`/projects/${p.id}/modules`);
                const mods = Array.isArray(modRes) ? modRes : (modRes.data || []);
                mods.forEach(m => {
                    modulesList.push({
                        id: m.id,
                        name: m.name,
                        project_id: p.id
                    });
                });
            } catch (e) {
                console.error(`Gagal memuat modul untuk project ${p.id}:`, e);
            }
        }
        
        updateModuleFilterOptions();
    } catch (err) {
        console.error("Gagal memuat data filter proyek & modul:", err);
    }
}

function updateModuleFilterOptions() {
    const projectFilter = document.getElementById("tcProjectFilter");
    const moduleFilter = document.getElementById("tcModuleFilter");
    if (!moduleFilter) return;

    const selectedProjectId = projectFilter ? projectFilter.value : "";
    
    moduleFilter.innerHTML = '<option value="">Semua Modul</option>';
    
    const filteredMods = selectedProjectId 
        ? modulesList.filter(m => String(m.project_id) === String(selectedProjectId))
        : modulesList;
        
    filteredMods.forEach(m => {
        moduleFilter.innerHTML += `<option value="${m.id}">${m.name}</option>`;
    });
}

function renderTestCasesTable() {
    const tbody = document.getElementById("testCaseTableBody");
    if (!tbody) return;

    tbody.innerHTML = "";

    const searchQuery = document.getElementById("testCaseSearch")?.value.toLowerCase().trim() || "";
    const selectedProjectId = document.getElementById("tcProjectFilter")?.value || "";
    const selectedModuleId = document.getElementById("tcModuleFilter")?.value || "";

    const filteredData = globalTestCasesData.filter(tc => {
        // Match project
        const matchProject = !selectedProjectId || (tc.module?.project_id && String(tc.module.project_id) === String(selectedProjectId));
        
        // Match module
        const matchModule = !selectedModuleId || (tc.module_id && String(tc.module_id) === String(selectedModuleId));
        
        // Match search query (name or expected_result or steps)
        const matchSearch = !searchQuery || 
            (tc.name && tc.name.toLowerCase().includes(searchQuery)) ||
            (tc.expected_result && tc.expected_result.toLowerCase().includes(searchQuery)) ||
            (tc.steps && tc.steps.toLowerCase().includes(searchQuery));

        return matchProject && matchModule && matchSearch;
    });

    if (filteredData.length === 0) {
        tbody.innerHTML = `
            <tr>
                <td colspan="5" class="text-center py-16">
                    <div class="flex flex-col items-center gap-3">
                        <div class="w-14 h-14 rounded-2xl bg-slate-100 flex items-center justify-center">
                            <svg class="w-7 h-7 text-slate-300" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z"></path>
                            </svg>
                        </div>
                        <p class="text-sm font-semibold text-slate-400">Tidak ada test case yang sesuai</p>
                        <p class="text-xs text-slate-300">Coba ubah filter atau kata kunci pencarian</p>
                    </div>
                </td>
            </tr>`;
        return;
    }

    filteredData.forEach(tc => {
        const tcId = `TC-${String(tc.id).padStart(3, '0')}`;
        const prioClass = tc.priority === 'High'
            ? 'bg-rose-50 text-rose-700 border border-rose-200/60'
            : (tc.priority === 'Low' ? 'bg-sky-50 text-sky-700 border border-sky-200/60' : 'bg-amber-50 text-amber-700 border border-amber-200/60');

        tbody.innerHTML += `
            <tr class="group hover:bg-slate-50/50 cursor-pointer transition-colors duration-150 testcase-row" data-id="${tc.id}">
                <td class="px-6 py-4 text-left">
                    <span class="inline-flex items-center justify-center min-w-[52px] px-2 py-1 rounded-lg bg-slate-100 text-[11px] font-bold text-slate-600 tracking-wide font-mono">
                        ${tcId}
                    </span>
                </td>
                <td class="px-6 py-4">
                    <div class="text-sm font-semibold text-slate-700 group-hover:text-indigo-650 transition-colors">${tc.name}</div>
                    <div class="text-[11px] text-slate-400 mt-1 max-w-md truncate" title="${tc.expected_result || ''}">${tc.expected_result || '-'}</div>
                </td>
                <td class="px-6 py-4">
                    <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-lg bg-indigo-50 text-[11px] font-semibold text-indigo-600 border border-indigo-100/50">
                        ${tc.module?.name || 'Bawaan'}
                    </span>
                </td>
                <td class="px-6 py-4">
                    <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-[11px] font-bold ${prioClass}">
                        ${tc.priority || 'Medium'}
                    </span>
                </td>
                <td class="px-6 py-4 text-center">
                    <div class="flex items-center justify-center gap-2">
                        <button class="run-tc-btn inline-flex items-center justify-center w-8 h-8 rounded-lg text-emerald-600 hover:bg-emerald-50 active:scale-[0.93] transition-all" data-id="${tc.id}" data-name="${tc.name}" title="Jalankan Skenario">
                            <svg class="w-4.5 h-4.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.2"><path stroke-linecap="round" stroke-linejoin="round" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z"></path></svg>
                        </button>
                        <button class="edit-tc-btn inline-flex items-center justify-center w-8 h-8 rounded-lg text-indigo-600 hover:bg-indigo-50 active:scale-[0.93] transition-all" data-id="${tc.id}" title="Ubah Skenario">
                            <svg class="w-4.5 h-4.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.2"><path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                        </button>
                        <button class="delete-tc-btn inline-flex items-center justify-center w-8 h-8 rounded-lg text-rose-600 hover:bg-rose-50 active:scale-[0.93] transition-all" data-id="${tc.id}" data-name="${tc.name}" title="Hapus Skenario">
                            <svg class="w-4.5 h-4.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.2"><path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                        </button>
                    </div>
                </td>
            </tr>
        `;
    });
    attachTestCaseActions();
}

function attachTestCaseActions() {
    document.querySelectorAll(".run-tc-btn").forEach(btn => {
        btn.addEventListener("click", async () => {
            const id = btn.dataset.id;
            const name = btn.dataset.name || "Skenario";

            const result = await showFormModal({
                title: `Eksekusi: ${name}`,
                fields: [
                    { name: "status", label: "Hasil Pengujian", type: "select", options: ["Pass", "Fail", "Blocked"], value: "Pass", required: true },
                    { name: "notes", label: "Catatan Pengujian (Opsional)", type: "textarea", placeholder: "Masukkan detail catatan atau kendala..." }
                ],
                submitText: "Simpan Hasil"
            });

            if (!result) return;

            try {
                await apiRequest("/executions", "POST", {
                    test_case_id: id,
                    status: result.status,
                    notes: result.notes
                });
                showToast("Eksekusi hasil tes berhasil dicatat!");
                loadTestCases();
            } catch (err) {
                showToast("Gagal mencatat eksekusi tes.", false);
            }
        });
    });

    document.querySelectorAll(".edit-tc-btn").forEach(btn => {
        btn.addEventListener("click", async () => {
            const id = parseInt(btn.dataset.id);
            const tc = globalTestCasesData.find(item => item.id === id);
            if (!tc) return;

            showToast("Memuat modul project...");
            let moduleOptions = [];
            try {
                const projRes = await apiRequest("/projects");
                const projects = Array.isArray(projRes) ? projRes : (projRes.data || []);
                for (const p of projects) {
                    const modRes = await apiRequest(`/projects/${p.id}/modules`);
                    (Array.isArray(modRes) ? modRes : (modRes.data || [])).forEach(m => {
                        moduleOptions.push({
                            value: m.id,
                            text: `${p.name} — ${m.name}`
                        });
                    });
                }
            } catch (e) {
                moduleOptions = [{ value: tc.module_id || 1, text: "Bawaan" }];
            }

            const result = await showFormModal({
                title: "Ubah Test Case",
                fields: [
                    { name: "module_id", label: "Pilih Project & Modul", type: "select", options: moduleOptions, value: tc.module_id, required: true },
                    { name: "name", label: "Nama Skenario Uji", type: "text", placeholder: "Contoh: Verifikasi login dengan akun valid...", value: tc.name, required: true },
                    { name: "steps", label: "Langkah-langkah Pengujian", type: "textarea", placeholder: "1. Buka aplikasi...\n2. Isi username...\n3. Klik tombol...", value: tc.steps, required: true },
                    { name: "expected_result", label: "Hasil yang Diharapkan", type: "textarea", placeholder: "Sistem harus masuk ke dashboard utama...", value: tc.expected_result, required: true },
                    { name: "priority", label: "Prioritas Pengujian", type: "select", options: ["High", "Medium", "Low"], value: tc.priority || "Medium", required: true }
                ],
                submitText: "Perbarui Test Case"
            });

            if (!result) return;

            try {
                await apiRequest(`/test-cases/${id}`, "PUT", result);
                showToast("Test case berhasil diperbarui!");
                loadTestCases();
            } catch (err) {
                showToast(err.message, false);
            }
        });
    });

    document.querySelectorAll(".delete-tc-btn").forEach(btn => {
        btn.addEventListener("click", async () => {
            const id = btn.dataset.id;
            const name = btn.dataset.name || "Skenario";

            const confirmDelete = await showConfirmModal({
                title: "Hapus Test Case?",
                message: `Apakah Anda yakin ingin menghapus skenario test case "${name}"? Tindakan ini tidak dapat dibatalkan.`,
                confirmText: "Ya, Hapus",
                isDanger: true
            });

            if (confirmDelete) {
                try {
                    await apiRequest(`/test-cases/${id}`, "DELETE");
                    showToast("Test case berhasil dihapus.");
                    loadTestCases();
                } catch (err) {
                    showToast("Gagal menghapus.", false);
                }
            }
        });
    });

    document.querySelectorAll(".testcase-row").forEach(row => {
        row.addEventListener("click", (e) => {
            // Biarkan tombol aksi bekerja tanpa membuka detail
            if (e.target.closest("button")) {
                return;
            }

            const id = parseInt(row.dataset.id);
            const tc = globalTestCasesData.find(item => item.id === id);
            if (tc) {
                const prioClass = tc.priority === 'High'
                    ? 'bg-rose-50 text-rose-700 border border-rose-200/60'
                    : (tc.priority === 'Low' ? 'bg-sky-50 text-sky-700 border border-sky-200/60' : 'bg-amber-50 text-amber-700 border border-amber-200/60');

                const moduleText = tc.module ? `${tc.module.project?.name || 'Proyek'} — ${tc.module.name}` : 'Bawaan';

                showDetailModal({
                    title: tc.name,
                    subtitle: `Project & Modul: ${moduleText}`,
                    badges: [
                        { text: tc.priority || "Medium", classes: prioClass },
                        { text: tc.category || "Skenario Uji", classes: "bg-indigo-50 text-indigo-750 border border-indigo-200/60" },
                        { text: tc.source === "ai" ? "Generated by AI" : "Manual", classes: tc.source === "ai" ? "bg-purple-50 text-purple-750 border border-purple-200/60" : "bg-slate-50 text-slate-700 border border-slate-200/60" }
                    ],
                    sections: [
                        {
                            label: "Langkah-langkah Pengujian",
                            icon: '<svg class="w-3.5 h-3.5 text-indigo-500" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path></svg>',
                            value: tc.steps || "",
                            isPre: true
                        },
                        {
                            label: "Hasil yang Diharapkan",
                            icon: '<svg class="w-3.5 h-3.5 text-emerald-500" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>',
                            value: tc.expected_result || "",
                            isPre: false
                        }
                    ]
                });
            }
        });
    });
}

export function setupTestCase() {
    const addBtn = document.getElementById("addTestCaseButton");
    if (addBtn) {
        addBtn.addEventListener("click", async () => {
            // Load list modul secara dinamis dari database
            showToast("Memuat modul project...");
            let moduleOptions = [];
            try {
                const projRes = await apiRequest("/projects");
                const projects = Array.isArray(projRes) ? projRes : (projRes.data || []);
                for (const p of projects) {
                    const modRes = await apiRequest(`/projects/${p.id}/modules`);
                    (Array.isArray(modRes) ? modRes : (modRes.data || [])).forEach(m => {
                        moduleOptions.push({
                            value: m.id,
                            text: `${p.name} — ${m.name}`
                        });
                    });
                }
            } catch (e) {
                moduleOptions = [{ value: 1, text: "Bawaan" }];
            }

            if (moduleOptions.length === 0) {
                showToast("Silakan buat project dan modul terlebih dahulu.", false);
                return;
            }

            const result = await showFormModal({
                title: "Tambah Test Case Baru",
                fields: [
                    { name: "module_id", label: "Pilih Project & Modul", type: "select", options: moduleOptions, required: true },
                    { name: "name", label: "Nama Skenario Uji", type: "text", placeholder: "Contoh: Verifikasi login dengan akun valid...", required: true },
                    { name: "steps", label: "Langkah-langkah Pengujian", type: "textarea", placeholder: "1. Buka aplikasi...\n2. Isi username...\n3. Klik tombol...", required: true },
                    { name: "expected_result", label: "Hasil yang Diharapkan", type: "textarea", placeholder: "Sistem harus masuk ke dashboard utama...", required: true },
                    { name: "priority", label: "Prioritas Pengujian", type: "select", options: ["High", "Medium", "Low"], value: "Medium", required: true }
                ],
                submitText: "Simpan Test Case"
            });

            if (!result) return;

            try {
                await apiRequest("/test-cases", "POST", result);
                showToast("Test case berhasil disimpan!");
                loadTestCases();
            } catch (err) {
                showToast(err.message, false);
            }
        });
    }

    // Wiring up local search and dropdown change event listeners
    const searchInput = document.getElementById("testCaseSearch");
    const projectFilter = document.getElementById("tcProjectFilter");
    const moduleFilter = document.getElementById("tcModuleFilter");

    if (searchInput) {
        searchInput.addEventListener("input", renderTestCasesTable);
    }
    if (projectFilter) {
        projectFilter.addEventListener("change", () => {
            updateModuleFilterOptions();
            renderTestCasesTable();
        });
    }
    if (moduleFilter) {
        moduleFilter.addEventListener("change", renderTestCasesTable);
    }
}
