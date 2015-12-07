<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

// 追加
use Laravel\Socialite\Contracts\Factory as Socialite;
use App\User;
use Auth;
use Mockery\CountValidator\Exception;

class FacebookController extends Controller
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
     * Facebook側へリダイレクトする
     * */
    public function getLogin()
    {
        //ソーシャルログイン処理
        return $this->socialite->driver('facebook')->redirect();
    }

    /**
     * Facebook側で認証後にリダイレクトされてくる
     * */
    public function getCallback()
    {
        // TODO: 認証エラー処理 (認証キャンセルされると、500エラーで返ってくる)

        // Facebookユーザー情報を取得
        $userData = $this->socialite->driver('facebook')->user();

        // Facebookからの情報取得
        $sns_id    = $userData->getId();	//facebookのユーザID
        $sns_name  = $userData->getName();	//facebook上の名前
        $sns_email = $userData->getEmail();	//メールアドレス

        // ユーザーが登録済かチェック
        $user = User::where('oauth_flg', "facebook")->where('oauth_id', $sns_id)->first();

        if(!$user) {
            //自分のuserテーブル登録されていないユーザなので新規ユーザ

            //ユーザ登録の処理
            User::create([
                'name'      => $sns_name,
                'email'     => $sns_email,
                'password'  => bcrypt('facebook%'.$sns_id),
                'oauth_flg' => 'facebook',
                'oauth_id'  => $sns_id,
            ]);

            //登録したユーザー情報を取得
            $user = User::where('oauth_flg', "facebook")->where('oauth_id', $sns_id)->first();
        }

        //ログイン
        Auth::login($user);

        // リダイレクト
        return redirect('/tasks');
    }
}
