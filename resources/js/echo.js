import Echo from 'laravel-echo';
import Pusher from 'pusher-js';
import { API } from './utilities/api.js';
import { loadProducts } from './user/requisitar.js';

// Fetch user information (this should be your authenticated user data)
API.makeAuthenticatedRequest('/api/user', 'GET', () => {}).then(function (response) {
    const userId = response.data.id;

    // Set up Laravel Echo with Pusher
    window.Pusher = Pusher;

    const echo = new Echo({
        broadcaster: 'pusher',
        key: 'iodhxje42dec3pvfl33g', // Use your Reverb app key
        cluster: 'mt1', // Change this to your Pusher cluster if needed
        forceTLS: false, // Set to true if you are using SSL
        encrypted: false, // Set to true if you are using SSL
        wsHost: "localhost", // Use your Reverb host
        wsPort: 8080, // Use your Reverb port
        wssPort: 443, // Use 443 for SSL
        disableStats: true,
        enabledTransports: ['ws'], // Use WebSocket transport
    });

    // Listen for updates on the cart channel for the authenticated user
    echo.private(`cart.${userId}`)
        .listen(`.cart`, (event) => {
            // Handle the event here (e.g., update the UI)
            window.cart.updateCart(event);
        });

    // Optional: Listen for other general events
    echo.connector.pusher.connection.bind('ping', (data) => {
        console.log('Ping received:', data);
    });

}).catch(error => {
    console.error('Failed to fetch user data:', error);
});