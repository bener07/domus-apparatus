<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\SocialLinks;
use App\Models\User;

class testCommands extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:test-command {user} {platform?} {--create-social}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Just used to test new stuff';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $user = User::find($this->argument('user'));
        $user->socialLinks;
        if($this->option('create-social') != null){
            $social = $user->socialLinks()->create([
                'platform' => $this->argument('platform'),
                'user_id' => $user->id,
                'social_id' => '123456789',
            ]);
            $this->line($social);
        }
        $this->line($user);

        $startInterval = '2025-02-20'; // Start of the requested interval (x2)
        $endInterval = '2025-02-27';   // End of the requested interval (x3)
        $maxAvailableProducts = 5;     // Maximum available quantity per base product
        $baseProductId = 123;          // Base product ID to filter by

        // Get requisitions and their associated cart items for the base product
        $requisitions = Requisition::where(function ($query) use ($startInterval, $endInterval) {
            // Check for requisitions that fall within the time interval or overlap with it
            $query->whereBetween('start_date', [$startInterval, $endInterval])
                ->orWhereBetween('end_date', [$startInterval, $endInterval])
                ->orWhere(function ($q) use ($startInterval, $endInterval) {
                    // Check if requisition's start and end dates overlap with the interval
                    $q->where('start_date', '<=', $endInterval)
                        ->where('end_date', '>=', $startInterval);
                });
        })
            ->whereHas('cartItems', function ($query) use ($baseProductId) {
                // Filter cart items by base product
                $query->where('base_id', $baseProductId);
            })
            ->with(['cartItems' => function ($query) {
                // Get the cart items and the related products
                $query->select('id', 'base_id', 'quantity', 'cart_id');
            }])
            ->get();

        // Now, loop through the requisitions and check total quantities for the base product
        $available = true;
        $totalRequested = 0;

        // Loop through the requisitions and calculate the total number of products requested for the base product
        foreach ($requisitions as $requisition) {
            foreach ($requisition->cartItems as $cartItem) {
                $totalRequested += $cartItem->quantity;
            }
        }

        // If the total quantity exceeds the max available products, notify the user
        if ($totalRequested > $maxAvailableProducts) {
            $available = false;
        }

        if ($available) {
            return response()->json(['message' => 'All requisitions can be fulfilled.']);
        } else {
            return response()->json(['message' => 'Not enough available products for the requested period.'], 400);
        }

    }
}
