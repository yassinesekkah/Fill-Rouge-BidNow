<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Auction;
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
}
