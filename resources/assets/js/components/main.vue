<template>
    <div>
        <p>メインなんか</p>
        <p>Hello {{ $route.params.username }}</p>
        <div v-for="fav in favList">
            {{ fav.text }}
        </div>
    </div>
</template>

<script>
export default {
    created() {
        this.getFav();
    },
    data() {
        return {
            favList: [],
        }
    },
    methods: {
        getFav() {
            var tmp = this;
            axios.get('fav')
            .then(function(res) {
                console.log(res.data);
                tmp.favList = res.data;
            })
        }
    }
}
</script>