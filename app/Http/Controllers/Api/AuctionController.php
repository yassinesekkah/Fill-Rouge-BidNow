<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Auction;
use App\Models\Product;
use Illuminate\Http\Request;

class AuctionController extends Controller
{
    public function index()
    {
        $auctions = Auction::with('product')
            ->where('status', 'active')
            ->get();

        return response()->json($auctions);
    }


    public function show($id)
    {
        $auction = Auction::with(['product'])->findOrFail($id);

        $latestBids = $auction->bids()
            ->with('user:id,name')
            ->latest()
            ->take(5)
            ->get();

        return response()->json([
            'auction' => $auction,
            'remaining_time' => $auction->remainingTime(),
            'latest_bids' => $latestBids
        ]);
    }


    public function store(Request $request, $id)
    {
        $user = auth()->user();
        $product = Product::findOrFail($id);

        if ($product->user_id !== $user->id) {
            return response()->json([
                'message' => 'Unauthorized'
            ], 403);
        }

        if ($product->auction) {
            return response()->json([
                'message' => 'This product already has an auction'
            ], 400);
        }

        $validated = $request->validate([
            'starting_price' => 'required|numeric|min:1',
            'reserve_price' => 'required|numeric',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after:start_date'
        ]);

        $auction = Auction::create([
            'product_id' => $product->id,
            'starting_price' => $validated['starting_price'],
            'reserve_price' => $validated['reserve_price'],
            'start_date' => $validated['start_date'],
            'end_date' => $validated['end_date']
        ]);

        return response()->json($auction);
    }


    public function accept(Auction $auction)
    {
        $user = auth()->user();

        if ($auction->status !== 'awaiting_seller') {
            abort(403, 'Auction is not awaiting seller decision.');
        }

        if ($user->id !== $auction->product->user_id) {
            abort(403, 'Only the seller can accept the offer.');
        }

        $auction->status = 'sold';
        $auction->save();

        return response()->json([
            'message' => 'Offer accepted successfully.',
            'auction' => $auction
        ]);
    }


    public function reject(Auction $auction)
    {
        $user = auth()->user();

        if ($auction->status !== 'awaiting_seller') {
            abort(403, 'Auction is not awaiting seller decision.');
        }

        if ($user->id !== $auction->product->user_id) {
            abort(403, 'Only the seller can reject the offer.');
        }

        $auction->status = 'ended';
        $auction->save();

        return response()->json([
            'message' => 'Offer rejected successfully.',
            'auction' => $auction
        ]);
    }
}
