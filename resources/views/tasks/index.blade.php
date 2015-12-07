@extends('layouts.app')

@section('content')

<div class="container">
    <div class="col-sm-offset-2 col-sm-8">
        <div class="panel panel-default">
            <div class="panel-heading">
                New Task
            </div>

            <div class="panel-body">
                <!-- バリデーションエラーの表示 -->
                @include('common.errors')

                <!-- 新タスクフォーム -->
                <form action="/task" method="POST" class="form-horizontal">
                    {{ csrf_field() }}

                    <!-- タスク名 -->
                    <div class="form-group">
                        <label for="task-name" class="col-sm-3 control-label">Task</label>

                        <div class="col-sm-6">
                            <input type="text" name="name" id="task-name" class="form-control">
                        </div>
                    </div>

                    <!-- タスク追加ボタン -->
                    <div class="form-group">
                        <div class="col-sm-offset-3 col-sm-6">
                            <button type="submit" class="btn btn-default">
                                <i class="fa fa-plus"></i> Add Task
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <!-- 現在のタスク -->
        @if (count($tasks) > 0)
        <div class="panel panel-default">
            <div class="panel-heading">
                Current Tasks
            </div>

            <div class="panel-body">
                <table class="table table-striped task-table">
                    <!-- テーブルヘッダ -->
                    <thead>
                        <th>Task</th>
                        <th>&nbsp;</th>
                    </thead>

                    <!-- テーブルボディ -->
                    <tbody>
                    @foreach ($tasks as $task)
                    <tr>
                        <td class="table-text"><div>{{ $task->name }}</div></td>

                        <!-- タスク削除ボタン -->
                        <td>
                            <form action="/task/{{ $task->id }}" method="POST">
                                {{ csrf_field() }}
                                {{ method_field('DELETE') }}

                                <button type="submit" id="delete-task-{{ $task->id }}" class="btn btn-danger">
                                    <i class="fa fa-btn fa-trash"></i>Delete
                                </button>
                            </form>
                        </td>
                    </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        {{-- ページネーションのリンク --}}
        {!! $tasks->render() !!}
        @endif
    </div>
</div>

@endsection