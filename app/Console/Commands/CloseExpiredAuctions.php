<?php

namespace App\Console\Commands;

use App\Http\Controllers\Api\NotificationController;
use App\Models\Auction;
use Illuminate\Console\Command;

class CloseExpiredAuctions extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:close-expired-auctions';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        ///start auction
        Auction::where('status', 'pending')
            ->where('start_date', '<=', now())
            ->update(['status' => 'active']);

        ///close auction
        $auctions = Auction::where('status', 'active')
            ->where('end_date', '<=', now())
            ->get();

        foreach ($auctions as $auction) {

            $highestBid = $auction->bids()->latest('amount')->first();

            if ($highestBid && $highestBid->amount >= $auction->reserve_price) {

                $auction->status = 'sold';
                $auction->winner_id = $highestBid->user_id;

                NotificationController::createNotification(
                    $highestBid->user_id,
                    'won',
                    'You won the auction for ' . $auction->product->title,
                    $auction->id
                );
            } elseif ($highestBid) {

                $auction->status = 'awaiting_seller';
                $auction->winner_id = $highestBid->user_id;

                NotificationController::createNotification(
                    $highestBid->user_id,
                    'pending',
                    'You are the highest bidder for ' . $auction->product->title . ', waiting seller confirmation',
                    $auction->id
                );

            } else {

                $auction->status = 'ended';
            }

            $auction->save();
        }
    }
}
