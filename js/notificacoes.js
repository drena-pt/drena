const publicVapidKey = 'BPPvOxxaLpZ9EWAWALLfZUhmOQv-6jXDCVnt8yat4n4bcdvVJ1n0n1gHPa3WNw_P4W5lS_J5E0THSinXYo2yyVk';

async function not_sub(metodo){
    if(!'serviceWorker' in navigator){
        return console.error('Service worker não disponivel');
    }
    //Regista o Service Worker
    const register = await navigator.serviceWorker.register('/js/worker.js');

    return await register.pushManager.subscribe({
        userVisibleOnly: true,
        applicationServerKey: urlBase64ToUint8Array(publicVapidKey)
    }).then((pushSubscription) => {
        
        switch (metodo){
            case 'ac':
                result = api("not",{"ac":"subscrever","sub":JSON.stringify(pushSubscription)});
                if (result['est']=='true'){
                    console.log('Subscrito com sucesso');
                } else if (result['est']=='false'){
                    console.log('Subscrição removida');
                }
                return result['est'];
                break;
            case 'ob':
                result = api("not",{"ob":"subscrever","sub":JSON.stringify(pushSubscription)});
                return result['est'];
                break;
            default:
                break;
        }

        return;

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