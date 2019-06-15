/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

Vue.component('chat', require('./components/ChatComponent.vue'));

Vue.component('favoriteVendor', require('./components/FavoriteVendorComponent.vue'));
Vue.component('confirmedDates', require('./components/QuotesConfirmedDatesComponent.vue'));

Vue.component('selectLocations', require('./components/SelectLocationsComponent.vue'));
Vue.component('vendorLocations', require('./components/VendorLocationsComponent.vue'));

Vue.component('savedJob', require('./components/SavedJobComponent.vue'));
Vue.component('specifications', require('./components/SpecificationsComponent.vue'));
Vue.component('quotesModal', require('./components/QuotesModalComponent.vue'));
Vue.component('milestones', require('./components/MilestonesComponent.vue'));

const app = new Vue({
    el: '#app'
});


window.setTimeout(function() {
    $(".alert").fadeTo(500, 0).slideUp(500, function(){
        $(this).remove();
    });
}, 8000);

NProgress.configure({ showSpinner: true });
NProgress.start();

$(document).ready(function(){
    NProgress.done();
})

$('input[type="submit"], button[type="submit"]').on('click', function(){
    NProgress.start();
});

$('.wb-datepicker').datepicker();