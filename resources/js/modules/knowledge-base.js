import { apiRequest, showToast } from "./api.js";
import { showConfirmModal } from "./modal.js";

let globalDocsData = [];
let docSearchQuery = "";

// ─── Entry Point ───────────────────────────────────────────────────────────────
export function setupKnowledgeBase() {
    setupUpload();
    setupClearCache();
    setupViewerClose();
    setupSearch();
    setupDragAndDrop();
}

export async function loadKnowledgeBase() {
    await loadDocuments();
}

// ─── 1. Muat Daftar Dokumen ────────────────────────────────────────────────────
async function loadDocuments() {
    const listContainer = document.getElementById("kbDocumentList");
    if (!listContainer) return;

    listContainer.innerHTML = `
        <div class="flex items-center justify-center py-12">
            <div class="flex items-center gap-3 text-slate-400">
                <svg class="w-5 h-5 animate-spin" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0A8.003 8.003 0 015.64 15m13.779 0H15"/>
                </svg>
                <span class="text-sm font-medium">Memuat dokumen...</span>
            </div>
        </div>`;

    try {
        const res = await apiRequest("/knowledge-base");
        globalDocsData = res.data || [];

        updateStats(globalDocsData);
        renderDocumentList();
    } catch (err) {
        listContainer.innerHTML = `
            <div class="flex items-center justify-center py-12">
                <p class="text-sm text-rose-500 font-medium">Gagal memuat dokumen: ${err.message}</p>
            </div>`;
    }
}

// ─── 2. Update Statistik ───────────────────────────────────────────────────────
function updateStats(docs) {
    const totalDocs = docs.length;
    const totalChunks = docs.reduce((acc, d) => acc + (d.chunks || 0), 0);
    const mdCount = docs.filter(d => d.extension === "md").length;
    const docxCount = docs.filter(d => d.extension === "docx").length;

    setTextContent("kbTotalDocs", totalDocs);
    setTextContent("kbTotalChunks", totalChunks);
    setTextContent("kbMdCount", mdCount);
    setTextContent("kbDocxCount", docxCount);
    setTextContent("kbDocCountLabel", `${totalDocs} dokumen ditemukan`);
}

function setTextContent(id, value) {
    const el = document.getElementById(id);
    if (el) el.textContent = value;
}

// ─── 3. Setup Kolom Pencarian ──────────────────────────────────────────────────
function setupSearch() {
    const searchInput = document.getElementById("kbSearchInput");
    if (!searchInput) return;

    searchInput.addEventListener("input", (e) => {
        docSearchQuery = e.target.value.toLowerCase().trim();
        renderDocumentList();
    });
}

// ─── 4. Render Daftar Dokumen ──────────────────────────────────────────────────
function renderDocumentList() {
    const container = document.getElementById("kbDocumentList");
    if (!container) return;

    const filteredDocs = docSearchQuery
        ? globalDocsData.filter(doc => doc.name.toLowerCase().includes(docSearchQuery))
        : globalDocsData;

    if (filteredDocs.length === 0) {
        if (docSearchQuery) {
            container.innerHTML = `
                <div class="flex flex-col items-center justify-center py-16 gap-3">
                    <svg class="w-12 h-12 text-slate-200" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                    </svg>
                    <p class="text-sm text-slate-400 font-medium">Tidak ada dokumen yang cocok dengan "${docSearchQuery}"</p>
                    <p class="text-xs text-slate-300">Coba ubah filter kata kunci pencarian Anda.</p>
                </div>`;
        } else {
            container.innerHTML = `
                <div class="flex flex-col items-center justify-center py-16 gap-3 border-2 border-dashed border-slate-150 rounded-2xl bg-slate-50/30 m-6 hover:bg-slate-50/70 transition-all duration-200 cursor-pointer" onclick="document.getElementById('kbUploadInput').click()">
                    <div class="w-12 h-12 rounded-full bg-violet-50 flex items-center justify-center text-violet-600 mb-1">
                        <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12" />
                        </svg>
                    </div>
                    <p class="text-sm text-slate-600 font-semibold">Tarik & lepas berkas Anda di sini, atau klik untuk memilih</p>
                    <p class="text-xs text-slate-400">Mendukung berkas Markdown (.md) dan Word (.docx) hingga 10MB</p>
                </div>`;
        }
        return;
    }

    container.innerHTML = "";

    filteredDocs.forEach(doc => {
        const sizeStr = formatFileSize(doc.size);
        const isMarkdown = doc.extension === "md";
        const typeColor = isMarkdown
            ? "bg-emerald-50 text-emerald-700 border-emerald-100"
            : "bg-sky-50 text-sky-700 border-sky-100";
        const typeIcon = isMarkdown
            ? `<svg class="w-4 h-4 text-emerald-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z"/></svg>`
            : `<svg class="w-4 h-4 text-sky-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"/></svg>`;

        const row = document.createElement("div");
        row.className = "flex flex-col sm:flex-row sm:items-center justify-between px-6 py-4.5 hover:bg-slate-50/50 transition-colors duration-150 gap-4";
        row.innerHTML = `
            <div class="flex items-start gap-4 min-w-0 flex-1">
                <div class="w-10 h-10 rounded-xl ${isMarkdown ? 'bg-emerald-50' : 'bg-sky-50'} flex items-center justify-center flex-shrink-0 border border-slate-100 shadow-sm mt-0.5">
                    ${typeIcon}
                </div>
                <div class="min-w-0 flex-1">
                    <div class="flex flex-wrap items-center gap-2">
                        <p class="text-sm font-semibold text-slate-800 truncate" title="${doc.name}">${doc.name}</p>
                        <span class="inline-flex items-center px-2 py-0.5 rounded-full border text-[9px] font-bold ${typeColor} flex-shrink-0">${doc.type}</span>
                    </div>
                    <div class="flex items-center gap-3 mt-1.5">
                        <span class="text-[10px] text-slate-400">${sizeStr}</span>
                        <span class="text-slate-200">•</span>
                        <span class="text-[10px] text-slate-400">${doc.modified}</span>
                        <span class="text-slate-200">•</span>
                        <span class="inline-flex items-center text-[10px] text-fuchsia-600 font-bold bg-fuchsia-50 border border-fuchsia-100 rounded px-1.5 py-0.5">${doc.chunks} chunks</span>
                    </div>
                </div>
            </div>
            <div class="flex items-center gap-2.5 flex-shrink-0">
                <button class="kb-view-btn inline-flex items-center gap-1.5 text-xs font-semibold text-violet-600 hover:text-violet-800 bg-violet-50 hover:bg-violet-100 border border-violet-100 hover:border-violet-200 px-3.5 py-2 rounded-xl transition-all duration-200 active:scale-[0.97]" data-file="${doc.name}" data-type="${doc.extension}">
                    <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                    Lihat
                </button>
                <button class="kb-delete-btn inline-flex items-center gap-1.5 text-xs font-semibold text-rose-600 hover:text-rose-800 bg-rose-50 hover:bg-rose-100 border border-rose-100 hover:border-rose-200 px-3.5 py-2 rounded-xl transition-all duration-200 active:scale-[0.97]" data-file="${doc.name}">
                    <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                    Hapus
                </button>
            </div>`;

        // Event: Lihat Dokumen
        row.querySelector(".kb-view-btn").addEventListener("click", (e) => {
            const filename = e.currentTarget.dataset.file;
            const type = e.currentTarget.dataset.type;
            viewDocument(filename, type);
        });

        // Event: Hapus Dokumen
        row.querySelector(".kb-delete-btn").addEventListener("click", (e) => {
            const filename = e.currentTarget.dataset.file;
            deleteDocument(filename);
        });

        container.appendChild(row);
    });
}

// ─── 5. Lihat Dokumen ──────────────────────────────────────────────────────────
async function viewDocument(filename, type) {
    const viewer = document.getElementById("kbViewerContainer");
    const title = document.getElementById("kbViewerTitle");
    const badge = document.getElementById("kbViewerBadge");
    const contentEl = document.getElementById("kbViewerContent");

    if (!viewer || !contentEl) return;

    // Set header
    if (title) title.textContent = filename;
    if (badge) {
        const isMarkdown = type === "md";
        badge.textContent = isMarkdown ? "Markdown" : "Word Document";
        badge.className = `inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[10px] font-bold border ${isMarkdown ? "bg-emerald-50 text-emerald-700 border-emerald-200" : "bg-sky-50 text-sky-700 border-sky-200"
            }`;
    }

    contentEl.innerHTML = `
        <div class="flex items-center justify-center py-12">
            <div class="flex items-center gap-3 text-slate-400">
                <svg class="w-5 h-5 animate-spin" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0A8.003 8.003 0 015.64 15m13.779 0H15"/>
                </svg>
                <span class="text-sm font-medium">Memuat konten dokumen...</span>
            </div>
        </div>`;

    viewer.classList.remove("hidden");

    // Scroll ke viewer
    viewer.scrollIntoView({ behavior: "smooth", block: "start" });

    try {
        const res = await apiRequest(`/knowledge-base/show?file=${encodeURIComponent(filename)}`);
        const content = res.data?.content || "";

        if (type === "md") {
            contentEl.innerHTML = renderSimpleMarkdown(content);
        } else {
            contentEl.innerHTML = `<pre class="text-sm text-slate-700 whitespace-pre-wrap font-mono leading-relaxed bg-slate-50/50 p-4 rounded-xl border border-slate-150">${escapeHtml(content)}</pre>`;
        }
    } catch (err) {
        contentEl.innerHTML = `<p class="text-sm text-rose-500 font-medium py-8 text-center">Gagal memuat konten: ${err.message}</p>`;
    }
}

// ─── 6. Render Markdown Sederhana ──────────────────────────────────────────────
function renderSimpleMarkdown(md) {
    let html = escapeHtml(md);

    // Headers
    html = html.replace(/^######\s+(.+)$/gm, '<h6 class="text-xs font-bold text-slate-600 mt-4 mb-1">$1</h6>');
    html = html.replace(/^#####\s+(.+)$/gm, '<h5 class="text-xs font-bold text-slate-700 mt-4 mb-1">$1</h5>');
    html = html.replace(/^####\s+(.+)$/gm, '<h4 class="text-sm font-bold text-slate-700 mt-5 mb-2">$1</h4>');
    html = html.replace(/^###\s+(.+)$/gm, '<h3 class="text-base font-bold text-slate-800 mt-6 mb-2">$1</h3>');
    html = html.replace(/^##\s+(.+)$/gm, '<h2 class="text-lg font-bold text-slate-800 mt-8 mb-3 pb-2 border-b border-slate-200">$1</h2>');
    html = html.replace(/^#\s+(.+)$/gm, '<h1 class="text-xl font-bold text-slate-900 mt-8 mb-4 pb-2 border-b-2 border-violet-200">$1</h1>');

    // Bold and Italic
    html = html.replace(/\*\*\*(.+?)\*\*\*/g, '<strong><em>$1</em></strong>');
    html = html.replace(/\*\*(.+?)\*\*/g, '<strong class="text-slate-800">$1</strong>');
    html = html.replace(/\*(.+?)\*/g, '<em>$1</em>');

    // Inline code
    html = html.replace(/`([^`]+)`/g, '<code class="px-1.5 py-0.5 bg-violet-50 text-violet-700 text-xs rounded font-mono border border-violet-100">$1</code>');

    // Code blocks
    html = html.replace(/```(\w*)\n([\s\S]*?)```/g, (_, lang, code) => {
        return `<div class="my-3 rounded-xl overflow-hidden border border-slate-200"><div class="px-3 py-1.5 bg-slate-100 text-[10px] text-slate-500 font-bold uppercase">${lang || 'code'}</div><pre class="p-4 bg-slate-50 text-xs text-slate-700 overflow-x-auto font-mono leading-relaxed">${code.trim()}</pre></div>`;
    });

    // Horizontal rule
    html = html.replace(/^---$/gm, '<hr class="my-6 border-slate-200">');

    // Tables
    html = html.replace(/^(\|.+\|)\n(\|[-|: ]+\|)\n((?:\|.+\|\n?)+)/gm, (_, header, separator, body) => {
        const headers = header.split('|').filter(c => c.trim()).map(c =>
            `<th class="px-3 py-2 text-left text-[10px] font-bold text-slate-500 uppercase tracking-wider bg-slate-50 border-b border-slate-200">${c.trim()}</th>`
        ).join('');
        const rows = body.trim().split('\n').map(row => {
            const cells = row.split('|').filter(c => c.trim()).map(c =>
                `<td class="px-3 py-2 text-xs text-slate-700 border-b border-slate-100">${c.trim()}</td>`
            ).join('');
            return `<tr class="hover:bg-slate-50/50">${cells}</tr>`;
        }).join('');
        return `<div class="my-4 overflow-x-auto rounded-xl border border-slate-200"><table class="w-full text-left"><thead><tr>${headers}</tr></thead><tbody>${rows}</tbody></table></div>`;
    });

    // Blockquote
    html = html.replace(/^&gt;\s+(.+)$/gm, '<blockquote class="pl-4 border-l-4 border-violet-300 text-sm text-slate-600 italic my-2">$1</blockquote>');

    // Lists
    html = html.replace(/^[-*]\s+(.+)$/gm, '<li class="text-sm text-slate-700 ml-4 list-disc">$1</li>');
    html = html.replace(/^\d+\.\s+(.+)$/gm, '<li class="text-sm text-slate-700 ml-4 list-decimal">$1</li>');

    // Paragraphs
    html = html.replace(/\n\n/g, '</p><p class="text-sm text-slate-700 leading-relaxed my-2">');
    html = `<p class="text-sm text-slate-700 leading-relaxed my-2">${html}</p>`;
    html = html.replace(/<p class="[^"]*"><\/p>/g, '');

    return `<div class="prose-sm">${html}</div>`;
}

function escapeHtml(text) {
    const div = document.createElement("div");
    div.textContent = text;
    return div.innerHTML;
}

// ─── 7. Hapus Dokumen ──────────────────────────────────────────────────────────
async function deleteDocument(filename) {
    const confirmDelete = await showConfirmModal({
        title: "Hapus Dokumen?",
        message: `Apakah Anda yakin ingin menghapus dokumen "${filename}"? Tindakan ini tidak dapat dibatalkan.`,
        confirmText: "Ya, Hapus",
        isDanger: true
    });

    if (!confirmDelete) return;

    try {
        await apiRequest(`/knowledge-base?file=${encodeURIComponent(filename)}`, "DELETE");
        showToast(`Dokumen "${filename}" berhasil dihapus.`);
        await loadDocuments();
    } catch (err) {
        showToast(`Gagal menghapus: ${err.message}`, false);
    }
}

// ─── 8. Upload Dokumen ─────────────────────────────────────────────────────────
function setupUpload() {
    const input = document.getElementById("kbUploadInput");
    if (!input) return;

    input.addEventListener("change", async (e) => {
        const file = e.target.files[0];
        if (!file) return;

        const ext = file.name.split('.').pop().toLowerCase();
        if (ext !== 'md' && ext !== 'docx') {
            showToast("Format berkas tidak didukung. Harap upload berkas .md atau .docx", false);
            input.value = "";
            return;
        }

        await uploadFile(file);
        input.value = "";
    });
}

async function uploadFile(file) {
    const formData = new FormData();
    formData.append("document", file);

    const token = localStorage.getItem("access_token");

    try {
        showToast(`Mengunggah "${file.name}"...`);

        const response = await fetch("/api/knowledge-base/upload", {
            method: "POST",
            headers: {
                Accept: "application/json",
                Authorization: `Bearer ${token}`,
            },
            body: formData,
        });

        const result = await response.json();

        if (response.ok) {
            showToast(result.message || `Dokumen "${file.name}" berhasil diunggah.`);
            await loadDocuments();
        } else {
            showToast(result.message || "Gagal mengunggah dokumen.", false);
        }
    } catch (err) {
        showToast(`Error upload: ${err.message}`, false);
    }
}

// ─── 9. Drag & Drop File Upload ───────────────────────────────────────────────
function setupDragAndDrop() {
    const listContainer = document.getElementById("kbDocumentList");
    if (!listContainer) return;

    ['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
        listContainer.addEventListener(eventName, preventDefaults, false);
    });

    function preventDefaults(e) {
        e.preventDefault();
        e.stopPropagation();
    }

    ['dragenter', 'dragover'].forEach(eventName => {
        listContainer.addEventListener(eventName, () => {
            listContainer.classList.add('bg-violet-50/40', 'border-2', 'border-dashed', 'border-violet-300', 'rounded-2xl');
        }, false);
    });

    ['dragleave', 'drop'].forEach(eventName => {
        listContainer.addEventListener(eventName, () => {
            listContainer.classList.remove('bg-violet-50/40', 'border-2', 'border-dashed', 'border-violet-300', 'rounded-2xl');
        }, false);
    });

    listContainer.addEventListener('drop', async (e) => {
        const dt = e.dataTransfer;
        const files = dt.files;
        if (files && files.length > 0) {
            const file = files[0];
            const ext = file.name.split('.').pop().toLowerCase();
            if (ext !== 'md' && ext !== 'docx') {
                showToast("Format berkas tidak didukung. Harap upload berkas .md atau .docx", false);
                return;
            }
            await uploadFile(file);
        }
    }, false);
}

// ─── 10. Clear Cache RAG ───────────────────────────────────────────────────────
function setupClearCache() {
    const btn = document.getElementById("kbClearCacheBtn");
    if (!btn) return;

    btn.addEventListener("click", async () => {
        btn.disabled = true;
        btn.querySelector("svg")?.classList.add("animate-spin");

        try {
            const res = await apiRequest("/knowledge-base/clear-cache", "POST");
            showToast(res.message || "Cache RAG berhasil dibersihkan.");
            await loadDocuments();
        } catch (err) {
            showToast(`Gagal membersihkan cache: ${err.message}`, false);
        } finally {
            btn.disabled = false;
            btn.querySelector("svg")?.classList.remove("animate-spin");
        }
    });
}

// ─── 11. Close Viewer ──────────────────────────────────────────────────────────
function setupViewerClose() {
    const closeBtn = document.getElementById("kbViewerClose");
    if (!closeBtn) return;

    closeBtn.addEventListener("click", () => {
        const viewer = document.getElementById("kbViewerContainer");
        if (viewer) viewer.classList.add("hidden");
    });
}

// ─── Utilitas ──────────────────────────────────────────────────────────────────
function formatFileSize(bytes) {
    if (bytes === 0) return "0 B";
    const units = ["B", "KB", "MB", "GB"];
    const i = Math.floor(Math.log(bytes) / Math.log(1024));
    return (bytes / Math.pow(1024, i)).toFixed(1) + " " + units[i];
}
