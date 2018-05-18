<template>
    <div>
        <p>{{user.name}}さんのいいね一覧</p>
        <div v-masonry transition-duration="0.5s" item-selector=".item" class="mainContents">
            <div v-for="fav in favList" class="tweet">
                <!-- 画像表示部 -->
                <img v-masonry-tile class="item" :src="fav.extended_entities.media[0].media_url_https">
                <img v-masonry-tile class="item" v-if="fav.extended_entities.media[1]" :src="fav.extended_entities.media[1].media_url_https">
                <img v-masonry-tile class="item" v-if="fav.extended_entities.media[2]" :src="fav.extended_entities.media[2].media_url_https">
                <img v-masonry-tile class="item" v-if="fav.extended_entities.media[3]" :src="fav.extended_entities.media[3].media_url_https">
            </div>
        </div>
    </div>
</template>

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