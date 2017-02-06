
/**
 * First we will load all of this project's JavaScript dependencies which
 * include Vue and Vue Resource. This gives a great starting point for
 * building robust, powerful web applications using Vue and Laravel.
 */
require('./bootstrap');

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the body of the page. From here, you may begin adding components to
 * the application, or feel free to tweak this setup for your needs.
 */

// Vue.component('User', require('./components/backoffice/User.vue'));
Vue.component('Index', require('./components/frontoffice/Index.vue'));
Vue.component('NavBar', require('./components/frontoffice/NavBar.vue'));
Vue.component('Tabs', require('./components/frontoffice/Tabs.vue'));
Vue.component('Tab', require('./components/frontoffice/Tab.vue'));
Vue.component('CardUser', require('./components/frontoffice/CardUser.vue'));
Vue.component('CardTruck', require('./components/frontoffice/CardTruck.vue'));
Vue.component('ModalAddUser', require('./components/frontoffice/ModalAddUser.vue'));
Vue.component('ModalAddTruck', require('./components/frontoffice/ModalAddTruck.vue'));
Vue.component('ModalEditUser', require('./components/frontoffice/ModalEditUser.vue'));
Vue.component('ModalEditTruck', require('./components/frontoffice/ModalEditTruck.vue'));
Vue.component('ModalDeleteUser', require('./components/frontoffice/ModalDeleteUser.vue'));
Vue.component('ModalDeleteTruck', require('./components/frontoffice/ModalDeleteTruck.vue'));

const app = new Vue({
    el: '#app'
});
