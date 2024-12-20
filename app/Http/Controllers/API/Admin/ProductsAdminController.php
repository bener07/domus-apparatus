<?php

namespace App\Http\Controllers\API\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\BaseProducts;
use App\Classes\ApiResponseClass;
use App\Http\Resources\ProductsResource;
use App\Http\Requests\Products\StoreProductRequest;
use App\Http\Requests\Products\UpdateProductRequest;


class ProductsAdminController extends Controller
{
    public function index(Request $request){
        if ($request->ajax()) {
            $start = $request->get('start');
            $length = $request->get('length');
            $search = $request->get('search');
            $sortDirection = $request->get('orderDir');
            $orderByColumn = $request->get('orderColumn')+1;
            $query = BaseProducts::query();

            if (!empty($search)) {
                $query->where(function($query) use ($search) {
                    $query->where('name', 'like', "%$search%")
                          ->orWhere('details', 'like', "%$search%");
                });
            }

            $totalData = $query->count();

            $products = $query->offset($start)
                           ->limit($length)
                           ->orderBy('id', $sortDirection)
                           ->get();

            $totalFiltered = $query->count();

            return ApiResponseClass::dataTables([
                "draw" => intval($request->input('draw')),
                "total" => $totalData,
                "filtered" => $totalFiltered,
                "data" => ProductsResource::collection($products)
            ]);
        }
        $products = BaseProducts::all();
        return ApiResponseClass::sendResponse(ProductsResource::collection($products), '', 200);
    }

    public function store(StoreProductRequest $request){
        $product = BaseProducts::create($request->all());
        return ApiResponseClass::sendResponse(new ProductsResource($product), 'Produtos guardados com sucesso', 201);
    }

    public function show($id){
        $product = BaseProducts::find($id);
        if($product){
            return ApiResponseClass::sendResponse(new ProductsResource($product), '', 200);
        }
        return ApiResponseClass::sendResponse([], 'Produto não encontrado', 404);
    }

    public function update(UpdateProductRequest $request, $id){
        $product = BaseProducts::find($id);
        if(!$product){
            return ApiResponseClass::sendResponse([], 'Produto não encontrado', 404);
        }
        $product->update($request->all());
        return ApiResponseClass::sendResponse(new ProductsResource($product), 'Produto atualizado', 200);
    }

    public function destroy($id){
        $product = BaseProducts::find($id);
        if($product){
            $product->delete();
            return ApiResponseClass::sendResponse([], 'Product deleted successfully', 204);
        }
        return ApiResponseClass::sendResponse([], 'Product not found', 404);
    }

}
