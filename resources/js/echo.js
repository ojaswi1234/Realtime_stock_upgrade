import Echo from 'laravel-echo';
import Pusher from 'pusher-js';

window.Pusher = Pusher;

window.Echo = new Echo({
    broadcaster: 'pusher',
    key: import.meta.env.VITE_PUSHER_APP_KEY,
    cluster: import.meta.env.VITE_PUSHER_APP_CLUSTER,
    forceTLS: true
});

window.Echo.channel('inventory-updates')
    .listen('.stock.updated', (e) => {
        const alertBox = document.getElementById('realtime-alert');
        const alertMsg = document.getElementById('alert-message');
        
        if (alertBox && alertMsg) {
            alertMsg.innerText = e.message + " (New Qty: " + e.inventory.quantity + ")";
            alertBox.classList.remove('d-none');
            
            // Auto hide after 5 seconds
            setTimeout(() => {
                alertBox.classList.add('d-none');
            }, 5000);
        }
        
        // If on dashboard, we might want to refresh part of the UI or just reload
        // console.log('Stock Updated Event Received', e);
    });
