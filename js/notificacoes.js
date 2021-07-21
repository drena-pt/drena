const publicVapidKey = 'BMdv0ZqCqf65dg7u7WGQz7y7cAiTnbkVfHls3mVFUD2Duuhm5hs51NA9ZNY9TrIqdmjZnXprnZXOHM-eW-WQXQE';

// Verifica se é possivel utilizar o service Worker
if('serviceWorker' in navigator){
    send().catch(err => //console.error(err)
        navigator.serviceWorker.getRegistrations().then(function(registrations) {
            for(let registration of registrations) {
            registration.unregister()
        } })
    );
}

// regista o SW, regista o push, envia notificação push
async function send(){
    // registar o SW
    //console.log('Registando service worker...');
    const register = await navigator.serviceWorker.register('/js/worker.js');
    //console.log('Service worker registado');
    
    // registar o push
    //console.log('Registando o Push...');
    const subscription = await register.pushManager.subscribe({
        userVisibleOnly: true,
        applicationServerKey: urlBase64ToUint8Array(publicVapidKey)
    });
    //console.log('Push registado');

    // Enviar registo
    //console.log('A enviar o registo...');
    await fetch('https://drena.pt:3000/subscrever', {
        method: 'POST',
        body: JSON.stringify({
            "subscription":subscription,
            "uti_nut":sub_uti_nut,
            "uti_cod":sub_uti_cod
        }),
        headers: {
            'content-type': 'application/json'
        }
    });
    //console.log('Registo enviado');
    console.log('Notificações ativas');
}

function urlBase64ToUint8Array(base64String) {
    var padding = '='.repeat((4 - base64String.length % 4) % 4);
    var base64 = (base64String + padding)
        .replace(/\-/g, '+')
        .replace(/_/g, '/');

    var rawData = window.atob(base64);
    var outputArray = new Uint8Array(rawData.length);

    for (var i = 0; i < rawData.length; ++i) {
        outputArray[i] = rawData.charCodeAt(i);
    }
    return outputArray;
}