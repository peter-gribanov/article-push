firebase.initializeApp({
  messagingSenderId: "448358493027"
});

var messaging = firebase.messaging();

messaging.requestPermission()
    .then(function() {
        // Get Instance ID token. Initially this makes a network call, once retrieved
        // subsequent calls to getToken will return from cache.
        messaging.getToken()
            .then(function(currentToken) {
                console.log(currentToken);
                document.getElementById('token').value = currentToken;

                if (currentToken) {
//                        sendTokenToServer(currentToken);
//                        updateUIForPushEnabled(currentToken);
                } else {
                    // Show permission request.
                    console.warn('No Instance ID token available. Request permission to generate one.');
                    // Show permission UI.
//                        updateUIForPushPermissionRequired();
//                        setTokenSentToServer(false);
                }
            })
            .catch(function(err) {
                console.warn('An error occurred while retrieving token. ', err);
//                    showToken('Error retrieving Instance ID token. ', err);
//                    setTokenSentToServer(false);
            });
    })
    .catch(function(err) {
        console.warn('Unable to get permission to notify.', err);
    });
