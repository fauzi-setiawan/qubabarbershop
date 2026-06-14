import { apiRequest, showToast } from "./api.js";

// ─── Konstanta kategori ────────────────────────────────────────────────────────
const CATEGORY_COLORS = {
    "Positive Test":   { bg: "bg-emerald-50",  text: "text-emerald-700",  border: "border-emerald-200" },
    "Negative Test":   { bg: "bg-rose-50",     text: "text-rose-700",     border: "border-rose-200"    },
    "Boundary Test":   { bg: "bg-amber-50",    text: "text-amber-700",    border: "border-amber-200"   },
    "Validation Test": { bg: "bg-violet-50",   text: "text-violet-700",   border: "border-violet-200"  },
    "UI/UX Test":      { bg: "bg-sky-50",      text: "text-sky-700",      border: "border-sky-200"     },
};

const PRIORITY_COLORS = {
    High:   "bg-rose-50 text-rose-700 border-rose-200",
    Medium: "bg-amber-50 text-amber-700 border-amber-200",
    Low:    "bg-sky-50 text-sky-700 border-sky-200",
};

// ─── State ─────────────────────────────────────────────────────────────────────
let _selectedModuleId   = null;
let _selectedModuleName = "";

// ─── Entry Point ───────────────────────────────────────────────────────────────
export function setupAIGenerate() {
    setupTestTypeCards();
    setupCharCounter();
    setupProjectDropdown();
    setupGenerateForm();
    setupSaveButton();
    setupRegenerateButton();
    setupSelectAll();
}

// Dipanggil oleh dashboard.js saat halaman AI dibuka
export async function loadAIGeneratePage() {
    await loadProjectsForAI();
}

// ─── 1. Kartu Kategori Pengujian ───────────────────────────────────────────────
function setupTestTypeCards() {
    const cards = document.querySelectorAll(".ai-type-card");
    const hiddenInput = document.getElementById("aiTestType");

    cards.forEach(card => {
        const radio = card.querySelector(".ai-type-radio");
        card.addEventListener("click", () => {
            // Reset semua kartu
            cards.forEach(c => {
                c.classList.remove("border-indigo-500", "border-rose-400", "border-amber-400", "border-sky-400", "bg-indigo-50", "bg-rose-50", "bg-amber-50", "bg-sky-50");
                c.classList.add("border-slate-200", "bg-white");
                c.querySelector("span").classList.remove("text-indigo-700", "text-rose-700", "text-amber-700", "text-sky-700");
                c.querySelector("span").classList.add("text-slate-600");
            });

            // Aktifkan kartu yang diklik
            radio.checked = true;
            const val = radio.value;
            const colorMap = {
                Positive:  ["border-indigo-500", "bg-indigo-50", "text-indigo-700"],
                Negative:  ["border-rose-400",   "bg-rose-50",   "text-rose-700"  ],
                Boundary:  ["border-amber-400",  "bg-amber-50",  "text-amber-700" ],
                "UI/UX":   ["border-sky-400",    "bg-sky-50",    "text-sky-700"   ],
            };
            const [borderCls, bgCls, textCls] = colorMap[val] || colorMap.Positive;
            card.classList.remove("border-slate-200", "bg-white");
            card.classList.add(borderCls, bgCls);
            card.querySelector("span").classList.remove("text-slate-600");
            card.querySelector("span").classList.add(textCls);

            if (hiddenInput) hiddenInput.value = val;
        });
    });
}

// ─── 2. Counter karakter deskripsi ────────────────────────────────────────────
function setupCharCounter() {
    const textarea = document.getElementById("aiFeatureDesc");
    const counter  = document.getElementById("aiDescCounter");
    const warning  = document.getElementById("aiDescWarning");
    if (!textarea) return;

    textarea.addEventListener("input", () => {
        const len = textarea.value.length;
        if (counter) counter.textContent = `${len} karakter`;
        if (warning) {
            if (len > 0 && len < 20) {
                warning.classList.remove("hidden");
            } else {
                warning.classList.add("hidden");
            }
        }
    });
}

// ─── 3. Dropdown Project ──────────────────────────────────────────────────────
async function loadProjectsForAI() {
    const projectSel = document.getElementById("aiProjectSelect");
    if (!projectSel) return;

    try {
        const res = await apiRequest("/projects");
        projectSel.innerHTML = `<option value="">— Pilih Project —</option>`;
        (Array.isArray(res) ? res : (res.data || [])).forEach(p => {
            projectSel.innerHTML += `<option value="${p.id}">${p.name}</option>`;
        });
    } catch {
        projectSel.innerHTML = `<option value="">Gagal memuat project</option>`;
    }
}

function setupProjectDropdown() {
    const projectSel = document.getElementById("aiProjectSelect");
    const moduleSel  = document.getElementById("aiModuleSelect");
    const moduleHint = document.getElementById("aiModuleHint");
    const nameInput  = document.getElementById("aiModuleName");

    if (!projectSel || !moduleSel) return;

    projectSel.addEventListener("change", async () => {
        const projectId = projectSel.value;
        if (!projectId) {
            moduleSel.innerHTML = `<option value="">— Pilih project dahulu —</option>`;
            moduleSel.disabled = true;
            moduleSel.classList.add("cursor-not-allowed", "bg-slate-50", "text-slate-400");
            moduleSel.classList.remove("cursor-pointer", "bg-white", "text-slate-700");
            _selectedModuleId = null;
            _selectedModuleName = "";
            return;
        }

        moduleSel.innerHTML = `<option value="">Memuat modul...</option>`;
        moduleSel.disabled = true;

        try {
            const res = await apiRequest(`/projects/${projectId}/modules`);
            moduleSel.innerHTML = `<option value="">— Pilih Modul —</option>`;
            (Array.isArray(res) ? res : (res.data || [])).forEach(m => {
                moduleSel.innerHTML += `<option value="${m.id}">${m.name}</option>`;
            });
            moduleSel.disabled = false;
            moduleSel.classList.remove("cursor-not-allowed", "bg-slate-50", "text-slate-400");
            moduleSel.classList.add("cursor-pointer", "bg-white", "text-slate-700");
            if (moduleHint) moduleHint.classList.add("hidden");
        } catch {
            moduleSel.innerHTML = `<option value="">Gagal memuat modul</option>`;
        }
    });

    // Saat modul dipilih, isi Nama Fitur secara otomatis
    moduleSel.addEventListener("change", () => {
        const opt = moduleSel.options[moduleSel.selectedIndex];
        _selectedModuleId   = moduleSel.value || null;
        _selectedModuleName = opt?.text || "";
        if (nameInput && _selectedModuleName && _selectedModuleName !== "— Pilih Modul —") {
            nameInput.value = _selectedModuleName;
        }
    });
}

// ─── 4. Form Generate ─────────────────────────────────────────────────────────
function setupGenerateForm() {
    const form = document.getElementById("aiGenerateForm");
    if (!form) return;

    form.addEventListener("submit", async (e) => {
        e.preventDefault();

        const btn      = document.getElementById("generateBtn");
        const btnText  = document.getElementById("generateBtnText");
        const btnIcon  = document.getElementById("generateBtnIcon");
        const desc     = document.getElementById("aiFeatureDesc").value.trim();
        const testType = document.getElementById("aiTestType").value;
        const name     = document.getElementById("aiModuleName").value.trim();

        // Validasi tambahan: panjang deskripsi
        if (desc.length < 20) {
            showToast("Deskripsi minimal 20 karakter.", false);
            return;
        }

        // Validasi modul harus dipilih
        if (!_selectedModuleId) {
            showToast("Silakan pilih project dan modul terlebih dahulu.", false);
            return;
        }

        setLoadingState(btn, btnText, btnIcon, true);

        try {
            const data = await apiRequest("/test-cases/generate-ai", "POST", {
                description: desc,
                test_type:   testType,
                module_name: name,
                module_id:   _selectedModuleId,
            });
            displayAICards(data.data, name, testType, data.rag_enabled === true);
        } catch (err) {
            showToast(err.message || "Gagal menghasilkan test case via AI.", false);
        } finally {
            setLoadingState(btn, btnText, btnIcon, false);
        }
    });
}

function setLoadingState(btn, btnText, btnIcon, isLoading) {
    if (!btn) return;
    btn.disabled = isLoading;
    if (isLoading) {
        if (btnText) btnText.textContent = "AI Sedang Menulis Skenario...";
        if (btnIcon) btnIcon.classList.add("animate-spin");
    } else {
        if (btnText) btnText.textContent = "Generate Skenario AI";
        if (btnIcon) btnIcon.classList.remove("animate-spin");
    }
}

// ─── 5. Tampilkan Kartu Hasil AI ──────────────────────────────────────────────
function displayAICards(cases, moduleName, testType, ragEnabled = false) {
    document.getElementById("aiFormContainer").classList.add("hidden");

    const resultContainer = document.getElementById("aiResultContainer");
    resultContainer.classList.replace("hidden", "flex");

    // Update label modul di header hasil
    const moduleLabel = document.getElementById("aiResultModuleLabel");
    if (moduleLabel && moduleName) moduleLabel.textContent = moduleName;

    // Tampilkan / sembunyikan badge RAG
    const ragBadge = document.getElementById("aiRagBadge");
    if (ragBadge) {
        if (ragEnabled) {
            ragBadge.classList.remove("hidden");
        } else {
            ragBadge.classList.add("hidden");
        }
    }

    const list = document.getElementById("aiOutputList");
    list.innerHTML = "";

    cases.forEach((tc, index) => {
        const cat     = tc.category || mapTestTypeToCategoryLabel(testType);
        const prio    = tc.priority || "Medium";
        const catColor = CATEGORY_COLORS[cat] || CATEGORY_COLORS["Positive Test"];
        const prioColor = PRIORITY_COLORS[prio] || PRIORITY_COLORS.Medium;

        const card = document.createElement("div");
        card.className = "ai-card p-5 border border-slate-200/80 rounded-2xl bg-gradient-to-br from-slate-50 to-white flex items-start gap-4 shadow-[0_1px_3px_rgba(0,0,0,0.02)] transition-all duration-200 hover:shadow-md hover:border-slate-300";
        card.dataset.category = cat;
        card.dataset.priority = prio;
        card.dataset.index = index;

        card.innerHTML = `
            <input type="checkbox" checked class="ai-select h-5 w-5 mt-1 rounded text-indigo-600 border-slate-300 focus:ring-indigo-500/30 cursor-pointer flex-shrink-0">
            <div class="flex-1 space-y-3 min-w-0">

                <div class="flex flex-wrap items-start justify-between gap-2">
                    <h4 class="ai-card-name font-bold text-slate-800 text-sm md:text-base focus:outline-none focus:ring-2 focus:ring-indigo-500/30 rounded px-1 -ml-1 flex-1 min-w-0"
                        contenteditable="true" spellcheck="false">${tc.name}</h4>
                    <div class="flex items-center gap-2 flex-shrink-0">
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full border text-[10px] font-bold ${catColor.bg} ${catColor.text} ${catColor.border}">
                            ${cat}
                        </span>
                        <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full bg-indigo-50 text-[10px] font-bold text-indigo-500 border border-indigo-100">
                            AI Case
                        </span>
                    </div>
                </div>

                <div>
                    <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1.5">Langkah Pengujian</p>
                    <div class="ai-card-steps text-sm text-slate-700 bg-white px-4 py-2.5 border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-indigo-500/20 whitespace-pre-line"
                        contenteditable="true" spellcheck="false">${tc.steps}</div>
                </div>

                <div>
                    <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1.5">Hasil yang Diharapkan</p>
                    <div class="ai-card-expected text-sm text-slate-700 bg-white px-4 py-2.5 border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-indigo-500/20 whitespace-pre-line"
                        contenteditable="true" spellcheck="false">${tc.expected_result}</div>
                </div>

                
                <div class="flex items-center justify-between pt-1">
                    <div class="flex items-center gap-2">
                        <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Prioritas:</span>
                        <select class="ai-priority-select text-[11px] font-bold px-2.5 py-1 rounded-full border ${prioColor} bg-transparent focus:outline-none focus:ring-2 focus:ring-indigo-500/20 cursor-pointer transition-colors">
                            <option value="High"   ${prio === 'High'   ? 'selected' : ''}>High</option>
                            <option value="Medium" ${prio === 'Medium' ? 'selected' : ''}>Medium</option>
                            <option value="Low"    ${prio === 'Low'    ? 'selected' : ''}>Low</option>
                        </select>
                    </div>
                    <span class="text-[10px] text-slate-300 italic">Klik teks untuk mengedit</span>
                </div>
            </div>
        `;

        // Update warna badge prioritas saat dropdown berubah
        const prioSel = card.querySelector(".ai-priority-select");
        prioSel.addEventListener("change", () => {
            const newPrio = prioSel.value;
            const newColor = PRIORITY_COLORS[newPrio] || PRIORITY_COLORS.Medium;
            prioSel.className = `ai-priority-select text-[11px] font-bold px-2.5 py-1 rounded-full border ${newColor} bg-transparent focus:outline-none focus:ring-2 focus:ring-indigo-500/20 cursor-pointer transition-colors`;
            card.dataset.priority = newPrio;
        });

        // Update counter saat checkbox diubah
        const checkbox = card.querySelector(".ai-select");
        checkbox.addEventListener("change", updateSelectedCount);

        list.appendChild(card);
    });

    updateSelectedCount();
}

function mapTestTypeToCategoryLabel(testType) {
    const map = {
        "Positive":  "Positive Test",
        "Negative":  "Negative Test",
        "Boundary":  "Boundary Test",
        "UI/UX":     "UI/UX Test",
        "Validation":"Validation Test",
    };
    return map[testType] || "Positive Test";
}

// ─── 6. Counter kartu terpilih ────────────────────────────────────────────────
function updateSelectedCount() {
    const total    = document.querySelectorAll(".ai-select").length;
    const selected = document.querySelectorAll(".ai-select:checked").length;
    const el = document.getElementById("aiSelectedCount");
    if (el) el.textContent = `${selected} dari ${total} dipilih`;
}

// ─── 7. Tombol Pilih Semua / Batalkan Semua ───────────────────────────────────
function setupSelectAll() {
    document.getElementById("aiSelectAllBtn")?.addEventListener("click", () => {
        document.querySelectorAll(".ai-select").forEach(cb => { cb.checked = true; });
        updateSelectedCount();
    });
    document.getElementById("aiDeselectAllBtn")?.addEventListener("click", () => {
        document.querySelectorAll(".ai-select").forEach(cb => { cb.checked = false; });
        updateSelectedCount();
    });
}

// ─── 8. Tombol Simpan ke Database ────────────────────────────────────────────
function setupSaveButton() {
    const saveBtn = document.getElementById("saveAiCasesBtn");
    if (!saveBtn) return;

    saveBtn.addEventListener("click", async () => {
        const checkedCards = document.querySelectorAll(".ai-card input.ai-select:checked");

        if (checkedCards.length === 0) {
            showToast("Pilih minimal satu skenario untuk disimpan.", false);
            return;
        }

        if (!_selectedModuleId) {
            showToast("Module ID tidak ditemukan. Silakan ulangi proses generate.", false);
            return;
        }

        const btnText = document.getElementById("saveBtnText");
        saveBtn.disabled = true;
        if (btnText) btnText.textContent = "Menyimpan ke Database...";

        let successCount = 0;
        let failCount    = 0;

        for (const checkbox of checkedCards) {
            const card          = checkbox.closest(".ai-card");
            const name          = card.querySelector(".ai-card-name").innerText.trim();
            const steps         = card.querySelector(".ai-card-steps").innerText.trim();
            const expected_result = card.querySelector(".ai-card-expected").innerText.trim();
            const priority      = card.querySelector(".ai-priority-select").value || "Medium";
            const category      = card.dataset.category || "Positive Test";

            try {
                await apiRequest("/test-cases", "POST", {
                    module_id: _selectedModuleId,
                    name,
                    steps,
                    expected_result,
                    priority,
                    category,
                    source: "ai",
                });
                successCount++;
            } catch {
                failCount++;
            }
        }

        saveBtn.disabled = false;
        if (btnText) btnText.textContent = "Simpan ke Database";

        if (successCount > 0) {
            showToast(`Berhasil menyimpan ${successCount} test case hasil AI.${failCount > 0 ? ` (${failCount} gagal)` : ""}`);
        }
        if (successCount === 0 && failCount > 0) {
            showToast("Semua test case gagal disimpan. Periksa koneksi atau data.", false);
            return;
        }

        // Reset ke form
        document.getElementById("aiResultContainer").classList.replace("flex", "hidden");
        document.getElementById("aiFormContainer").classList.remove("hidden");
        document.getElementById("aiGenerateForm")?.reset();
        document.getElementById("aiModuleName").value = "";
        document.getElementById("aiTestType").value = "Positive";
        // Reset dropdown modul
        const moduleSel = document.getElementById("aiModuleSelect");
        if (moduleSel) { moduleSel.innerHTML = `<option value="">— Pilih project dahulu —</option>`; moduleSel.disabled = true; }
        _selectedModuleId   = null;
        _selectedModuleName = "";
    });
}

// ─── 9. Tombol Generate Ulang ─────────────────────────────────────────────────
function setupRegenerateButton() {
    document.getElementById("regenerateBtn")?.addEventListener("click", () => {
        document.getElementById("aiResultContainer").classList.replace("flex", "hidden");
        document.getElementById("aiFormContainer").classList.remove("hidden");
    });
}