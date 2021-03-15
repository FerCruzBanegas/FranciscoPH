require('./bootstrap');

window.Vue = require('vue');


// Dependencies --------------------------------------
import Toasted from 'vue-toasted';
import VueClip from 'vue-clip'
import Multiselect from 'vue-multiselect'
import swal from 'sweetalert';
import VueContentPlaceholders from 'vue-content-placeholders'
import CripLoading from "crip-vue-loading"
import axios from 'axios'
import VueCoreVideoPlayer from 'vue-core-video-player'
import Viewer from 'v-viewer'
import VueQRCodeComponent from 'vue-qrcode-component'

const moment = require('moment')
require('moment/locale/es')
moment.locale('es')


Vue.use(Viewer)
Vue.use(VueCoreVideoPlayer)
Vue.use(CripLoading, {axios})

Vue.use(require('vue-moment'), {
    moment
})
Vue.use(Toasted)
Vue.toasted.register('error', message => message, {
    position : 'bottom-center',
    duration : 3000
})
Vue.use(VueClip)
Vue.component('multiselect', Multiselect)
Vue.use(VueContentPlaceholders)

Vue.component('qr-code', VueQRCodeComponent)

// Layout
Vue.component('sidebar', require('./components/layout/Sidebar.vue'));

// Dashboard
Vue.component('users-count', require('./components/dashboard/UsersCount.vue'));
Vue.component('roles-count', require('./components/dashboard/RolesCount.vue'));
Vue.component('services-count', require('./components/dashboard/ServicesCount.vue'));
Vue.component('disk-usage', require('./components/dashboard/DiskUsage.vue'));

// Profile
Vue.component('profile', require('./components/profile/Profile.vue'));
Vue.component('profile-password', require('./components/profile/Password.vue'));

// Users
Vue.component('users-index', require('./components/users/Index.vue'));
Vue.component('users-create', require('./components/users/Create.vue'));
Vue.component('users-edit', require('./components/users/Edit.vue'));

// Customers
Vue.component('customers-index', require('./components/customers/Index.vue'));
Vue.component('customers-create', require('./components/customers/Create.vue'));
Vue.component('customers-edit', require('./components/customers/Edit.vue'));

// Services
Vue.component('services-index', require('./components/services/Index.vue'));
Vue.component('services-create', require('./components/services/Create.vue'));
Vue.component('services-show', require('./components/services/Show.vue'));
Vue.component('services-edit', require('./components/services/Edit.vue'));

// Roles
Vue.component('roles-index', require('./components/roles/Index.vue'));
Vue.component('roles-create', require('./components/roles/Create.vue'));
Vue.component('roles-edit', require('./components/roles/Edit.vue'));

const app = new Vue({
    el: '#app'
});
