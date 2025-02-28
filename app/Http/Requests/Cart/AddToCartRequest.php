<?php

namespace App\Http\Requests\Cart;

use Illuminate\Foundation\Http\FormRequest;
use App\Http\Requests\ApiRequest;
use App\Models\BaseProducts;
use App\Models\Calendar;
use App\Exceptions\ProductException;
use App\Exceptions\UserException;
use App\Events\CartEvent;

class AddToCartRequest extends ApiRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        $request = $this;
        $user = $request->user();
        $cart = $user->cart;
        $base_product_id = $request->input('product_id') 
                           ??
                           $cart->items()
                           ->where(
                                'id', 
                                $request->input('id')
                            )->first()
                            ->base_product_id;
        $product = BaseProducts::where('id', $base_product_id)->get()->first();
        if(!$product->exists()){
            throw new UserException("O produto não existe", 404);
        }
        if($request->quantity <= 0){
            throw new UserException("Quantidade de equipamentos solicitada deve ser superior a 0", 400);
        }
        if($request->quantity > $product->quantity){
            event(new CartEvent($cart, ''));
            throw new UserException("Quantidade de equipamentos solicitada é superior ao disponível", 400);
        }
        // $productsOnDate = Calendar::productsRequestedOnDate($product->id, $request->start ?? $cart->start, $request->end ?? $cart->end)->sum('quantity');

        // //dd( $product->quantity - $productsOnDate + $request->quantity);
        // if ($productsOnDate < $request->quantity )
        //     throw new ProductException("Não há equipamentos suficientes para a data pedida", 400);
        $title = $product->name . " - ". $user->name;
        $existingCartItem = $cart->items()->where('title', $title)->get();
        $productQuantityOnDate = $product->quantity - Calendar::productsRequestedOnDate($product->id, $cart->start, $cart->end)->sum('quantity');
        if($productQuantityOnDate < $request->quantity){
            throw new ProductException("Não há equipamentos suficientes para a data pedida.", 400);
        }
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'product_id' => 'nullable|exists:base_products,id',
            'quantity' => 'required|integer|min:1',
            'id' => 'nullable|integer|exists:cart_items,id',
        ];
    }

    public function messages(): array
    {
        return [
            'product_id.exists' => 'O produto selecionado não é válido.',
            'quantity.required' => 'O campo de quantidade é obrigatório.',
            'quantity.integer' => 'A quantidade deve ser um número inteiro.',
            'quantity.min' => 'A quantidade deve ser pelo menos 1.',
            'start.required' => 'O campo de início é obrigatório.',
            'start.dateTime' => 'A data e hora de início devem ser válidas.',
            'end.required' => 'O campo de término é obrigatório.',
            'end.dateTime' => 'A data e hora de término devem ser válidas.',
            'end.after' => 'A data e hora de término devem ser posteriores à data e hora de início.',
            'id.exists' => 'Item do carrinho não encontrado'
        ];
    }
}
