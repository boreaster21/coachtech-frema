<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Comment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Mail\UserNotificationMail;

class AdminController extends Controller
{
    public function index()
    {
        $users = User::whereHas('role', function ($query) {
            $query->where('name', 'user');
        })->paginate(10);

        $comments = Comment::with('user')->paginate(10);

        return view('admin.dashboard', compact('users', 'comments'));
    }

    public function deleteUser($id)
    {
        $user = User::findOrFail($id);
        $user->delete();

        return redirect()->route('admin.dashboard')->with('success', 'ユーザーを削除しました。');
    }

    public function deleteComment($id)
    {
        try {
            $comment = Comment::findOrFail($id); 
            $comment->delete();

            return redirect()->route('admin.dashboard')->with('success', 'コメントを削除しました。');
        } catch (\Exception $e) {
            return redirect()->route('admin.dashboard')->with('error', 'コメント削除に失敗しました: ' . $e->getMessage());
        }
    }

    public function sendMail(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'subject' => 'required|string|max:255',
            'message' => 'required|string',
        ]);

        $user = User::findOrFail($request->user_id);

        try {
            Mail::to($user->email)->send(new UserNotificationMail($request->subject, $request->message));
            return redirect()->route('admin.dashboard')->with('success', 'メールを送信しました。');
        } catch (\Exception $e) {
            return redirect()
                ->route('admin.dashboard')
                ->with('error', 'メール送信に失敗しました: ' . $e->getMessage());
        }
    }
}

