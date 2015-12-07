<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Task;

class TaskController extends Controller
{
    public function __construct()
    {
        // 全アクションに対して認証を要求する
        $this->middleware('auth');
    }

    /**
     * ユーザーの全タスクをリスト表示
     *
     * @param  Request  $request
     * @return Response
     */
    public function index(Request $request)
    {
        #$tasks = Task::where('user_id', $request->user()->id)->get();

        // ページネーション
        $tasks = Task::where('user_id', $request->user()->id)->paginate(3);

        return view('tasks.index', [
            'tasks' => $tasks,
        ]);
    }

    /**
     * 新タスク作成
     *
     * @param  Request  $request
     * @return Response
     */
    public function store(Request $request)
    {
        // バリデーションのルール
        $this->validate($request, [
                'name' => 'required|max:255',
            ]);

        // タスク登録
        $request->user()->tasks()->create([
                'name' => $request->name,
            ]);

        // タスク登録後のリダイレクト先
        return redirect('/tasks');
    }

    /**
     * 指定タスクの削除
     *
     * @param  Request  $request
     * @param  Task     $task  (AppServiceProviderのRoot Model Binding機能によりTaskインスタンスを注入)
     * @return Response
     */
    public function destroy(Request $request, Task $task)
    {
        // アクションに対する認可
        // 第1引数: 呼び出すポリシーメソッド
        // 第2引数: モデルのインスタンス
        $this->authorize('destroy', $task);

        // Eloquentのdeleteメソッド
        $task->delete();

        // 削除完了したら、リダイレクト
        return redirect('/tasks');
    }
}
