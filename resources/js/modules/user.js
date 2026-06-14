import { apiRequest, showToast } from "./api.js";

export async function loadUsers() {
    try {
        const res = await apiRequest("/users");
        const tbody = document.getElementById("userTableBody");
        if (tbody) {
            tbody.innerHTML = "";
            (Array.isArray(res) ? res : (res.data || [])).forEach(user => {
                const roleClass = user.role === 'Admin'
                    ? 'bg-rose-50 text-rose-700 border border-rose-200/60'
                    : (user.role === 'Project Manager' ? 'bg-indigo-50 text-indigo-700 border border-indigo-200/60' : 'bg-emerald-50 text-emerald-700 border border-emerald-200/60');

                const initials = user.name ? user.name.split(' ').map(n => n[0]).join('').substring(0, 2).toUpperCase() : 'US';

                tbody.innerHTML += `
                    <tr class="group hover:bg-slate-50/50 transition-colors duration-150">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center">
                                <div class="h-9 w-9 rounded-full bg-gradient-to-br from-indigo-500 to-purple-600 text-white flex items-center justify-center font-bold text-xs mr-3 shadow-sm flex-shrink-0">
                                    ${initials}
                                </div>
                                <span class="text-sm font-semibold text-slate-700 group-hover:text-slate-900 transition-colors">${user.name}</span>
                            </div>
                        </td>
                        <td class="px-6 py-4 text-sm text-slate-400 font-medium">${user.email}</td>
                        <td class="px-6 py-4">
                            <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-[11px] font-bold ${roleClass}">
                                ${user.role}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-center">
                            <div class="flex items-center justify-center gap-2">
                                <button class="edit-usr-btn inline-flex items-center justify-center w-8 h-8 rounded-lg text-indigo-600 hover:bg-indigo-50 active:scale-[0.93] transition-all" data-id="${user.id}" data-name="${user.name}" data-email="${user.email}" data-role="${user.role}" title="Ubah User">
                                    <svg class="w-4.5 h-4.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.2"><path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                </button>
                                <button class="delete-usr-btn inline-flex items-center justify-center w-8 h-8 rounded-lg text-rose-600 hover:bg-rose-50 active:scale-[0.93] transition-all" data-id="${user.id}" title="Hapus User">
                                    <svg class="w-4.5 h-4.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.2"><path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                </button>
                            </div>
                        </td>
                    </tr>
                `;
            });
            attachUserActions();
        }
    } catch (err) {
        showToast("Gagal memuat data pengguna.", false);
    }
}

function attachUserActions() {
    document.querySelectorAll(".edit-usr-btn").forEach(btn => {
        btn.addEventListener("click", async () => {
            const id = btn.dataset.id;
            const name = prompt("Edit Nama User:", btn.dataset.name);
            if (!name) return;
            const email = prompt("Edit Email:", btn.dataset.email);
            if (!email) return;
            const role = prompt("Edit Role (Admin/Project Manager/Software QA):", btn.dataset.role);
            if (!["Admin", "Project Manager", "Software QA"].includes(role)) return;

            try {
                await apiRequest(`/users/${id}`, "PUT", { name, email, role });
                showToast("User berhasil diperbarui!");
                loadUsers();
            } catch (err) {
                showToast(err.message, false);
            }
        });
    });

    document.querySelectorAll(".delete-usr-btn").forEach(btn => {
        btn.addEventListener("click", async () => {
            if (confirm("Hapus user ini?")) {
                try {
                    await apiRequest(`/users/${btn.dataset.id}`, "DELETE");
                    showToast("User berhasil dihapus.");
                    loadUsers();
                } catch (err) {
                    showToast("Gagal menghapus user.", false);
                }
            }
        });
    });
}

export function setupUser() {
    const addBtn = document.getElementById("addUserButton");
    if (addBtn) {
        addBtn.addEventListener("click", async () => {
            const name = prompt("Nama Lengkap:");
            if (!name) return;
            const email = prompt("Alamat Email:");
            if (!email) return;
            const role = prompt("Role:", "Software QA");
            const password = prompt("Masukkan Password (Min. 8 Karakter):");

            try {
                await apiRequest("/users", "POST", { name, email, role, password });
                showToast("User berhasil ditambahkan!");
                loadUsers();
            } catch (err) {
                showToast(err.message, false);
            }
        });
    }
}
