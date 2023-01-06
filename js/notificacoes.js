const publicVapidKey = 'BPPvOxxaLpZ9EWAWALLfZUhmOQv-6jXDCVnt8yat4n4bcdvVJ1n0n1gHPa3WNw_P4W5lS_J5E0THSinXYo2yyVk';

// Verifica se é possivel utilizar o service Worker
if('serviceWorker' in navigator){
    send().catch(err => console.error(err));
}

async function send(){
    //Regista o Service Worker
    const register = await navigator.serviceWorker.register('/js/worker.js');

    await register.pushManager.subscribe({
        userVisibleOnly: true,
        applicationServerKey: urlBase64ToUint8Array(publicVapidKey)
    }).then((pushSubscription) => {

        //Subscreve
        result = api("not_sub",{"subscription":JSON.stringify(pushSubscription)});
        if (result['est']=='sucesso'){
            console.log('Notificações ativas');
        } else {
            console.debug(result['est']);
        }

    }, (error) => {
        console.error(error);
    });
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