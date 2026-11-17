import './bootstrap';

import Echo from 'laravel-echo';
import Pusher from 'pusher-js';

const userId = document.body.dataset.userId;
const appKey = import.meta.env.VITE_REVERB_APP_KEY;

if (userId && appKey) {
    window.Pusher = Pusher;

    const scheme = import.meta.env.VITE_REVERB_SCHEME || 'http';
    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content;

    window.Echo = new Echo({
        broadcaster: 'reverb',
        key: appKey,
        wsHost: import.meta.env.VITE_REVERB_HOST || window.location.hostname,
        wsPort: Number(import.meta.env.VITE_REVERB_PORT || 8080),
        wssPort: Number(import.meta.env.VITE_REVERB_PORT || 8080),
        forceTLS: scheme === 'https',
        enabledTransports: ['ws', 'wss'],
        authEndpoint: document.body.dataset.broadcastAuthUrl,
        auth: {
            headers: csrfToken ? { 'X-CSRF-TOKEN': csrfToken } : {},
        },
    });

    const updateUnreadCount = (count) => {
        document.querySelectorAll('[data-notification-count]').forEach((badge) => {
            const limit = Number(badge.dataset.countLimit || 99);
            badge.textContent = count > limit ? `${limit}+` : String(count);
            badge.classList.toggle('hidden', count < 1);
        });
    };

    const toast = document.querySelector('[data-notification-toast]');
    const showNotification = (notification) => {
        updateUnreadCount(notification.unread_count);

        if (!toast) {
            return;
        }

        toast.querySelector('[data-notification-title]').textContent = notification.titulo;
        toast.querySelector('[data-notification-message]').textContent = notification.mensaje;
        toast.classList.remove('hidden');

        window.clearTimeout(toast.hideTimeout);
        toast.hideTimeout = window.setTimeout(() => toast.classList.add('hidden'), 7000);
    };

    window.Echo.private(`users.${userId}`)
        .listen('.notification.created', showNotification);
}
