import { apiRequest, showToast} from "./api.js";
import { showFormModal, showConfirmModal } from "./modal.js";





let globalProjectsData = [];
let globalModulesData = [];
let globalModuleTestCasesData = [];

export async function loadProjects() {
    try {
        const res = await apiRequest("/projects");
        console.log('API RESPONSE PROJECTS:', res); globalProjectsData = res.data ? res.data : res;
        renderProjectsTable();
    } catch (err) {
        showToast("Gagal memuat daftar project.", false);
    }
}

function renderProjectsTable() {
    const tbody = document.getElementById("projectTableBody");
    if (tbody) {
        tbody.innerHTML = "";
        if (globalProjectsData.length === 0) {
            tbody.innerHTML = `<tr><td colspan="4" class="text-center py-6 text-gray-500">Tidak ada project aktif.</td></tr>`;
            return;
        }

        const paginated = globalProjectsData;

        paginated.forEach(p => {
            const prioClass = p.priority === 'High'
                ? 'bg-rose-50 text-rose-700 border border-rose-200/60'
                : (p.priority === 'Low' ? 'bg-sky-50 text-sky-700 border border-sky-200/60' : 'bg-amber-50 text-amber-700 border border-amber-200/60');

            tbody.innerHTML += `
                <tr class="group hover:bg-slate-50/50 cursor-pointer transition-colors duration-150 project-row" data-id="${p.id}" data-name="${p.name}">
                    <td class="px-6 py-4 text-sm font-semibold text-slate-700 group-hover:text-slate-900 transition-colors">${p.name}</td>
                    <td class="px-6 py-4 text-sm text-slate-400 max-w-xs truncate" title="${p.description || ''}">${p.description || '-'}</td>
                    <td class="px-6 py-4">
                        <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-[11px] font-bold ${prioClass}">
                            ${p.priority || 'Medium'}
                        </span>
                    </td>
                    <td class="px-6 py-4 text-center">
                        <div class="flex items-center justify-center gap-2">
                            <button class="manage-module-btn inline-flex items-center justify-center w-8 h-8 rounded-lg text-teal-600 hover:bg-teal-50 active:scale-[0.93] transition-all" data-id="${p.id}" data-name="${p.name}" title="Atur Modul">
                                <svg class="w-4.5 h-4.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.2"><path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 10h16M4 14h16M4 18h16"></path></svg>
                            </button>
                            <button class="edit-proj-btn inline-flex items-center justify-center w-8 h-8 rounded-lg text-indigo-600 hover:bg-indigo-50 active:scale-[0.93] transition-all" data-id="${p.id}" data-name="${p.name}" data-desc="${p.description}" data-priority="${p.priority}" title="Ubah Project">
                                <svg class="w-4.5 h-4.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.2"><path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                            </button>
                            <button class="delete-proj-btn inline-flex items-center justify-center w-8 h-8 rounded-lg text-rose-600 hover:bg-rose-50 active:scale-[0.93] transition-all" data-id="${p.id}" data-name="${p.name}" title="Hapus Project">
                                <svg class="w-4.5 h-4.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.2"><path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                            </button>
                        </div>
                    </td>
                </tr>
            `;
        });
        attachProjectActions();
    }
}

function attachProjectActions() {
    document.querySelectorAll(".project-row").forEach(row => {
        row.addEventListener("click", (e) => {
            if (e.target.closest("button")) return;
            showProjectDetail(row.dataset.id, row.dataset.name);
        });
    });

    document.querySelectorAll(".manage-module-btn").forEach(btn => {
        btn.addEventListener("click", () => {
            showProjectDetail(btn.dataset.id, btn.dataset.name);
        });
    });

    document.querySelectorAll(".edit-proj-btn").forEach(btn => {
        btn.addEventListener("click", async () => {
            const id = btn.dataset.id;

            const result = await showFormModal({
                title: "Ubah Project",
                fields: [
                    { name: "name", label: "Nama Project", type: "text", placeholder: "Masukkan nama project...", value: btn.dataset.name, required: true },
                    { name: "description", label: "Deskripsi Project", type: "textarea", placeholder: "Masukkan deskripsi project...", value: btn.dataset.desc, required: true },
                    { name: "priority", label: "Prioritas", type: "select", options: ["High", "Medium", "Low"], value: btn.dataset.priority, required: true }
                ],
                submitText: "Perbarui Project"
            });

            if (!result) return;

            try {
                await apiRequest(`/projects/${id}`, "PUT", result);
                showToast("Project berhasil diperbarui!");
                loadProjects();
            } catch (err) {
                showToast(err.message, false);
            }
        });
    });

    document.querySelectorAll(".delete-proj-btn").forEach(btn => {
        btn.addEventListener("click", async () => {
            const id = btn.dataset.id;
            const name = btn.dataset.name || "Project";

            const confirmDelete = await showConfirmModal({
                title: "Hapus Project?",
                message: `Apakah Anda yakin ingin menghapus project "${name}"? Seluruh modul dan skenario terkait akan ikut terhapus. Tindakan ini tidak dapat dibatalkan.`,
                confirmText: "Ya, Hapus",
                isDanger: true
            });

            if (confirmDelete) {
                try {
                    await apiRequest(`/projects/${id}`, "DELETE");
                    showToast("Project berhasil dihapus.");
                    loadProjects();
                } catch (err) {
                    showToast("Gagal menghapus project.", false);
                }
            }
        });
    });
}

export function setupProject() {
    const addBtn = document.getElementById("addProjectButton");
    if (addBtn) {
        addBtn.addEventListener("click", async () => {
            const result = await showFormModal({
                title: "Tambah Project Baru",
                fields: [
                    { name: "name", label: "Nama Project", type: "text", placeholder: "Masukkan nama project...", required: true },
                    { name: "description", label: "Deskripsi Project", type: "textarea", placeholder: "Masukkan deskripsi project...", required: true },
                    { name: "priority", label: "Prioritas", type: "select", options: ["High", "Medium", "Low"], value: "Medium", required: true }
                ],
                submitText: "Simpan Project"
            });

            if (!result) return;

            try {
                await apiRequest("/projects", "POST", result);
                showToast("Project baru berhasil disimpan!");
                loadProjects();
            } catch (err) {
                showToast(err.message, false);
            }
        });
    }

    const backBtn = document.getElementById("backToProjectsBtn");
    if (backBtn) {
        backBtn.addEventListener("click", () => {
            document.getElementById("projectDetailView").classList.add("hidden");
            document.getElementById("projectListView").classList.remove("hidden");
        });
    }

    const backToModulesBtn = document.getElementById("backToModulesBtn");
    if (backToModulesBtn) {
        backToModulesBtn.addEventListener("click", () => {
            document.getElementById("moduleDetailView").classList.add("hidden");
            document.getElementById("projectDetailView").classList.remove("hidden");
        });
    }

    const addModuleBtn = document.getElementById("addModuleButton");
    if (addModuleBtn) {
        addModuleBtn.addEventListener("click", async () => {
            const projectId = addModuleBtn.dataset.projectId;
            if (!projectId) return;

            const result = await showFormModal({
                title: "Tambah Modul Baru",
                fields: [
                    { name: "name", label: "Nama Modul", type: "text", placeholder: "Masukkan nama modul...", required: true }
                ],
                submitText: "Simpan Modul"
            });

            if (!result) return;

            try {
                await apiRequest(`/projects/${projectId}/modules`, "POST", result);
                showToast("Modul berhasil ditambahkan!");
                loadModules(projectId);
            } catch (err) {
                showToast(err.message, false);
            }
        });
    }
}

let currentProjectId = null;

export function showProjectDetail(projectId, projectName) {
    currentProjectId = projectId;
    document.getElementById("projectListView").classList.add("hidden");
    document.getElementById("projectDetailView").classList.remove("hidden");

    const titleEl = document.getElementById("detailProjectName");
    if (titleEl) titleEl.textContent = `Modul: ${projectName}`;

    const addModuleBtn = document.getElementById("addModuleButton");
    if (addModuleBtn) addModuleBtn.dataset.projectId = projectId;

    loadModules(projectId);
}

export async function loadModules(projectId) {
    try {
        const res = await apiRequest(`/projects/${projectId}/modules`);
        globalModulesData = Array.isArray(res) ? res : (res.data || []);
        renderModulesTable();
    } catch (err) {
        showToast("Gagal memuat modul.", false);
    }
}

function renderModulesTable() {
    const tbody = document.getElementById("moduleTableBody");
    if (tbody) {
        tbody.innerHTML = "";
        if (!globalModulesData || globalModulesData.length === 0) {
            tbody.innerHTML = `<tr><td colspan="2" class="text-center py-6 text-gray-500">Tidak ada modul untuk project ini.</td></tr>`;
            return;
        }

        const paginated = globalModulesData;

        paginated.forEach(m => {
            tbody.innerHTML += `
                <tr class="group hover:bg-slate-50/50 cursor-pointer transition-colors duration-150 module-row" data-id="${m.id}" data-name="${m.name}">
                    <td class="px-6 py-4 text-sm font-semibold text-slate-700 group-hover:text-slate-900 transition-colors">${m.name}</td>
                    <td class="px-6 py-4 text-center">
                        <div class="flex items-center justify-center gap-2">
                            <button class="detail-module-btn inline-flex items-center justify-center w-8 h-8 rounded-lg text-teal-600 hover:bg-teal-50 active:scale-[0.93] transition-all" data-id="${m.id}" data-name="${m.name}" title="Lihat Test Case">
                                <svg class="w-4.5 h-4.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.2"><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                            </button>
                            <button class="edit-module-btn inline-flex items-center justify-center w-8 h-8 rounded-lg text-indigo-600 hover:bg-indigo-50 active:scale-[0.93] transition-all" data-id="${m.id}" data-name="${m.name}" title="Ubah Modul">
                                <svg class="w-4.5 h-4.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.2"><path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                            </button>
                            <button class="delete-module-btn inline-flex items-center justify-center w-8 h-8 rounded-lg text-rose-600 hover:bg-rose-50 active:scale-[0.93] transition-all" data-id="${m.id}" data-name="${m.name}" title="Hapus Modul">
                                <svg class="w-4.5 h-4.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.2"><path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                            </button>
                        </div>
                    </td>
                </tr>
            `;
        });

        document.querySelectorAll(".module-row").forEach(row => {
            row.addEventListener("click", (e) => {
                if (e.target.closest("button")) return;
                showModuleDetail(row.dataset.id, row.dataset.name);
            });
        });

        document.querySelectorAll(".detail-module-btn").forEach(btn => {
            btn.addEventListener("click", () => {
                showModuleDetail(btn.dataset.id, btn.dataset.name);
            });
        });

        document.querySelectorAll(".edit-module-btn").forEach(btn => {
            btn.addEventListener("click", async () => {
                const id = btn.dataset.id;
                const name = btn.dataset.name;

                const result = await showFormModal({
                    title: "Ubah Modul",
                    fields: [
                        { name: "name", label: "Nama Modul", type: "text", placeholder: "Masukkan nama modul...", value: name, required: true }
                    ],
                    submitText: "Perbarui Modul"
                });

                if (!result) return;

                try {
                    await apiRequest(`/modules/${id}`, "PUT", result);
                    showToast("Modul berhasil diperbarui!");
                    loadModules(currentProjectId);
                } catch (err) {
                    showToast(err.message, false);
                }
            });
        });

        document.querySelectorAll(".delete-module-btn").forEach(btn => {
            btn.addEventListener("click", async () => {
                const id = btn.dataset.id;
                const name = btn.dataset.name;
                const confirmDelete = await showConfirmModal({
                    title: "Hapus Modul?",
                    message: `Apakah Anda yakin ingin menghapus modul "${name}"?`,
                    confirmText: "Ya, Hapus",
                    isDanger: true
                });
                if (confirmDelete) {
                    try {
                        await apiRequest(`/modules/${id}`, "DELETE");
                        showToast("Modul berhasil dihapus.");
                        loadModules(currentProjectId);
                    } catch (err) {
                        showToast("Gagal menghapus modul.", false);
                    }
                }
            });
        });
    }
}

export function showModuleDetail(moduleId, moduleName) {
    document.getElementById("projectDetailView").classList.add("hidden");
    document.getElementById("moduleDetailView").classList.remove("hidden");

    const titleEl = document.getElementById("detailModuleName");
    if (titleEl) titleEl.textContent = `Test Case untuk Modul: ${moduleName}`;

    loadModuleTestCases(moduleId);
}

export async function loadModuleTestCases(moduleId) {
    try {
        const res = await apiRequest(`/modules/${moduleId}/test-cases`);
        globalModuleTestCasesData = Array.isArray(res) ? res : (res.data || []);
        renderModuleTestCasesTable();
    } catch (err) {
        showToast("Gagal memuat test case.", false);
    }
}

function renderModuleTestCasesTable() {
    const tbody = document.getElementById("moduleTestCaseTableBody");
    if (tbody) {
        tbody.innerHTML = "";
        if (!globalModuleTestCasesData || globalModuleTestCasesData.length === 0) {
            tbody.innerHTML = `<tr><td colspan="7" class="text-center py-6 text-gray-500">Tidak ada test case untuk modul ini.</td></tr>`;
            return;
        }

        globalModuleTestCasesData.forEach(tc => {
            const tcId = `TC-${String(tc.id).padStart(3, '0')}`;
            const prioClass = tc.priority === 'High'
                ? 'bg-rose-50 text-rose-700 border border-rose-200/60'
                : (tc.priority === 'Low' ? 'bg-sky-50 text-sky-700 border border-sky-200/60' : 'bg-amber-50 text-amber-700 border border-amber-200/60');

            const catClass = tc.category === 'Positive Test'
                ? 'bg-emerald-50 text-emerald-700 border border-emerald-200/60'
                : (tc.category === 'Negative Test' ? 'bg-rose-50 text-rose-700 border border-rose-200/60' : 'bg-indigo-50 text-indigo-700 border border-indigo-200/60');

            const sourceClass = tc.source === 'ai'
                ? 'bg-purple-50 text-purple-700 border border-purple-200/60'
                : 'bg-slate-50 text-slate-600 border border-slate-200/60';

            const sourceText = tc.source === 'ai' ? 'AI' : 'Manual';

            tbody.innerHTML += `
                <tr class="hover:bg-slate-50/50 transition-colors duration-150">
                    <td class="px-6 py-4 text-left">
                        <span class="inline-flex items-center justify-center min-w-[52px] px-2 py-1 rounded-lg bg-slate-100 text-[11px] font-bold text-slate-600 tracking-wide font-mono">
                            ${tcId}
                        </span>
                    </td>
                    <td class="px-6 py-4 font-semibold text-slate-700 break-words min-w-[150px]" title="${tc.name || ''}">${tc.name || '-'}</td>
                    <td class="px-6 py-4 text-xs text-slate-500 whitespace-pre-line break-words min-w-[220px] max-w-xs" title="${tc.steps || ''}">${tc.steps || '-'}</td>
                    <td class="px-6 py-4 text-xs text-slate-500 whitespace-pre-line break-words min-w-[220px] max-w-xs" title="${tc.expected_result || ''}">${tc.expected_result || '-'}</td>
                    <td class="px-6 py-4">
                        <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-lg text-[11px] font-semibold ${catClass}">
                            ${tc.category || 'Positive Test'}
                        </span>
                    </td>
                    <td class="px-6 py-4">
                        <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-[11px] font-bold ${prioClass}">
                            ${tc.priority || 'Medium'}
                        </span>
                    </td>
                    <td class="px-6 py-4">
                        <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-lg text-[11px] font-semibold ${sourceClass}">
                            ${sourceText}
                        </span>
                    </td>
                </tr>
            `;
        });
    }
}

