console.log('Service Worker loaded');

self.addEventListener('push', e => {
    console.debug('Notificação Recebida');
    const data = e.data.json();
    self.registration.showNotification(data.title, data.options);
});
this.addEventListener('notificationclick', function(event) {
    if (!event.action) {
      // Was a normal notification click
      console.debug('Notification Click.');
      return;
    }
    event.notification.close();
    if (event.action!='null'){
        clients.openWindow(event.action);
    }
});