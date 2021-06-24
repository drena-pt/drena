console.log('Service Worker loaded');

self.addEventListener('push', e => {
    const data = e.data.json();
    console.log('push recebido');
    self.registration.showNotification(data.title, {
        body: data.body,
        image: data.image,
        badge: data.badge,
        icon: data.icon,
        timestamp: data.timestamp,
        actions: data.actions,
    });
});
this.addEventListener('notificationclick', function(event) {
    if (!event.action) {
      // Was a normal notification click
      console.log('Notification Click.');
      return;
    }
    event.notification.close();
    if (event.action!='null'){
        clients.openWindow(event.action);
    }
});