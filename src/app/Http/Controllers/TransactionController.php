<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Mail\TransactionCompleted;
use App\Models\UserReview;
use App\Models\Transaction;

class TransactionController extends Controller
{
    // 出品者への評価登録処理
    public function complete(Request $request, Transaction $transaction)
    {
        $alreadyReviewed = UserReview::where('transaction_id', $transaction->id)->exists();

        if (!$alreadyReviewed) {
            UserReview::create([
                'transaction_id' => $transaction->id,
                'reviewer_id' => auth()->id(),
                'reviewee_id' => $transaction->seller_id,
                'rating' => $request->rating,
            ]);

            Mail::to($transaction->seller->email)->send(new TransactionCompleted($transaction));

        }

        return redirect('/');
    }
}
