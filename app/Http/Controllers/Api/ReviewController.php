<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\Auction;
use App\Models\Review;
use Illuminate\Http\Request;

class ReviewController extends Controller
{
    public function store(Auction $auction, Request $request)
    {   
        ///check the status if sold
        if ($auction->status !== 'sold') {
            abort(403, 'Reviews are allowed only after the auction is sold.');
        }

        ///check if user can make review
        $user = auth()->user();
        $sellerId = $auction->product->user_id;
        $buyerId = $auction->winner_id;

        if (!in_array($user->id, [$sellerId, $buyerId])) {
            abort(403, 'Only participants of the auction can leave a review.');
        }

        ///check if already make a review
        $alreadyReviewed = Review::where('auction_id', $auction->id)
            ->where('user_id', $user->id)
            ->exists();

        if ($alreadyReviewed) {
            abort(403, 'You have already submitted a review for this auction.');
        }

        $reviewedUser = $user->id === $buyerId ? $sellerId: $buyerId;

        ///validate inputs
        $validated = $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'nullable|string|max:1000'
        ]);

        // return response()->json($reviewedUser);
        ///create review
        $review = Review::create([
            'user_id' => $user->id,
            'auction_id' => $auction->id,
            'reviewed_user_id' => $reviewedUser,
            'rating' => $validated['rating'],
            'comment' => $validated['comment']
        ]);


        return response()->json([
            'message' => 'Review submitted successfully.',
            'review' => $review
        ], 201);
    }
}
