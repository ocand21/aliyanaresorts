/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');

window.Vue = require('vue');

import Vue from 'vue'
import money from 'v-money'
Vue.use(money, {precision:4})


import { Form, HasError, AlertError} from 'vform';
import {ServerTable, ClientTable, Event} from 'vue-tables-2';
Vue.use(ServerTable, {}, false, 'bootstrap3');
Vue.use(ClientTable, {}, false, 'bootstrap3');
Vue.use(Event);

window.Form = Form;
Vue.component(HasError.name, HasError)
Vue.component(AlertError.name, AlertError)

import swal from 'sweetalert2';
window.swal = swal;
const toast = swal.mixin({
  toast: true,
  position: 'top-end',
  showConfirmButton: false,
  timer: 3000
});
window.toast = toast;

import VueProgressBar from 'vue-progressbar';
Vue.use(VueProgressBar, {
  color: '#bffaf3',
  failedColor: '#874b4b',
  thickness: '5px',
  transition: {
    speed: '0.2s',
    opacity: '0.6s',
    termination: 300
  },
  autoRevert: true,
  location: 'top',
  inverse: false
});

import VueCurrencyFilter from 'vue-currency-filter'
Vue.use(VueCurrencyFilter)

Vue.use(VueCurrencyFilter,
{
  symbol : 'Rp',
  thousandsSeparator: '.',
  fractionCount: 2,
  fractionSeparator: ',',
  symbolPosition: 'front',
  symbolSpacing: true
})
const moment = require('moment')
require('moment/locale/id')
Vue.filter('myDate', function(created){
	return moment(created).format('dddd, Do MMMM YYYY');
  // return moment().to(data);
});

/**
 * The following block of code may be used to automatically register your
 * Vue components. It will recursively scan this directory for the Vue
 * components and automatically register them with their "basename".
 *
 * Eg. ./components/ExampleComponent.vue -> <example-component></example-component>
 */

// const files = require.context('./', true, /\.vue$/i);
// files.keys().map(key => Vue.component(key.split('/').pop().split('.')[0], files(key).default));
import VueRouter from 'vue-router'
window.Fire = new Vue();
Vue.use(VueRouter)

let routes = [
  { path: '/admin/dashboard', component: require('./components/Admin/Dashboard.vue').default},
  { path: '/admin/pengaturan', component: require('./components/Admin/Pengaturan.vue').default},
  { path: '/admin/kamar', component: require('./components/Admin/Kamar.vue').default, name: 'kamar'},
  { path: '/admin/kamar/tipe/tambah', component: require('./components/Admin/TambahTipeKamar.vue').default, name: 'tambah-tipe'},
  { path: '/admin/kamar/tipe/edit/:id', component: require('./components/Admin/EditTipeKamar.vue').default, name: 'edit-tipe'},
  { path: '/admin/kamar/tipe/detail/:id', component: require('./components/Admin/DetilTipeKamar.vue').default, name: 'detil-tipe'},

  { path: '/admin/fasilitas', component: require('./components/Admin/Fasilitas.vue').default, name: 'fasilitas'},
  { path: '/admin/fasilitas/detail/:id', component: require('./components/Admin/DetailFasilitas.vue').default, name: 'detil-fasilitas'},

  { path: '/admin/departemen', component: require('./components/Admin/Departemen.vue').default, name: 'departemen'},

  { path: '/admin/staff', component: require('./components/Admin/Staff.vue').default, name: 'staff'},

  { path: '/admin/bookings', component: require('./components/Admin/Reservasi/Bookings.vue').default, name: 'bookings'},
  { path: '/admin/booking/room', component: require('./components/Admin/Reservasi/CariKamar.vue').default, name: 'cari-kamar'},
  { path: '/admin/booking/detail/:kode_booking', component: require('./components/Admin/Reservasi/DetilBooking.vue').default, name: 'detil-booking'},

  { path: '/admin/check-in', component: require('./components/Admin/Reservasi/Checkin.vue').default, name: 'check-in'},
  { path: '/admin/check-in/:kode_booking', component: require('./components/Admin/Reservasi/FormCheckin.vue').default, name: 'form-checkin'},
  { path: '/admin/check-out', component: require('./components/Admin/Reservasi/Checkout.vue').default, name: 'check-out'},
  { path: '/admin/check-out/:kode_booking', component: require('./components/Admin/Reservasi/FormCheckout.vue').default, name: 'form-checkout'},
  { path: '/admin/canceled', component: require('./components/Admin/Reservasi/Canceled.vue').default, name: 'canceled'},


  { path: '/admin/inhouse', component: require('./components/Admin/Reservasi/Inhouse.vue').default, name: 'inhouse'},

  { path: '/admin/payment/tagihan', component: require('./components/Admin/Payment/Tagihan.vue').default, name: 'tagihan'},
  { path: '/admin/payment/tagihan/:kode_booking', component: require('./components/Admin/Payment/DetilTagihan.vue').default, name:'detil-tagihan'},

  { path: '/admin/master-data/slideshow', component: require('./components/Admin/MasterData/Slideshow.vue').default, name: 'slideshow'},

  { path: '/admin/master-data/menu-resto', component: require('./components/Admin/MasterData/MenuResto.vue').default, name: 'menu-resto'},
  { path: '/admin/master-data/menu-resto/tambah', component: require('./components/Admin/MasterData/TambahMenu.vue').default, name: 'tambah-resto'},

  { path: '/admin/master-data/metode-pembayaran', component: require('./components/Admin/MasterData/MetodePembayaran.vue').default, name: 'metode-pembayaran'},

  { path: '/admin/payment/pembayaran', component: require('./components/Admin/Payment/Pembayaran.vue').default, name: 'pembayaran'},
  { path: '/admin/master-data/charge', component: require('./components/Admin/MasterData/Charges.vue').default, name: 'charges'},
]

const router = new VueRouter({
	mode: 'history',
	routes
})
/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

 const app = new Vue({
     el: '#app',
     router,
     methods:{
       printme() {
         window.print();
       },
       markasread(){
         $.get('/notif-dibaca')
       }
     }
 });
