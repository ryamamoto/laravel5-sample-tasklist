<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\User;

class Task extends Model
{
    //マスアサインメント(複数代入)を許可するカラムを指定
    protected $fillable = ['name'];

    /*
     * タスクを所有するユーザーを取得
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
