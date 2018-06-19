<template>
    <div>
        <p>{{user.name}}さんのいいね一覧</p>
        <div v-masonry transition-duration="0.5s" item-selector=".item" class="mainContents">
            <div v-for="fav in favList" class="tweet">
                <div v-if="fav.extended_entities.media[0].type == 'video'" v-masonry-tile class="item video">
                    <!-- 動画だったら動画を表示 -->
                    <a :href="fav.extended_entities.media[0].video_info.variants[1].url" :data-fancybox="fav.id_str" :data-caption="fav.text + ` By ` + fav.user.name + `<br><a href='https://twitter.com/` + fav.user.screen_name + `/status/` + fav.id_str + `' target='_blank'>Twitterで元ツイートを見る→</a>`">
                        <img :src="fav.extended_entities.media[0].media_url_https">
                        <img src="/img/play.png" class="playBtn">
                    </a>
                </div>
                <div v-else>
                    <!-- 画像表示部 -->
                    <div v-masonry-tile class="item img">
                    <a :href="fav.extended_entities.media[0].media_url_https" :data-fancybox="fav.id_str" :data-caption="fav.text + ` By ` + fav.user.name + `<br><a href='https://twitter.com/` + fav.user.screen_name + `/status/` + fav.id_str + `' target='_blank'>Twitterで元ツイートを見る→</a>`">
                        <img :src="fav.extended_entities.media[0].media_url_https">
                    </a>
                    </div>
                    <div v-masonry-tile class="item img">
                    <a v-if="fav.extended_entities.media[1]" :href="fav.extended_entities.media[1].media_url_https" :data-fancybox="fav.id_str">
                        <img :src="fav.extended_entities.media[1].media_url_https">
                    </a>
                    </div>
                    <div v-masonry-tile class="item img">
                    <a v-if="fav.extended_entities.media[2]" :href="fav.extended_entities.media[2].media_url_https" :data-fancybox="fav.id_str">
                        <img :src="fav.extended_entities.media[2].media_url_https">
                    </a>
                    </div>
                    <div v-masonry-tile class="item img">
                    <a v-if="fav.extended_entities.media[3]" :href="fav.extended_entities.media[3].media_url_https" :data-fancybox="fav.id_str">
                        <img :src="fav.extended_entities.media[3].media_url_https">
                    </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    created() {
        this.loginCheck();
        this.heightCheck();
        this.getFav();
    },
    data() {
        return {
            favList: [],
            user: null,
            tmpFavId: null,
            scrollY: 0,
            pageHeight: 0,
            windowHeight: 0,
            flag: false
        }
    },
    mounted() {
        window.addEventListener('scroll', this.handleScroll);
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
                // 取得した中で一番古いツイートのIDを取得
                this.tmpFavId = res.data[res.data.length - 1].id_str;
                console.log(this.tmpFavId);
                // 画像つきのみを抽出
                this.favList = res.data.filter(function (elem) {
                    return(typeof(elem.extended_entities) !== "undefined");
                });
            })
        },
        addFav() {
            let params = { id: this.tmpFavId };
            axios.get('api/addfav', { params })
            .then(res => {
                console.log(res.data);
                // 取得した中で一番古いツイートのIDを取得
                this.tmpFavId = res.data[res.data.length - 1].id_str;
                console.log(this.tmpFavId);
                // 先頭に同じ内容が入ってしまう対応
                // 1つ目を消す
                res.data.shift();
                // 画像つきのみを抽出
                let tmpList = res.data.filter(function (elem) {
                    return(typeof(elem.extended_entities) !== "undefined");
                });
                // 今ある配列の後ろに結合
                Array.prototype.push.apply(this.favList, tmpList);
                // 配列の長さを更新
                this.favList.splice(this.favList.length);
                // ページの高さを更新する
                this.heightCheck();
            })
        },
        handleScroll() {
            // もっとスマートに実装したい
            this.heightCheck();
            this.scrollY = window.scrollY;
            if (this.flag == false && this.scrollY + (this.windowHeight * 3) >= this.pageHeight) {
                // ページ最下部に来たらaddFav実行
                // 複数回実行されないようフラグを立てる
                this.flag = true;
                this.addFav();
                // 1秒経ったら実行できるように
                setTimeout(() => {
                    this.flag = false;
                }, 1000);
            }
        },
        heightCheck() {
            this.pageHeight = document.body.scrollHeight;
            this.windowHeight = document.documentElement.clientHeight;
        }
    }
}
</script>