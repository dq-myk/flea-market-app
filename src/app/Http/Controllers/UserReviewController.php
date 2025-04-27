<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Transaction;
use App\Models\UserReview;

class UserReviewController extends Controller
{
    // 購入者への評価登録処理
    public function reviewComplete(Request $request, $id)
    {
        $transaction = Transaction::findOrFail($id);

        UserReview::create([
            'transaction_id' => $transaction->id,
            'reviewer_id' => auth()->id(),
            'reviewee_id' => $transaction->buyer_id,
            'rating' => $request->input('rating'),
        ]);

        $buyerReviewExists = UserReview::where('transaction_id', $transaction->id)
            ->where('reviewee_id', $transaction->buyer_id)
            ->exists();

        $sellerReviewExists = UserReview::where('transaction_id', $transaction->id)
            ->where('reviewee_id', $transaction->seller_id)
            ->exists();

        if ($buyerReviewExists && $sellerReviewExists) {
            $transaction->update([
                'status' => 'completed',
            ]);
        }

        return redirect('/');
    }
}
