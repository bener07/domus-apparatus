<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Classes\ApiResponseClass;
use App\Http\Resources\ProductResource;
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
            $query = Product::query();

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
                "data" => ProductResource::collection($products)
            ]);
        }
        $products = Product::all();
        return ApiResponseClass::sendResponse(ProductResource::collection($products), '', 200);
    }

    public function store(StoreProductRequest $request){
        $product = Product::create($request->all());
        return ApiResponseClass::sendResponse(new ProductResource($product), 'Product created successfully', 201);
    }

    public function show($id){
        $product = Product::find($id);
        if($product){
            return ApiResponseClass::sendResponse(new ProductResource($product), '', 200);
        }
        return ApiResponseClass::sendResponse([], 'Product not found', 404);
    }

    public function update(UpdateProductRequest $request, $id){
        $product = Product::find($id);
        if(!$product){
            return ApiResponseClass::sendResponse([], 'Product not found', 404);
        }
        $product->update($request->all());
        return ApiResponseClass::sendResponse(new ProductResource($product), 'Product updated successfully', 200);
    }

    public function destroy($id){
        $product = Product::find($id);
        if($product){
            $product->delete();
            return ApiResponseClass::sendResponse([], 'Product deleted successfully', 204);
        }
        return ApiResponseClass::sendResponse([], 'Product not found', 404);
    }

}
