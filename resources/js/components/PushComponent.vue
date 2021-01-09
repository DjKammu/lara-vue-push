<template>
  <div>Hello World.</div>
</template>

<script>

import firebase from 'firebase/app';

import 'firebase/messaging';

const firebaseConfig = {
  apiKey: process.env.MIX_FIREBASE_API_KEY,
  authDomain: process.env.MIX_FIREBASE_PROJECT_ID+".firebaseapp.com",
  databaseURL: "https://"+process.env.MIX_FIREBASE_PROJECT_ID+".firebaseio.com",
  projectId: process.env.MIX_FIREBASE_PROJECT_ID,
  storageBucket: process.env.MIX_FIREBASE_PROJECT_ID+".appspot.com",
  messagingSenderId: process.env.MIX_FIREBASE_MESSANGER_ID,
  appId: process.env.MIX_FIREBASE_APP_ID,
  measurementId: process.env.MIX_FIREBASE_MEASUREMENT_ID
};

firebase.initializeApp(firebaseConfig);

 export default {

    methods:{ 

     saveToken: function(token) {

       const url = 'api/save-token'
    
        const payload = {
          token: token,
          type: 'web'
        }
        axios.post(url, payload)
          .then((response) => {
            console.log('Successfully saved notification token!')
            console.log(response.data)
          })
          .catch((error) => {
            console.log('Error: could not save notification token')
          })
        },
      },

    mounted() {
    	   const messaging = firebase.messaging();

         messaging.requestPermission().then(() => {
          console.log('Notification permission granted.')
          messaging.getToken().then((token) => {
            console.log('New token created: ', token)
            this.saveToken(token)
          })
        }).catch((err) => {
          console.log('Unable to get permission to notify.', err)
        })


    	   // messaging
        //     .requestPermission()
        //     .then(function () {
        //         return messaging.getToken()
        //     })
        //     .then(function(token) {
        //         console.log(token);
                
        //     }).catch(function (err) {
        //         console.log('Error '+ err);
        //     });
      
		    messaging.onMessage(function(payload) {
		        const noteTitle = payload.notification.title;
		        const noteOptions = {
		            body: payload.notification.body,
		            icon: payload.notification.icon,
		        };
		        new Notification(noteTitle, noteOptions);
		    });
    },
    

  };
</script>