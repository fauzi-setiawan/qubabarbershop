import { apiRequest, updateToken, showToast } from "./api.js";

export function setupAuth() {
    console.log("setupAuth berjalan");
    const loginForm = document.getElementById("loginForm");
    if (loginForm) {
        loginForm.addEventListener("submit", async (e) => {
            e.preventDefault();
            const email = document.getElementById("email").value;
            const password = document.getElementById("password").value;
            const btn = document.getElementById("loginButton");

            btn.disabled = true;
            btn.innerHTML = "Memproses...";

            try {
                const res = await apiRequest("/login", "POST", { email, password });
                localStorage.setItem("access_token", res.access_token);
                localStorage.setItem("user_name", res.data.name);
                localStorage.setItem("user_avatar", res.data.avatar || 'US');
                
                updateToken(res.access_token);
                showToast("Login berhasil!");
                
                // Pemicu event global agar dashboard tahu user telah login
                window.dispatchEvent(new CustomEvent("userLoggedIn"));
                
            } catch (err) {
                showToast(err.message || "Email atau password salah.", false);
            } finally {
                btn.disabled = false;
                btn.innerHTML = "Login";
            }
        });
    }

    const logoutBtn = document.getElementById("logoutButton");
    if (logoutBtn) {
        logoutBtn.addEventListener("click", async () => {
            try {
                await apiRequest("/logout", "POST");
            } catch (err) {
                console.warn("Sesi lokal akan dihapus.");
            } finally {
                localStorage.clear();
                updateToken(null);
                showToast("Anda telah keluar.");
                window.location.reload();
            }
        });
    }

    // Toggle navigasi antara login & register
    const toRegisterBtn = document.getElementById("toRegisterBtn");
    const toLoginBtn = document.getElementById("toLoginBtn");
    if (toRegisterBtn && toLoginBtn) {
        toRegisterBtn.addEventListener("click", (e) => {
            e.preventDefault();
            document.getElementById("loginScreen").classList.add("hidden");
            document.getElementById("registerScreen").classList.remove("hidden");
        });
        toLoginBtn.addEventListener("click", (e) => {
            e.preventDefault();
            document.getElementById("registerScreen").classList.add("hidden");
            document.getElementById("loginScreen").classList.remove("hidden");
        });
    }

    const registerForm = document.getElementById("registerForm");
    if (registerForm) {
        registerForm.addEventListener("submit", async (e) => {
            e.preventDefault();
            const name = document.getElementById("regName").value;
            const email = document.getElementById("regEmail").value;
            const role = document.getElementById("regRole").value;
            const password = document.getElementById("regPassword").value;
            const btn = document.getElementById("registerButton");

            btn.disabled = true;
            btn.innerHTML = "Mendaftarkan...";

            try {
                await apiRequest("/register", "POST", { name, email, role, password });
                showToast("Registrasi berhasil! Silakan login.");
                document.getElementById("registerScreen").classList.add("hidden");
                document.getElementById("loginScreen").classList.remove("hidden");
                registerForm.reset();
            } catch (err) {
                showToast(err.message || "Gagal melakukan registrasi.", false);
            } finally {
                btn.disabled = false;
                btn.innerHTML = "Daftar Sekarang";
            }
        });
    }
}