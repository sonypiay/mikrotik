
/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');

window.Vue = require('vue');

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

Vue.component('loginsection', require('./components/Login.vue'));
Vue.component('users', require('./components/pages/Users.vue'));
Vue.component('usermikrotik', require('./components/pages/UserMikrotik.vue'));
Vue.component('zoneregion', require('./components/pages/ZoneRegion.vue'));
Vue.component('zonedomain', require('./components/pages/ZoneDomain.vue'));
Vue.component('devices', require('./components/pages/Devices.vue'));
Vue.component('getdevices', require('./components/pages/DetailDevice.vue'));
Vue.component('dashboardsection', require('./components/pages/Dashboard.vue'));
Vue.component('locationdevice', require('./components/pages/Location.vue'));
Vue.component('controllerdevice', require('./components/pages/Controller.vue'));
Vue.component('userprofile', require('./components/pages/UserProfile.vue'));

const app = new Vue({
    el: '#app'
});
