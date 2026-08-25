// sw.js - Service Worker de SisAdMed
self.addEventListener('push', function(event) {
    if (!event.data) {
        console.log('Evento Push recibido sin contenido.');
        return;
    }

    try {
        const data = event.data.json();

        const options = {
            body: data.body,
            icon: data.icon || '/assets/img/logo-sisadmed.png', // Ajusta a la ruta de tu logo
            badge: data.badge || '/assets/img/logo-sisadmed.png',
            data: {
                url: data.url || '/'
            },
            vibrate: [100, 50, 100],
            requireInteraction: true // Mantiene la notificación visible hasta que el usuario interactúe
        };

        event.waitUntil(
            self.registration.showNotification(data.title, options)
        );
    } catch (e) {
        console.error('Error al procesar el mensaje Push:', e);
    }
});

// Evento al hacer clic sobre la notificación emergente
self.addEventListener('notificationclick', function(event) {
    event.notification.close(); // Cierra el banner flotante

    // Abre o enfoca la ventana de SisAdMed en la URL configurada
    event.waitUntil(
        clients.matchAll({ type: 'window', includeUncontrolled: true }).then(function(clientList) {
            const targetUrl = event.notification.data.url;

            for (let i = 0; i < clientList.length; i++) {
                const client = clientList[i];
                if (client.url.includes(targetUrl) && 'focus' in client) {
                    return client.focus();
                }
            }

            if (clients.openWindow) {
                return clients.openWindow(targetUrl);
            }
        })
    );
});