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

    
    public function store(Request $request, $id)
    {   
        $user = auth()->user();
        $product = Product::findOrFail($id);

        if($product->user_id !== $user->id){
            return response()->json([
                'message' => 'Unauthorized'
            ], 403);
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
}
