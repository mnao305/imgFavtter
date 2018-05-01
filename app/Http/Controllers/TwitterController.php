<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Abraham\TwitterOAuth\TwitterOAuth;

class TwitterController extends Controller
{
    public function login()
    {
        $connection = new TwitterOAuth(
            config('twitter.consumer_key'),
            config('twitter.consumer_secret')
        );

        $token = $connection->oauth('oauth/request_token', [
            'oauth_callback' => config('twitter.oauth_callback')
        ]);

        session([
            'oauth_token' => $token['oauth_token'],
            'oauth_token_secret' => $token['oauth_token_secret']
        ]);

        $url = $connection->url('oauth/authenticate', [
            'oauth_token' => $token['oauth_token']
        ]);

        return redirect($url);
    }

    public function callback(Request $request)
    {
        $oauthToken = session('oauth_token');
        $oauthTokenSecret = session('oauth_token_secret');

        // トークンが不正な値だったらエラー
        if ($request->has('oauth_token') && $oauthToken !== $request->oauth_token) {
            return redirect('/');
        }

        // リクエストトークンからアクセストークンを取得
        $connection = new TwitterOAuth(
            config('twitter.consumer_key'),
            config('twitter.consumer_secret'),
            $oauthToken,
            $oauthTokenSecret
        );
        $token = $connection->oauth('oauth/access_token', [
            'oauth_verifier' => $request->oauth_verifier,
        ]);

        // スクリーンネームを取得してメインページに飛ばす
        $twitter = new TwitterOAuth(
            config('twitter.consumer_key'),
            config('twitter.consumer_secret'),
            $token['oauth_token'],
            $token['oauth_token_secret']
        );
        session([
            'twitter' => $twitter
        ]);
        return redirect("/main");
    }

    public function getFav()
    {
        $twitter = session('twitter');
        try {
            // いいね履歴を入手
            return  $twitter->get("favorites/list", ["count" => "100"]);
            // return $twitter->get("favorites/list", ["count" => "100", "max_id" => $_POST['getFav']]);
        } catch (\Error $e) {
            // いいね取得に失敗したらメッセージを表示させる
            return "いいねの取得に失敗しました。\nしばらくしてからやり直してください";
        }
    }
}
