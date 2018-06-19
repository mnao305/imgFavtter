import Vue from 'vue';
import router from './router';
import axios from 'axios';
import { VueMasonryPlugin } from "vue-masonry";
import VueAnalytics from "vue-analytics";

require('./bootstrap');

window.Vue = require('vue');

Vue.use(VueMasonryPlugin);

Vue.use(VueAnalytics, {
    id: 'UA-57438486-3',
    router
  })

const app = new Vue({
    router,
    el: '#app',
    render: h => h(require('./app.vue'))
});
