<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Api\NotificationController as ApiNotificationController;
use App\Http\Controllers\Controller;
use App\Models\Auction;
use App\Models\Bid;
use Illuminate\Http\Request;
use App\Http\Controllers\NotificationController;

class BidController extends Controller
{
    public function store(Request $request, $auctionId)
    {
        $validated = $request->validate([
            'amount' => 'required|numeric|min:1',
        ]);

        $auction = Auction::findOrFail($auctionId);
        $user = auth()->user();

        // highst previous bid
        $previousHighest = Bid::where('auction_id', $auction->id)
            ->orderBy('amount', 'desc')
            ->first();

        // create bid
        $bid = $auction->placeBid($user, $validated['amount']);
        $bid->load('user');

        // notification
        if (
            $previousHighest &&
            $previousHighest->user_id !== $user->id &&
            $validated['amount'] > $previousHighest->amount
        ) {
            ApiNotificationController::createNotification(
                $previousHighest->user_id,
                'outbid',
                'Someone outbid you on ' . $auction->product->title,
                $auction->id
            );
        }

        return response()->json([
            'message' => 'Bid placed successfully',
            'bid' => $bid
        ], 201);
    }

    public function index(Auction $auction)
    {
        $bids = $auction->bids()
            ->with('user')
            ->latest()
            ->paginate(10);

        return response()->json($bids);
    }


    public function highestBid(Auction $auction)
    {
        $bid = $auction->bids()
            ->with('user')
            ->latest()
            ->first();

        return response()->json($bid);
    }

    public function myBids(Request $request)
    {
        $user = $request->user();

        $auctions = Auction::whereHas('bids', function ($q) use ($user) {
            $q->where('user_id', $user->id);
        })
            ->with(['product', 'bids' => function ($q) {
                $q->latest();
            }])
            ->get();

        return response()->json($auctions);
    }
}
