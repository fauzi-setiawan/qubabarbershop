export const baseUrl = "/api";
export let token = localStorage.getItem("access_token");

export function updateToken(newToken) {
    token = newToken;
}

export async function apiRequest(endpoint, method = "GET", body = null) {
    const headers = {
        "Accept": "application/json",
        "Content-Type": "application/json"
    };

    if (token) {
        headers["Authorization"] = `Bearer ${token}`;
    }

    const config = { method, headers };
    if (body) {
        config.body = JSON.stringify(body);
    }

    try {
        const response = await fetch(`${baseUrl}${endpoint}`, config);

        const text = await response.text();

        if (!response.ok) {
            console.log("ERROR RESPONSE:", text);
        }


        if (response.status === 401) {
            localStorage.clear();
            updateToken(null);
            window.location.reload(); // Reload untuk memicu layar login kembali
            showToast("Sesi berakhir. Silakan login kembali.", false);
            throw new Error("Unauthorized");
        }

        const result = JSON.parse(text);

        if (!response.ok) {
            throw new Error(result.message || "Terjadi kesalahan sistem.");
        }
        return result;
    } catch (error) {
        console.error(`API Error (${endpoint}):`, error);
        throw error;
    }
}

export function showToast(message, isSuccess = true) {
    const toast = document.getElementById("globalToast");
    if (toast) {
        toast.textContent = message;
        toast.className = `fixed bottom-5 right-5 text-white py-3 px-5 rounded-lg shadow-lg transform translate-y-0 transition-all duration-300 z-50 ${isSuccess ? 'bg-gray-800' : 'bg-red-600'}`;

        setTimeout(() => {
            toast.className = "fixed bottom-5 right-5 bg-gray-800 text-white py-3 px-5 rounded-lg shadow-lg transform translate-y-20 opacity-0 transition-all duration-300 z-50";
        }, 3000);
    }
}
