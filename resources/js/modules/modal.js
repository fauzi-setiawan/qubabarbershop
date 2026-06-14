/**
 * Dynamic Modal Dialogs for professional UI/UX
 * Replaces native browser prompt() and confirm() with beautiful Tailwind modals
 */

/**
 * Show a professional confirmation dialog
 * 
 * @param {Object} options
 * @param {string} options.title - Modal title
 * @param {string} options.message - Confirmation message
 * @param {string} [options.confirmText] - Label for confirm button
 * @param {string} [options.cancelText] - Label for cancel button
 * @param {boolean} [options.isDanger] - Theme as red destructive action
 * @returns {Promise<boolean>} Resolves to true if confirmed, false if cancelled
 */
export function showConfirmModal({ title, message, confirmText = "Konfirmasi", cancelText = "Batal", isDanger = false }) {
    return new Promise((resolve) => {
        // Wrapper container
        const wrapper = document.createElement("div");
        wrapper.className = "fixed inset-0 z-50 flex items-center justify-center p-4";

        // Backdrop blur overlay
        const backdrop = document.createElement("div");
        backdrop.className = "fixed inset-0 bg-slate-900/60 backdrop-blur-sm transition-opacity duration-300 opacity-0";
        wrapper.appendChild(backdrop);

        // Modal card container
        const card = document.createElement("div");
        card.className = "relative w-full max-w-md bg-white rounded-2xl shadow-xl border border-slate-100 overflow-hidden transform scale-95 opacity-0 transition-all duration-300 z-10 p-6";

        // Top border accent
        const accent = document.createElement("div");
        accent.className = `absolute top-0 left-0 w-full h-1.5 ${isDanger ? 'bg-gradient-to-r from-rose-500 to-red-600' : 'bg-gradient-to-r from-indigo-500 to-purple-600'}`;
        card.appendChild(accent);

        // Render card content
        card.innerHTML += `
            <div class="flex items-start gap-4 mt-2">
                <div class="w-10 h-10 rounded-xl flex items-center justify-center flex-shrink-0 ${isDanger ? 'bg-rose-50 text-rose-600' : 'bg-indigo-50 text-indigo-600'} shadow-sm">
                    ${isDanger
                ? '<svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg>'
                : '<svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>'
            }
                </div>
                <div class="flex-1 min-w-0">
                    <h3 class="text-base font-bold text-slate-800 tracking-tight">${title}</h3>
                    <p class="text-sm text-slate-500 mt-2 leading-relaxed">${message}</p>
                </div>
            </div>
            <div class="flex justify-end gap-3 mt-6">
                <button type="button" class="modal-cancel-btn px-4 py-2 text-sm font-semibold text-slate-500 hover:text-slate-700 bg-slate-100 hover:bg-slate-200/80 rounded-xl transition-all duration-200 active:scale-[0.97]">${cancelText}</button>
                <button type="button" class="modal-confirm-btn px-4 py-2 text-sm font-semibold text-white rounded-xl shadow-sm transition-all duration-200 active:scale-[0.97] ${isDanger ? 'bg-gradient-to-r from-rose-600 to-red-600 hover:from-rose-700 hover:to-red-700 shadow-rose-100' : 'bg-gradient-to-r from-indigo-600 to-purple-600 hover:from-indigo-700 hover:to-purple-700 shadow-indigo-100'}">${confirmText}</button>
            </div>
        `;

        wrapper.appendChild(card);
        document.body.appendChild(wrapper);

        // Apply transition effects
        setTimeout(() => {
            backdrop.classList.replace("opacity-0", "opacity-100");
            card.classList.remove("scale-95", "opacity-0");
            card.classList.add("scale-100", "opacity-100");
        }, 10);

        // Close animation handler
        const close = (result) => {
            backdrop.classList.replace("opacity-100", "opacity-0");
            card.classList.replace("scale-100", "scale-95");
            card.classList.replace("opacity-100", "opacity-0");
            setTimeout(() => {
                wrapper.remove();
            }, 300);
            resolve(result);
        };

        // Event hooks
        wrapper.querySelector(".modal-cancel-btn").addEventListener("click", () => close(false));
        wrapper.querySelector(".modal-confirm-btn").addEventListener("click", () => close(true));
        backdrop.addEventListener("click", () => close(false));
    });
}

/**
 * Show a form input modal with custom fields
 * 
 * @param {Object} options
 * @param {string} options.title - Modal header title
 * @param {Array<Object>} options.fields - Configuration for each form field
 * @param {string} options.fields[].name - Property key in return data object
 * @param {string} options.fields[].label - Input label text
 * @param {string} [options.fields[].type] - Field input type: text, textarea, select, password, email
 * @param {string} [options.fields[].placeholder] - Placeholder text
 * @param {string} [options.fields[].value] - Prefilled value
 * @param {boolean} [options.fields[].required] - Is field required
 * @param {Array<string|Object>} [options.fields[].options] - Options for select element
 * @param {string} [options.submitText] - Label for submit button
 * @param {string} [options.cancelText] - Label for cancel button
 * @returns {Promise<Object|null>} Resolves to key-value fields data if submitted, or null if cancelled
 */
export function showFormModal({ title, fields, submitText = "Simpan", cancelText = "Batal" }) {
    return new Promise((resolve) => {
        // Wrapper container (allows scrollable layout)
        const wrapper = document.createElement("div");
        wrapper.className = "fixed inset-0 z-50 flex items-center justify-center p-4 overflow-y-auto";

        // Backdrop overlay
        const backdrop = document.createElement("div");
        backdrop.className = "fixed inset-0 bg-slate-900/60 backdrop-blur-sm transition-opacity duration-300 opacity-0";
        wrapper.appendChild(backdrop);

        // Modal card
        const card = document.createElement("div");
        card.className = "relative w-full max-w-lg bg-white rounded-2xl shadow-2xl border border-slate-100 overflow-hidden transform scale-95 opacity-0 transition-all duration-300 z-10 p-6 my-8";

        // Accent top border
        const accent = document.createElement("div");
        accent.className = "absolute top-0 left-0 w-full h-1.5 bg-gradient-to-r from-indigo-500 via-purple-500 to-pink-500";
        card.appendChild(accent);

        // Title header
        const header = document.createElement("div");
        header.className = "mb-5 mt-2";
        header.innerHTML = `<h3 class="text-lg font-bold text-slate-800 tracking-tight">${title}</h3>`;
        card.appendChild(header);

        // Form tag
        const form = document.createElement("form");
        form.className = "space-y-4";

        // Fields elements container
        const fieldsContainer = document.createElement("div");
        fieldsContainer.className = "space-y-4 max-h-[60vh] overflow-y-auto pr-1";

        // Generate input items based on configuration array
        fields.forEach(field => {
            const group = document.createElement("div");
            group.className = "space-y-1.5";

            const label = document.createElement("label");
            label.className = "block text-[11px] font-semibold text-slate-500 uppercase tracking-wider";
            label.textContent = field.label;
            group.appendChild(label);

            let input;
            const initialVal = field.value !== undefined ? field.value : "";

            if (field.type === 'textarea') {
                input = document.createElement("textarea");
                input.rows = field.rows || 3;
                input.className = "w-full px-3.5 py-2 text-sm text-slate-700 placeholder-slate-350 bg-white border border-slate-200 rounded-xl shadow-sm focus:outline-none focus:ring-2 focus:ring-indigo-500/30 focus:border-indigo-400 hover:border-slate-300 transition-all duration-200 resize-none";
                input.value = initialVal;
            } else if (field.type === 'select') {
                input = document.createElement("select");
                input.className = "w-full px-3.5 py-2 text-sm text-slate-700 bg-white border border-slate-200 rounded-xl shadow-sm focus:outline-none focus:ring-2 focus:ring-indigo-500/30 focus:border-indigo-400 hover:border-slate-300 transition-all duration-200 cursor-pointer appearance-none";

                const opts = field.options || [];
                opts.forEach(opt => {
                    const optEl = document.createElement("option");
                    if (typeof opt === 'object' && opt !== null) {
                        optEl.value = opt.value;
                        optEl.textContent = opt.text;
                    } else {
                        optEl.value = opt;
                        optEl.textContent = opt;
                    }
                    if (optEl.value == initialVal) {
                        optEl.selected = true;
                    }
                    input.appendChild(optEl);
                });
            } else {
                input = document.createElement("input");
                input.type = field.type || "text";
                input.className = "w-full px-3.5 py-2 text-sm text-slate-700 placeholder-slate-350 bg-white border border-slate-200 rounded-xl shadow-sm focus:outline-none focus:ring-2 focus:ring-indigo-500/30 focus:border-indigo-400 hover:border-slate-300 transition-all duration-200";
                input.value = initialVal;
            }

            input.name = field.name;
            input.placeholder = field.placeholder || "";
            if (field.required) {
                input.required = true;
            }

            group.appendChild(input);
            fieldsContainer.appendChild(group);
        });

        form.appendChild(fieldsContainer);

        // Buttons footer
        const footer = document.createElement("div");
        footer.className = "flex justify-end gap-3 pt-3 border-t border-slate-100 mt-6";
        footer.innerHTML = `
            <button type="button" class="modal-cancel-btn px-4.5 py-2.5 text-sm font-semibold text-slate-500 hover:text-slate-700 bg-slate-100 hover:bg-slate-200/80 rounded-xl transition-all duration-200 active:scale-[0.97]">${cancelText}</button>
            <button type="submit" class="modal-submit-btn px-5 py-2.5 text-sm font-semibold text-white bg-gradient-to-r from-indigo-600 to-purple-600 hover:from-indigo-700 hover:to-purple-700 rounded-xl shadow-sm shadow-indigo-150 transition-all duration-200 active:scale-[0.97]">${submitText}</button>
        `;
        form.appendChild(footer);

        card.appendChild(form);
        wrapper.appendChild(card);
        document.body.appendChild(wrapper);

        // Apply visual transitions
        setTimeout(() => {
            backdrop.classList.replace("opacity-0", "opacity-100");
            card.classList.remove("scale-95", "opacity-0");
            card.classList.add("scale-100", "opacity-100");
        }, 10);

        // Close logic
        const close = (result) => {
            backdrop.classList.replace("opacity-100", "opacity-0");
            card.classList.replace("scale-100", "scale-95");
            card.classList.replace("opacity-100", "opacity-0");
            setTimeout(() => {
                wrapper.remove();
            }, 300);
            resolve(result);
        };

        // Form submit hook
        form.addEventListener("submit", (e) => {
            e.preventDefault();
            const data = {};
            const elements = form.elements;
            for (let i = 0; i < elements.length; i++) {
                const el = elements[i];
                if (el.name) {
                    data[el.name] = el.value.trim();
                }
            }
            close(data);
        });

        // Event listeners
        wrapper.querySelector(".modal-cancel-btn").addEventListener("click", () => close(null));
        backdrop.addEventListener("click", () => close(null));
    });
}

/**
 * Show a premium detail modal for viewing items (e.g. Test Cases)
 * 
 * @param {Object} options
 * @param {string} options.title - Main header title
 * @param {string} [options.subtitle] - Optional subtitle
 * @param {Array<Object>} [options.badges] - Array of badge configurations {text, classes}
 * @param {Array<Object>} options.sections - Array of section configurations {label, value, isPre, icon}
 * @returns {Promise<void>} Resolves when closed
 */
export function showDetailModal({ title, subtitle, badges = [], sections = [] }) {
    return new Promise((resolve) => {
        // Wrapper container
        const wrapper = document.createElement("div");
        wrapper.className = "fixed inset-0 z-50 flex items-center justify-center p-4 overflow-y-auto";

        // Backdrop blur overlay
        const backdrop = document.createElement("div");
        backdrop.className = "fixed inset-0 bg-slate-900/60 backdrop-blur-sm transition-opacity duration-300 opacity-0";
        wrapper.appendChild(backdrop);

        // Modal card container
        const card = document.createElement("div");
        card.className = "relative w-full max-w-xl bg-white rounded-2xl shadow-2xl border border-slate-100 overflow-hidden transform scale-95 opacity-0 transition-all duration-300 z-10 p-6 my-8";

        // Accent top border
        const accent = document.createElement("div");
        accent.className = "absolute top-0 left-0 w-full h-1.5 bg-gradient-to-r from-violet-500 via-indigo-500 to-purple-500";
        card.appendChild(accent);

        // Header
        const header = document.createElement("div");
        header.className = "mb-5 mt-2 flex flex-col gap-1.5 border-b border-slate-100 pb-4";

        let badgesHtml = "";
        badges.forEach(b => {
            badgesHtml += `<span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-xs font-bold ${b.classes}">${b.text}</span>`;
        });

        header.innerHTML = `
            <div class="flex flex-wrap gap-2 items-center mb-1">
                ${badgesHtml}
            </div>
            <h3 class="text-base font-bold text-slate-800 tracking-tight leading-snug">${title}</h3>
            ${subtitle ? `<p class="text-xs text-slate-400 font-medium">${subtitle}</p>` : ""}
        `;
        card.appendChild(header);

        // Content Container
        const contentContainer = document.createElement("div");
        contentContainer.className = "space-y-5 max-h-[55vh] overflow-y-auto pr-1";

        sections.forEach(s => {
            const secEl = document.createElement("div");
            secEl.className = "space-y-1.5";

            let valueHtml = "";
            if (s.isPre) {
                const lines = (s.value || "").split('\n').map(l => l.trim()).filter(l => l.length > 0);
                if (lines.length > 0) {
                    valueHtml = `<ol class="space-y-2 list-decimal list-inside text-sm text-slate-650 bg-slate-50 border border-slate-100 rounded-xl p-3.5 leading-relaxed">`;
                    lines.forEach(line => {
                        const cleanLine = line.replace(/^\d+[\.\-\)]\s*/, '');
                        valueHtml += `<li class="pl-1">${cleanLine}</li>`;
                    });
                    valueHtml += `</ol>`;
                } else {
                    valueHtml = `<div class="text-xs italic text-slate-450 bg-slate-50 border border-slate-100 rounded-xl p-3.5">Tidak ada data</div>`;
                }
            } else {
                valueHtml = `<div class="text-sm text-slate-655 bg-slate-50 border border-slate-100 rounded-xl p-3.5 leading-relaxed whitespace-pre-line">${s.value || "-"}</div>`;
            }

            secEl.innerHTML = `
                <h4 class="text-[10px] font-bold text-slate-400 uppercase tracking-widest flex items-center gap-1.5">
                    ${s.icon || ''}
                    ${s.label}
                </h4>
                ${valueHtml}
            `;
            contentContainer.appendChild(secEl);
        });

        card.appendChild(contentContainer);

        // Footer
        const footer = document.createElement("div");
        footer.className = "flex justify-end pt-3 border-t border-slate-100 mt-6";
        footer.innerHTML = `
            <button type="button" class="modal-close-btn px-5 py-2.5 text-sm font-semibold text-white bg-gradient-to-r from-indigo-600 to-purple-600 hover:from-indigo-700 hover:to-purple-700 rounded-xl shadow-sm shadow-indigo-150 transition-all duration-200 active:scale-[0.97]">Tutup</button>
        `;
        card.appendChild(footer);

        wrapper.appendChild(card);
        document.body.appendChild(wrapper);

        // Apply transition effects
        setTimeout(() => {
            backdrop.classList.replace("opacity-0", "opacity-100");
            card.classList.remove("scale-95", "opacity-0");
            card.classList.add("scale-100", "opacity-100");
        }, 10);

        const close = () => {
            backdrop.classList.replace("opacity-100", "opacity-0");
            card.classList.replace("scale-100", "scale-95");
            card.classList.replace("opacity-100", "opacity-0");
            setTimeout(() => {
                wrapper.remove();
            }, 300);
            resolve();
        };

        wrapper.querySelector(".modal-close-btn").addEventListener("click", close);
        backdrop.addEventListener("click", close);
    });
}
