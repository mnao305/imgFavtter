<template>
    <nav class="navbar navbar-default navbar-static-top" role="navigation">
        <!-- Brand and toggle get grouped for better mobile display -->
        <div class="navbar-header">
            <router-link class="navbar-brand" to="/">imgFavtter</router-link>
            <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-ex1-collapse">
                <span class="sr-only">Toggle navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
        </div>

        <!-- Collect the nav links, forms, and other content for toggling -->
        <div class="collapse navbar-collapse navbar-ex1-collapse">
            <ul class="nav navbar-nav navbar-right">
                <li><a href="https://twitter.com/intent/tweet?text=いいねしてきた画像を一覧表示!%20imgFavtter%0a&url=https://imgFavtter.mnao305.com" onClick="window.open(encodeURI(decodeURI(this.href)), 'tweetwindow', 'width=650, height=470, personalbar=0, toolbar=0, scrollbars=1, sizable=1'); return false;" rel="nofollow">ツイート</a></li>
                <li><a href="/login/twitter">Register・Login</a></li>
                <li class="dropdown">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown">その他<b class="caret"></b></a>
                    <ul class="dropdown-menu">
                        <li><router-link to="about">About</router-link></li>
                        <li><router-link to="userconfig">UserConfig</router-link></li>
                        <li><router-link to="logout">Logout</router-link></li>
                    </ul>
                </li>
            </ul>
        </div><!-- /.navbar-collapse -->
    </nav>
</template>
