/**
 * Echo Listener untuk Modul Daftar Ulang
 * Menangani real-time sync ketika checklist diupdate oleh operator lain.
 */

// Fungsi untuk inisialisasi Echo listener pada halaman checklist
export function initDaftarUlangEcho(handlers) {
    if (!window.Echo) {
        console.warn('[Real-Time] Echo not available yet. Retrying in 2s...');
        setTimeout(() => initDaftarUlangEcho(handlers), 2000);
        return;
    }

    // Pastikan kita tidak double-register
    if (window._daftarUlangEchoInitialized) return;
    window._daftarUlangEchoInitialized = true;

    // Listen pada private channel 'daftar-ulang'
    window.Echo.private('daftar-ulang')
        .listen('.checklist.updated', (payload) => {
            console.log('[Real-Time] Checklist update received:', payload);

            if (typeof handlers.onChecklistUpdated === 'function') {
                handlers.onChecklistUpdated(payload);
            }

            if (typeof handlers.onStatsUpdated === 'function' && payload.stats) {
                handlers.onStatsUpdated(payload.stats);
            }
        })
        .error((error) => {
            console.error('[Real-Time] Echo connection error:', error);
        });
}

// Fungsi untuk menghentikan listener (cleanup)
export function destroyDaftarUlangEcho() {
    if (window.Echo) {
        window.Echo.leave('daftar-ulang');
    }
    window._daftarUlangEchoInitialized = false;
}

// Ekspos ke window agar bisa diakses dari inline script di Blade
// (tanpa perlu dynamic import yang bermasalah dengan Vite hashing)
if (typeof window !== 'undefined') {
    window.daftarUlangEcho = {
        init: initDaftarUlangEcho,
        destroy: destroyDaftarUlangEcho,
    };
}
