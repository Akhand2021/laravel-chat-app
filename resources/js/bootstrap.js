import Echo from 'laravel-echo';
import Pusher from 'pusher-js';

window.Pusher = Pusher;
console.log(import.meta.env.VITE_PUSHER_APP_KEY);
console.log(import.meta.env.VITE_PUSHER_APP_CLUSTER);
window.Echo = new Echo({
    broadcaster: 'pusher',
    key: import.meta.env.VITE_PUSHER_APP_KEY,
    cluster: import.meta.env.VITE_PUSHER_APP_CLUSTER || 'ap2',
    forceTLS: true,
    authorizer: (channel, options) => {
        return {
            authorize: (socketId, callback) => {
                // Get the CSRF token from the meta tag
                const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

                // Create a FormData object
                const formData = new FormData();
                formData.append('socket_id', socketId);
                formData.append('channel_name', channel.name);

                // Send the request with the CSRF token in the header
                fetch('/broadcasting/auth', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': token,
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    body: formData,
                    credentials: 'same-origin'
                })
                    .then(response => {
                        if (!response.ok) {
                            throw new Error('Network response was not ok: ' + response.status);
                        }
                        return response.json();
                    })
                    .then(data => {
                        console.log('Authorization successful:', data);
                        callback(null, data);
                    })
                    .catch(error => {
                        console.error('Authorization error:', error);
                        callback(error);
                    });
            }
        };
    }
});
