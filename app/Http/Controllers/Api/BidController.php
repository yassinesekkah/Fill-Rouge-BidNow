<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Auction;
use App\Models\Bid;
use Illuminate\Http\Request;

class BidController extends Controller
{
    public function store(Request $request, $auctionId)
    {
        //validate input
        $validated = $request->validate([
            'amount' => 'required|numeric|min:1',
        ]);

        //get auction
        $auction = Auction::findOrFail($auctionId);

        //get user
        $user = auth()->user();

        //call auction model for place bid
        $bid = $auction->placeBid($user, $validated['amount']);
        $bid->load('user');

        $previousHighest = Bid::where('auction_id', $auction->id)
            ->latest()
            ->first();

        if ($previousHighest && $previousHighest->user_id !== auth()->id()) {
            NotificationController::createNotification(
                $previousHighest->user_id,
                'outbid',
                'Someone outbid you on ' . $auction->product->title
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
