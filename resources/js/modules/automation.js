import { showToast } from "./api.js";

export function loadAutomationConfig() {
    console.log("Halaman Automation aktif.");
}

export function setupAutomation() {
    const autoForm = document.getElementById("automationForm");
    if (autoForm) {
        autoForm.addEventListener("submit", (e) => {
            e.preventDefault();
            const btn = document.getElementById("runAutoBtn");
            btn.disabled = true;
            btn.innerHTML = "Memproses Trigger...";

            showToast("Memicu GitHub Actions Workflow...");
            setTimeout(() => {
                showToast("Workflow berhasil dipicu secara otomatis!");
                btn.disabled = false;
                btn.innerHTML = "Picu Automation";
                autoForm.reset();
            }, 2000);
        });
    }
}