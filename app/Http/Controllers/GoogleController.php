<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

// 追加
use Laravel\Socialite\Contracts\Factory as Socialite;
use App\User;
use Auth;

class GoogleController extends Controller
{
    /**
     * @var Socialite
     */
    protected $socialite;

    public function __construct(Socialite $socialite)
    {
        $this->socialite = $socialite;
    }

    /**
     * Google+へリダイレクトする
     * */
    public function getLogin()
    {
        //ソーシャルログイン処理
        return $this->socialite->driver('google')->redirect();
    }

    /**
     * Google+側で認証後にリダイレクトされてくる
     * */
    public function getCallback()
    {
        // TODO: 認証エラー処理 (認証キャンセルされると、500エラーで返ってくる)

        // ユーザー情報を取得
        $userData = $this->socialite->driver('google')->user();

        // 情報取得
        $sns_id    = $userData->getId();	//ユーザID
        $sns_name  = $userData->getName();	//名前
        $sns_email = $userData->getEmail();	//メールアドレス

        // ユーザーが登録済かチェック
        $user = User::where('oauth_flg', "google")->where('oauth_id', $sns_id)->first();

        if(!$user) {
            //自分のuserテーブル登録されていないユーザなので新規ユーザ

            //ユーザ登録の処理
            User::create([
                'name'      => $sns_name,
                'email'     => $sns_email,
                'password'  => bcrypt('google%'.$sns_id),
                'oauth_flg' => 'google',
                'oauth_id'  => $sns_id,
            ]);

            //登録したユーザー情報を取得
            $user = User::where('oauth_flg', "google")->where('oauth_id', $sns_id)->first();
        }

        //ログイン
        Auth::login($user);

        // リダイレクト
        return redirect('/tasks');
    }
}
