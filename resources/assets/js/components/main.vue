<template>
    <div>
        <p>{{user.name}}さんのいいね一覧</p>
        <div v-for="fav in favList">
            <!-- 画像表示部 -->
            <img :src="fav.extended_entities.media[0].media_url_https">
            <div v-if="fav.extended_entities.media[1]">
                <img :src="fav.extended_entities.media[1].media_url_https">
            </div>
            <div v-if="fav.extended_entities.media[2]">
                <img :src="fav.extended_entities.media[2].media_url_https">
            </div>
            <div v-if="fav.extended_entities.media[3]">
                <img :src="fav.extended_entities.media[3].media_url_https">
            </div>
            <br>
            <br>
        </div>
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
        }
    }
}
</script>