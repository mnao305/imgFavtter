import Vue from 'vue'
import router from './router'
// eslint-disable-next-line no-unused-vars
import axios from 'axios'
import { VueMasonryPlugin } from 'vue-masonry'
import VueAnalytics from 'vue-analytics'

require('./bootstrap')

window.Vue = require('vue')

Vue.use(VueMasonryPlugin)

Vue.use(VueAnalytics, {
  id: 'UA-57438486-3',
  router
})

// eslint-disable-next-line no-unused-vars
const app = new Vue({
  router,
  el: '#app',
  render: h => h(require('./app.vue'))
})
