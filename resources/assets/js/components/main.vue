<template>
    <div>
        <p>{{this.user.name}}さんのいいね一覧</p>
        <div v-for="fav in favList">
            {{ fav.text }}
            {{ fav.extended_entities.media.media_url_https }}
            <img :src="fav.extended_entities.media[0].media_url_https">
        </div>
        <br>
    </div>
</template>

<style>
    img {
        max-width: 100px;
        max-height: 100px;
    }
</style>


<script>
export default {
    created() {
        this.getFav();
        this.loginCheck();
    },
    data() {
        return {
            favList: [],
            user: null
        }
    },
    methods: {
        loginCheck() {
            axios.get('api/twitterUserCheck')
            .then(res => {
                console.log(res.data);
                this.user = res.data;
                if (this.user === 'notLogin') {
                    console.log('');
                }
            })
        },
        getFav() {
            axios.get('api/fav')
            .then(res => {
                console.log(res.data);
                // 画像つきのみを抽出
                this.favList = res.data.filter(function (elem) {
                    return(typeof(elem.extended_entities) !== "undefined");
                });
            })
        },
        tmp () {
            if (Boolean(fav)) {
                return true;
            } else {
                return false;
            }
        }
    }
}
</script>