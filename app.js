
firebase.initializeApp({
    messagingSenderId: '448358493027'
});

var messaging = firebase.messaging();

document.getElementById('send').onclick = function() {
    if (isTokenSentToServer()) {
        send('/send.php');
    } else {
        console.error('Token not sent to server.');
    }
};

messaging.onMessage(function(payload) {
    console.log('Message received. ', payload);
    new Notification(payload.data.title, payload.data);
});

messaging.requestPermission()
    .then(function() {
        // Get Instance ID token. Initially this makes a network call, once retrieved
        // subsequent calls to getToken will return from cache.
        messaging.getToken()
            .then(function(currentToken) {
                console.log(currentToken);

                if (currentToken) {
                    // send token to the server if is isn't sent before
                    sendTokenToServer(currentToken);
                } else {
                    console.warn('No Instance ID token available. Request permission to generate one.');
                    setTokenSentToServer(false);
                }
            })
            .catch(function(err) {
                console.warn('An error occurred while retrieving token. ', err);
                setTokenSentToServer(false);
            });
    })
    .catch(function(err) {
        console.warn('Unable to get permission to notify.', err);
    });

function send(url, data) {
    var xhr = new XMLHttpRequest();
    xhr.open('POST', url, true);

    if (data) {
        var params = '';
        for (var property in data) {
            if (data.hasOwnProperty(property)) {
                if (params != '') {
                    params += '&';
                }
                params += property + '=' + encodeURIComponent(data[property]);
            }
        }

        xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
        xhr.send(params);
    } else {
        xhr.send(null);
    }
}

function sendTokenToServer(currentToken) {
    // always send token for fix session expire
    if (true || !isTokenSentToServer()) {
        console.log('Sending token to server...');
        send('/register.php', {token: currentToken});
        setTokenSentToServer(true);
    } else {
        console.log('Token already sent to server so won\'t send it again unless it changes');
    }
}

function isTokenSentToServer() {
    return window.localStorage.getItem('sentToServer') == 1;
}

function setTokenSentToServer(sent) {
    window.localStorage.setItem('sentToServer', sent ? 1 : 0);
}
