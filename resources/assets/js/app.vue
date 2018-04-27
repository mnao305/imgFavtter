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
export default {
    components: {
        navbar: require('./components/navbar.vue'),
        footerbar: require('./components/footerbar.vue')
    }
}
</script>


