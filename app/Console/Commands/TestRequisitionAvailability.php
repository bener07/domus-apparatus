<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Requisicao;
use App\Models\CartItem;
use App\Models\BaseProducts;
use App\Models\Calendar; // Assuming this is the pivot model

class TestRequisitionAvailability extends Command
{
    protected $signature = 'test:requisition-availability {startInterval} {endInterval} {baseProductId} {maxAvailableProducts}';
    
    protected $description = 'Test if requisitions and cart items can be fulfilled for a base product within a given time interval';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        // Retrieve the arguments passed in the command
        $startInterval = $this->argument('startInterval');
        $endInterval = $this->argument('endInterval');
        $baseProductId = $this->argument('baseProductId');
        $maxAvailableProducts = $this->argument('maxAvailableProducts');

        // Fetch the base product's max available quantity (as max stock per base product)
        $BaseProducts = BaseProducts::find($baseProductId);

        if (!$BaseProducts) {
            $this->error("Base product not found.");
            return;
        }

        $maxStock = $BaseProducts->max_quantity;

        // Query Requisicoes table for requisitions overlapping with the given date interval
        // $requisitions = Requisicao::whereHas('calendar', function ($query) use ($baseProductId, $startInterval, $endInterval){
        //     $query->where('base_product_id', $baseProductId)
        //           ->where('end', '>=', $startInterval)
        //           ->where('start', '<=', $endInterval);
        // })->get(); 

        $requisitions = Calendar::whereIn('id', function($query) use ($startInterval, $endInterval) {
            $query->selectRaw('MIN(id)')
                  ->from('calendar')
                  ->where(function($query) use ($startInterval, $endInterval) {
                      $query->where('end', '<=', '2025-02-10')
                            ->orWhere('start', '<=', '2025-03-10');
                  })
                  ->where(function($query) use ($startInterval, $endInterval) {
                      $query->where('end', '>=', $startInterval)
                            ->orWhere('start', '>=', $startInterval);
                  })
                  ->groupBy('base_product_id');
            })->get();

        $this->info(
            json_encode($requisitions, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES)
        );
        $totalRequested = 0;

        // Calculate the total quantity of the base product requested during the given time interval from requisitions
        // foreach ($requisitions as $requisition) {
        //     // Sum the quantity of base products requested in each requisition
        //     $totalRequested = $requisition->calendar->where('base_product_id', $baseProductId);
        // }

        // Query Cart Items table for cart items that overlap the given date interval
        $cartItems = CartItem::where('base_product_id', $baseProductId)
            ->whereHas('cart', function ($query) use ($startInterval, $endInterval) {
                $query->where(function ($q) use ($startInterval, $endInterval) {
                    $q->whereBetween('start', [$startInterval, $endInterval])
                      ->orWhereBetween('end', [$startInterval, $endInterval])
                      ->orWhere(function ($q) use ($startInterval, $endInterval) {
                          $q->where('start', '<=', $endInterval)
                            ->where('end', '>=', $startInterval);
                      });
                });
            })
            ->get();

        // Calculate the total quantity requested in cart items
        foreach ($cartItems as $cartItem) {
            $totalRequested += $cartItem->quantity;
        }

        // Check if the total requested quantity exceeds the available stock for the base product
        if ($totalRequested > $maxStock) {
            $this->error("Not enough available products for the requested period.");
        } else {
            $this->info("All requisitions and cart items can be fulfilled for the given time period.");
        }
    }
}
