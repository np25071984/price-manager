/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');

window.Vue = require('vue');

/**
 * The following block of code may be used to automatically register your
 * Vue components. It will recursively scan this directory for the Vue
 * components and automatically register them with their "basename".
 *
 * Eg. ./components/ExampleComponent.vue -> <example-component></example-component>
 */

// const files = require.context('./', true, /\.vue$/i);
// files.keys().map(key => Vue.component(key.split('/').pop().split('.')[0], files(key).default));

Vue.component('table-component', require('./components/TableComponent.vue').default);
Vue.component('button-component', require('./components/ButtonComponent.vue').default);
Vue.component('tab-component', require('./components/TabComponent.vue').default);
Vue.component('modal', require('./components/ModalComponent.vue').default);
Vue.component('pagination', require('laravel-vue-pagination'));
Vue.component('tags-input', require('@voerro/vue-tagsinput').default);

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

import {
    setDiscountAction,
    removeDiscountAction,
    addToShopAction,
    removeFromShopAction,
    itemDestroyAction,
} from './actions.js';

const app = new Vue({
    el: '#app',
    data: {
        showModal: false,
        setDiscountAction,
        removeDiscountAction,
        addToShopAction,
        removeFromShopAction,
        itemDestroyAction,
    },
});

window.setRelation = function (article, obj) {
    const name = obj.closest('TR').getElementsByTagName('td')[2].innerText;
    document.getElementById('article').value = article;
    document.getElementById('name').value = name;
};
