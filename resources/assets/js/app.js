import Vue from 'vue';
import router from './router';
import axios from 'axios';
import { VueMasonryPlugin } from "vue-masonry";

require('./bootstrap');

window.Vue = require('vue');

Vue.use(VueMasonryPlugin);

const app = new Vue({
    router,
    el: '#app',
    render: h => h(require('./app.vue'))
});
