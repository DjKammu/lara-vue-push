require('./bootstrap');

 window.Vue = require('vue');

 Vue.component('push-component', require('./components/PushComponent.vue').default);

 const app = new Vue({
   el: '#app',
 });

