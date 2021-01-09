/*
Give the service worker access to Firebase Messaging.
Note that you can only use Firebase Messaging here, other Firebase libraries are not available in the service worker.
*/
importScripts('https://www.gstatic.com/firebasejs/7.23.0/firebase-app.js');
importScripts('https://www.gstatic.com/firebasejs/7.23.0/firebase-messaging.js');
   
/*
Initialize the Firebase app in the service worker by passing in the messagingSenderId.
* New configuration for app@pulseservice.com
*/
const firebaseConfig = {
  apiKey: "AIzaSyCIgSQHzizZwlzZebgYDan51DToaoJpJkY",
  authDomain: "fir-8fccd.firebaseapp.com",
  projectId: "fir-8fccd",
  storageBucket: "fir-8fccd.appspot.com",
  messagingSenderId: "826859615236",
  appId: "1:826859615236:web:5101134c36e05681723151",
  measurementId: "G-EJFN9P2WJM"
};

firebase.initializeApp(firebaseConfig);

/*
Retrieve an instance of Firebase Messaging so that it can handle background messages.
*/
const messaging = firebase.messaging();
messaging.setBackgroundMessageHandler(function(payload) {
    console.log(
        "[firebase-messaging-sw.js] Received background message ",
        payload,
    );
    /* Customize notification here */
    const notificationTitle = "Background Message Title";
    const notificationOptions = {
        body: "Background Message body.",
        icon: "/itwonders-web-logo.png",
    };
  
    return self.registration.showNotification(
        notificationTitle,
        notificationOptions,
    );
});