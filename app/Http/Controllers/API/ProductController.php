<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Requests\Product\StoreProductRequest;
use App\Http\Requests\Product\UpdateProductRequest;
use App\Classes\ApiResponseClass;
use App\Http\Resources\ProductResource;
use App\Models\Product;
use App\Models\BaseProducts;
use Illuminate\Support\Facades\DB;


class ProductController extends Controller
{
    public function index(Request $request){
        $data = BaseProducts::all();

        return ApiResponseClass::sendResponse(ProductResource::collection($data), '',200);
    }
    
    public function create(){
        //
    }

    public function store(StoreProductRequest $request){
        DB::beginTransaction();
        try{
            $Product = [];
            DB::commit();
            return ApiResponseClass::sendResponse(new ProductResource($Product), 'Product Create Successful', 201);

        }catch(\Exception $ex){
            return ApiResponseClass::rollback($ex);
        }
    }

    public function show($id){
        $Product = [];

        if($Product){
            return ApiResponseClass::sendResponse(new ProductResource($Product), 'Product retrieved successfully', 200);
        }else {
            return ApiResponseClass::sendResponse([], 'Product not found', 404);
        }
    }

    public function edit($id){
        //
    }

    public function update(UpdateProductRequest $request, $id){
        DB::beginTransaction();
        try{
            $Product = [];

             DB::commit();
             return ApiResponseClass::sendResponse(ProductResource::make($Product), 'Product Update Successful', 200);

        }catch(\Exception $ex){
            return ApiResponseClass::rollback($ex);
        }
    }
    
    public function destroy($id){
        $this->ProductRepositoryInterface->delete($id);

        return ApiResponseClass::sendResponse([], 'Product Delete Successful', 204);
    }
}
