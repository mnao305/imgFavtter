import Vue from 'vue'
import router from './router'

require('./bootstrap');

window.Vue = require('vue');

const app = new Vue({
    router,
    el: '#app',
    render: h => h(require('./app.vue'))
});
