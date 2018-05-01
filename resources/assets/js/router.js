import VueRouter from "vue-router";
import Vue from "vue";

Vue.use(VueRouter);

export default new VueRouter({
    mode: "history",
    routes: [
        { path: "/", component: require("./components/index.vue") },
        { path: "/main", component: require("./components/main.vue") },
        { path: "/userconfig", component: require("./components/userConfig.vue") },
        { path: "/about", component: require("./components/about.vue") },
    ],
    scrollBehavior(to, from, savePosition) {
        if (savePosition) {
            return savePosition;
        } else {
            return { x: 0, y: 0 };
        }
    },
});
