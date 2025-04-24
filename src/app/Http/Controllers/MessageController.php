<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\ChatRequest;
use App\Models\Message;

class MessageController extends Controller
{
    public function send(ChatRequest $request, $transactionId)
    {
        $chat = new Message();
        $chat->transaction_id = $transactionId;
        $chat->user_id = auth()->id(); // ログインユーザー
        $chat->message = $request->input('message');

        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('chat_images', 'public');
            $chat->image_path = $path;
        }

        $chat->save();

        return redirect()->back();
    }
}
