<?php

namespace App\Policies;

use Illuminate\Auth\Access\HandlesAuthorization;
use App\User;  # 追加
use App\Task;  # 追加

class TaskPolicy
{
    use HandlesAuthorization;

    /**
     * Create a new policy instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * 指定されたユーザーが指定されたタスクを削除できるか決定
     *
     * @param  User  $user
     * @param  Task  $task
     * @return bool
     */
    public function destroy(User $user, Task $task)
    {
        // ユーザーのIDがタスクのuser_idと一致するかを調べるだけ
        return $user->id === $task->user_id;
    }
}
