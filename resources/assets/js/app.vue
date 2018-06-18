<template>
    <div id="app">
        <navbar></navbar>

        <div class="container">
            <router-view></router-view>
        </div>
        <footerbar></footerbar>
    </div>
</template>

<script>
import "../../../node_modules/@fancyapps/fancybox/dist/jquery.fancybox.min.css";
import "../../../node_modules/@fancyapps/fancybox/dist/jquery.fancybox.min.js";
export default {
    components: {
        navbar: require('./components/navbar.vue'),
        footerbar: require('./components/footerbar.vue')
    }
}
</script>


