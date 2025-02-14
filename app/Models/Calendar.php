<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Exceptions\ProductException;
use App\Classes\Notifications;

class Calendar extends Model
{
    protected $fillable = [
        'product_id',
        'base_product_id',
        'requisicoes_id',
        'quantity',
        'status',
        'start',
        'end'
    ];
    protected $table = 'calendar';

    public function product(){
        return $this->belongsTo(Product::class, 'product_id');
    }

    public function baseProduct(){
        return $this->belongsTo(BaseProducts::class, 'base_product_id');
    }

    public function requisicao(){
        return $this->belongsTo(Requisicao::class, 'requisicoes_id');
    }

    public function updateQuantity($quantity){
        $this->quantity = $quantity;
        $this->save();
    }

    public function updateStart($start){
        $this->start = $start;
        $this->save();
    }

    public function updateEnd($end){
        $this->end = $end;
        $this->save();
    }

    public function updateStartAndEnd($start, $end){
        $this->updateStart($start);
        $this->updateEnd($end);
    }

    public function removeFromCalendar(){
        $this->delete();
    }

    public function addToCalendar($start, $end){
        $this->updateStartAndEnd($start, $end);
        $this->save();
    }

    public static function productsOnDate($base_product_id, $start, $end){
        return self::where('base_product_id', $base_product_id)
            ->whereBetween('start', [$start, $end])
            ->where('end', '>=', $start);
    }


    /**
     * @return Calendar Collection
     */
    public static function baseProductsQuantityOnDate($base_product, $start, $end) {
        $products_on_date = self::baseProductsOnDate($base_product, $start, $end);
    
        if ($products_on_date->get()->isEmpty()) {
            return $products_on_date; // Retorna uma coleção vazia corretamente
        }
    
        return $products_on_date
            ->selectRaw('requisicoes_id, SUM(quantity) as total_quantity, COUNT(DISTINCT product_id) as unique_products')
            ->groupBy('requisicoes_id')
            ->having('unique_products', '>', 1)
            ->get(); // Garante que os dados sejam retornados corretamente
    }


    public static function baseProductsOnDate($base_product_id, $start, $end) {
        // Primeiro, pegamos os menores IDs de cada requisicoes_id
        $subquery = self::where('base_product_id', $base_product_id)
            ->whereBetween('start', [$start, $end])
            ->where('end', '>=', $start)
            ->selectRaw('MIN(id) as id') // Pegamos apenas um ID por requisicoes_id
            ->groupBy('requisicoes_id');
    
        // Agora buscamos os registros completos com base nos IDs obtidos
        return self::whereIn('id', $subquery);
    }

    /**
     * $product_id -> id from table id
     * $start -> start date to search in the calendar
     * $end -> end date to search in the calendar
     * @return Collection of Calendar
     */
    public static function productsRequestedOnDate($product_id, $start, $end){
        $base_product_id = BaseProducts::find($product_id)->id;
        $products_on_date = self::productsOnDate($base_product_id, $start, $end)->sum('quantity');
        if($products_on_date > 0){
            return $products_on_date;
        }
        return auth()->user()->cart->items()->where('base_product_id', $product_id)->sum('quantity');
    }
}
