<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\ChatRequest;
use App\Models\Message;
use App\Models\Transaction;
use App\Models\UserReview;

class MessageController extends Controller
{
    //出品者用チャット画面表示
    public function seller(Request $request, Transaction $transaction)
    {
        $user = auth()->user();

        $transaction->load('buyer', 'item');

        if ($transaction->seller_id === $user->id) {
            $transaction->buyerMessages()
                ->where('sender_id', '!=', $user->id)
                ->where('read', false)
                ->update(['read' => true]);
        }

        $sellerId = $transaction->seller_id;

        $tradingItems = Transaction::where('status', 'in_progress')
        ->where('seller_id', $sellerId)
        ->where('id', '!=', $transaction->id)
        ->with(['item', 'messages' => function ($query) use ($transaction) {
            $query->where('sender_id', $transaction->buyer_id)
                ->latest('created_at');
        }])
        ->get()
        ->sortByDesc(function ($item) {
            return optional($item->messages->first())->created_at;
        });

        $alreadyReviewed = UserReview::where('transaction_id', $transaction->id)
            ->where('reviewer_id', $transaction->buyer_id)
            ->exists();

        $openModal = $alreadyReviewed;

        return view('chat_seller', compact('transaction', 'tradingItems', 'openModal', 'chatMessage'));
    }

    //購入者用チャット画面表示
    public function buyer(Transaction $transaction)
    {
        $user = auth()->user();

        $transaction->load('seller', 'item');

        if ($transaction->buyer_id === $user->id) {
            $transaction->sellerMessages()
                ->where('sender_id', '!=', $user->id)
                ->where('read', false)
                ->update(['read' => true]);
        }

        $buyerId = $transaction->buyer_id;

        $tradingItems = Transaction::where('status', 'in_progress')
        ->where('buyer_id', $buyerId)
        ->where('id', '!=', $transaction->id)
        ->with(['item', 'messages' => function ($query) use ($transaction) {
            $query->where('sender_id', $transaction->buyer_id)
                ->latest('created_at');
        }])
        ->get()
        ->sortByDesc(function ($item) {
            return optional($item->messages->first())->created_at;
        });

        $alreadyReviewed = UserReview::where('transaction_id', $transaction->id)
            ->where('reviewee_id', $transaction->buyer_id)
            ->exists();

        return view('chat_buyer', compact('transaction', 'tradingItems'));
    }

    // チャットメッセージとプロフィール画像の送信
    public function send(ChatRequest $request, $transactionId)
    {
        $transaction = Transaction::with(['messages.sender'])->findOrFail($transactionId);

        if ($request->filled('message')) {
            Message::create([
                'transaction_id' => $transactionId,
                'sender_id' => Auth::id(),
                'message' => $request->message,
            ]);
        }

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
