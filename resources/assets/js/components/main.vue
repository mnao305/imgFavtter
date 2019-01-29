<template>
  <div>
    <p>{{user.name}}さんのいいね一覧</p>
    <p id="configShowButton" v-on:click="configShowToggle">設定▽</p>
    <dir id="config" v-if="configShow">
      <label>動画を表示しない：
        <input type="checkbox" v-model="configTmp.video">
      </label>
      <br>
      <label>画像をサムネ形式で表示する：
        <input type="checkbox" v-model="configTmp.imgThumb">
      </label>
      <br>
      <button v-on:click="configSave">保存</button>
      <transition name="msg">
        <span id="savedMessage" v-if="savedMessage">保存しました</span>
      </transition>
      <br>設定は再読込後有効になります。
    </dir>
    <div v-masonry transition-duration="0.5s" item-selector=".item" class="mainContents">
      <div v-for="fav in favList" class="tweet" :key="fav.id_str">
        <div
          v-if="fav.extended_entities.media[0].type === 'video'"
          v-masonry-tile
          class="item video"
        >
          <!-- 動画だったら動画を表示 -->
          <a
            :href="getVideoURL(fav)"
            :data-fancybox="fav.id_str"
            :data-caption="fav.text + ` By ` + fav.user.name + `<br><a href='https://twitter.com/` + fav.user.screen_name + `/status/` + fav.id_str + `' target='_blank'>Twitterで元ツイートを見る→</a>`"
          >
            <img
              :src="fav.extended_entities.media[0].media_url_https + `:${config.imgThumb ? 'thumb' : 'small'}`"
            >
            <img src="/img/play.png" class="playBtn">
          </a>
        </div>
        <div v-else>
          <!-- 画像表示部 -->
          <div v-for="(media, index) in fav.extended_entities.media" :key="index" v-masonry-tile class="item" v-bind:class="config.imgThumb ? 'thumbImg' : 'smallImg'">
            <a
              :href="media.media_url_https"
              :data-fancybox="fav.id_str"
              :data-caption="fav.text + ` By ` + fav.user.name + `<br><a href='https://twitter.com/` + fav.user.screen_name + `/status/` + fav.id_str + `' target='_blank'>Twitterで元ツイートを見る→</a>`"
            >
              <img :src="media.media_url_https + `:${config.imgThumb ? 'thumb' : 'small'}`">
            </a>
          </div>
        </div>
      </div>
    </div>
    <div id="loading" v-show="this.loading">
      <img src="/img/load.gif">loading...
    </div>
  </div>
</template>

<script>
export default {
  created () {
    this.loginCheck()
    this.heightCheck()
    this.getFav()
  },
  data () {
    return {
      favList: [],
      user: null,
      tmpFavId: null,
      scrollY: 0,
      pageHeight: 0,
      windowHeight: 0,
      flag: false,
      loading: true,
      config:
        localStorage.getItem('config') != null
          ? JSON.parse(localStorage.getItem('config'))
          : { video: false, imgThumb: false },
      configTmp:
        localStorage.getItem('config') != null
          ? JSON.parse(localStorage.getItem('config'))
          : { video: false, imgThumb: false },
      savedMessage: false,
      configShow: false
    }
  },
  mounted () {
    window.addEventListener('scroll', this.handleScroll)
  },
  methods: {
    loginCheck () {
      // eslint-disable-next-line no-undef
      axios.get('api/twitterUserCheck').then(res => {
        console.log(res.data)
        this.user = res.data
        if (this.user === 'notLogin') {
          alert('ログインしていません。トップページからログインしなおしてください。')
          location.href = '/'
        }
      })
    },
    getFav () {
      // eslint-disable-next-line no-undef
      axios.get('api/fav').then(res => {
        // 取得した中で一番古いツイートのIDを取得
        this.tmpFavId = res.data[res.data.length - 1].id_str
        // 画像つきのみを抽出
        this.favList = res.data.filter(function (elem) {
          return typeof elem.extended_entities !== 'undefined'
        })

        if (this.config.video) {
          this.favList = this.videoDelete(this.favList)
        }
      }).catch(err => {
        console.log(err)
      }).then(() => {
        this.loading = false
      })
    },
    addFav () {
      this.loading = true
      let params = { id: this.tmpFavId }
      // eslint-disable-next-line no-undef
      axios.get('api/addfav', { params }).then(res => {
        // 取得した中で一番古いツイートのIDを取得
        this.tmpFavId = res.data[res.data.length - 1].id_str
        // 先頭に同じ内容が入ってしまう対応
        // 1つ目を消す
        res.data.shift()
        // 画像つきのみを抽出
        let tmpList = res.data.filter(function (elem) {
          return typeof elem.extended_entities !== 'undefined'
        })

        if (this.config.video) {
          tmpList = this.videoDelete(tmpList)
        }

        let flag
        if (tmpList.length <= 15) {
          flag = true
        }
        // 今ある配列の後ろに結合
        Array.prototype.push.apply(this.favList, tmpList)
        // 配列の長さを更新
        this.favList.splice(this.favList.length)
        // ページの高さを更新する
        this.heightCheck()
        if (flag === true) {
          this.addFav()
        }
      }).catch(err => {
        console.log(err)
      }).then(() => {
        this.loading = false
      })
    },
    handleScroll () {
      // もっとスマートに実装したい
      this.heightCheck()
      this.scrollY = window.scrollY
      if (this.flag === false && this.scrollY + this.windowHeight * 5 >= this.pageHeight) {
        // ページ最下部に来たらaddFav実行
        // 複数回実行されないようフラグを立てる
        this.flag = true
        this.addFav()
        // 1秒経ったら実行できるように
        setTimeout(() => {
          this.flag = false
        }, 1000)
      }
    },
    heightCheck () {
      this.pageHeight = document.body.scrollHeight
      this.windowHeight = document.documentElement.clientHeight
    },
    getVideoURL (tweet) {
      // アルゴリズム力つけろ
      var videoInfos = tweet.extended_entities.media[0].video_info.variants
      var videoTmp = videoInfos[0]
      videoInfos.forEach(element => {
        if (element.bitrate && (!videoTmp.bitrate || videoTmp.bitrate < element.bitrate)) {
          videoTmp = element
        }
      })
      return videoTmp.url
    },
    configSave () {
      localStorage.setItem('config', JSON.stringify(this.configTmp))
      this.savedMessage = true
      setTimeout(() => {
        this.savedMessage = false
      }, 1000)
    },
    videoDelete (list) {
      let notVideo = list.filter(function (elem) {
        return elem.extended_entities.media[0].type !== 'video'
      })
      return notVideo
    },
    configShowToggle () {
      this.configShow = !this.configShow
    }
  }
}
</script>
