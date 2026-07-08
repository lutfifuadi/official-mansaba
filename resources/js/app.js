import axios from 'axios';
import Echo from 'laravel-echo';
import Pusher from 'pusher-js';

window.axios = axios;
window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';

window.Pusher = Pusher;

/*
 * Auto-Switch Reverb Config
 * Local (dev)  -> localhost:8080 (ws://)
 * Production   -> man1kotabandung.sch.id via Apache proxy (wss://)
 */
const isProduction = import.meta.env.VITE_APP_ENV === 'production';

window.Echo = new Echo({
    broadcaster: 'reverb',
    key: import.meta.env.VITE_REVERB_APP_KEY,
    wsHost: import.meta.env.VITE_REVERB_HOST,
    wsPort: isProduction
        ? Number(import.meta.env.VITE_REVERB_PORT ?? 443)
        : Number(import.meta.env.VITE_REVERB_PORT ?? 8080),
    wssPort: isProduction
        ? Number(import.meta.env.VITE_REVERB_PORT ?? 443)
        : Number(import.meta.env.VITE_REVERB_PORT ?? 8080),
    forceTLS: (import.meta.env.VITE_REVERB_SCHEME ?? 'https') === 'https',
    enabledTransports: isProduction ? ['wss'] : ['ws', 'wss'],
    disableStats: true,
});
