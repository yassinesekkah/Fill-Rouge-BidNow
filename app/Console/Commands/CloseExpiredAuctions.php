<?php

namespace App\Console\Commands;

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
        $auctions = Auction::where('status', 'active')
            ->where('end_date', '<=', now())
            ->get();

        foreach ($auctions as $auction) {

            $highestBid = $auction->bids()->latest('amount')->first();

            if ($highestBid && $highestBid->amount >= $auction->reserve_price) {

                $auction->status = 'sold';
                $auction->winner_id = $highestBid->user_id;
            } elseif ($highestBid) {

                $auction->status = 'awaiting_seller';
                $auction->winner_id = $highestBid->user_id;
            } else {

                $auction->status = 'ended';
            }

            $auction->save();
        }
    }
}
