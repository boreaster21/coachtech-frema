@extends('layouts.template')
@section('title', '管理画面')
@section('css')
<link rel="stylesheet" href="{{ asset('css/admin.css') }}">
@endsection
@section('content')
<div class="admin-container">
    <h1>管理画面</h1>

    @if (session('success'))
    <div class="alert alert-success">
        {{ session('success') }}
    </div>
    @endif
    @if (session('error'))
    <div class="alert alert-danger">
        {{ session('error') }}
    </div>
    @endif

    <h2>ユーザー一覧</h2>
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>名前</th>
                <th>メールアドレス</th>
                <th>操作</th>
            </tr>
        </thead>
        <tbody>
            @foreach($users as $user)
            <tr>
                <td data-label="ID">{{ $user->id }}</td>
                <td data-label="名前">{{ $user->name }}</td>
                <td data-label="メールアドレス">{{ $user->email }}</td>
                <td data-label="操作">
                    <button onclick="openMailModal({{ $user->id }}, '{{ addslashes($user->name) }}')" class="btn btn-primary">メール送信</button>
                    <form action="{{ route('admin.deleteUser', $user->id) }}" method="POST" style="display:inline;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" onclick="return confirm('このユーザーを削除しますか？')">削除</button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <h2>コメント一覧</h2>
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>投稿者</th>
                <th>コメント内容</th>
                <th>商品名</th>
                <th>操作</th>
            </tr>
        </thead>
        <tbody>
            @foreach($comments as $comment)
            <tr>
                <td data-label="ID">{{ $comment->id }}</td>
                <td data-label="投稿者">{{ $comment->user->name }}</td>
                <td data-label="コメント内容">{{ $comment->content }}</td>
                <td data-label="商品名">{{ $comment->product->name }}</td>
                <td data-label="操作">
                    <form action="{{ route('admin.deleteComment', $comment->id) }}" method="POST" style="display:inline;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" onclick="return confirm('このコメントを削除しますか？')">削除</button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div id="mailModal" class="modal">
        <div class="modal-content">
            <span class="close" onclick="closeMailModal()">&times;</span>
            <form action="{{ route('admin.sendMail') }}" method="POST">
                @csrf
                <input type="hidden" id="modal-user-id" name="user_id">
                <h2>メール送信</h2>
                <p>宛先: <span id="modal-user-name"></span></p>
                <div>
                    <label for="modal-subject">件名:</label>
                    <input type="text" id="modal-subject" name="subject" required>
                </div>
                <div>
                    <label for="modal-message">メッセージ:</label>
                    <textarea id="modal-message" name="message" rows="5" required></textarea>
                </div>
                <button type="submit">送信</button>
                <button type="button" onclick="closeMailModal()">キャンセル</button>
            </form>
        </div>
    </div>
</div>

<script>
    function openMailModal(userId, userName) {
        document.getElementById('modal-user-id').value = userId;
        document.getElementById('modal-user-name').innerText = decodeURIComponent(userName);
        document.getElementById('mailModal').style.display = 'block';
    }

    function closeMailModal() {
        document.getElementById('mailModal').style.display = 'none';
    }
</script>
@endsection