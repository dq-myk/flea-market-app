<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\ChatRequest;
use App\Models\Message;
use App\Models\Transaction;

class MessageController extends Controller
{
    // チャットメッセージとプロフィール画像の送信
    public function send(ChatRequest $request, $transactionId)
    {
        $transaction = Transaction::with(['messages.sender'])->findOrFail($transactionId);

        // メッセージ保存
        if ($request->filled('message')) {
            Message::create([
                'transaction_id' => $transactionId,
                'sender_id' => Auth::id(),
                'message' => $request->'message',
            ]);
        }

        // ユーザー画像の保存（上書き）
        if ($request->hasFile('image')) {
            $user = Auth::user();
            $image = $request->file('image');
            $imagePath = $image->store('public/images');
            $user->image_path = str_replace('public/', 'storage/', $imagePath);
            $user->save();
        }

        return redirect()->back();
    }

    // 投稿メッセージの更新
    public function update(Request $request, $id)
    {
        $request->validate([
            'message' => 'required|string|max:1000',
        ]);

        $message = Message::findOrFail($id);

        if ($message->sender_id !== Auth::id()) {
            abort(403);
        }

        $message->message = $request->input('message');
        $message->save();

        return redirect()->back();
    }

    // 投稿メッセージに対する編集、削除のアクション処理
    public function action(Request $request)
    {
        $action = $request->input('action');
        $messageId = $request->input('message_id');

        if ($action === 'edit') {
            return back()->withInput(['edit_id' => $messageId]);
        }

        if ($action === 'delete') {
            $message = Message::findOrFail($messageId);

            if ($message->sender_id === Auth::id()) {
                $message->delete();
            }

            return back();
        }

        return back();
    }

}
