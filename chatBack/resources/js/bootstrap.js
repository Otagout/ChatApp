import axios from 'axios';
window.axios = axios;
window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';

// Import Laravel Echo
import Echo from 'laravel-echo';

// Import Pusher
import Pusher from 'pusher-js';
window.Pusher = Pusher;

// Initialize Echo
window.Echo = new Echo({
  broadcaster: 'pusher',
  key: import.meta.env.VITE_PUSHER_APP_KEY, // Use Vite-prefixed env variable
  cluster: import.meta.env.VITE_PUSHER_APP_CLUSTER ?? 'eu', // Default to 'eu' cluster if not defined
  wsHost: import.meta.env.VITE_PUSHER_HOST ? import.meta.env.VITE_PUSHER_HOST : `ws-${import.meta.env.VITE_PUSHER_APP_CLUSTER}.pusher.com`,
  wsPort: import.meta.env.VITE_PUSHER_PORT ?? 80, // Default to 80 for WebSocket connections
  wssPort: import.meta.env.VITE_PUSHER_PORT ?? 443, // Default to 443 for WebSocket secure connections
  forceTLS: (import.meta.env.VITE_PUSHER_SCHEME ?? 'https') === 'https', // Ensure TLS is used if specified
  enabledTransports: ['ws', 'wss'], // Enable WebSocket and Secure WebSocket connections
});
