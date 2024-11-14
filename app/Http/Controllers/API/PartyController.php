<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Requests\StoreProductRequest;
use App\Http\Requests\UpdateProductRequest;
use App\Interfaces\ProductRepositoryInterface;
use App\Classes\ApiResponseClass;
use App\Http\Resources\ProductResource;
use Illuminate\Support\Facades\DB;


class ProductController extends Controller
{
    private ProductRepositoryInterface $ProductRepositoryInterface;

    public function __construct(ProductRepositoryInterface $ProductRepositoryInterface){
        $this->ProductRepositoryInterface = $ProductRepositoryInterface;
    }

    public function index(Request $request){
        $data = $this->ProductRepositoryInterface->index();

        return ApiResponseClass::sendResponse(ProductResource::collection($data), '',200);
    }
    
    public function create(){
        //
    }

    public function store(StoreProductRequest $request){
        DB::beginTransaction();
        try{
             $Product = $this->ProductRepositoryInterface->store($request);

             DB::commit();
             return ApiResponseClass::sendResponse(new ProductResource($Product), 'Product Create Successful', 201);

        }catch(\Exception $ex){
            return ApiResponseClass::rollback($ex);
        }
    }

    public function show($id){
        $Product = $this->ProductRepositoryInterface->getById($id);

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
        $details =[
            'name' => $request->name,
            'details' => $request->details
        ];
        DB::beginTransaction();
        try{
             $Product = $this->ProductRepositoryInterface->update($id, $details);

             DB::commit();
             return ApiResponseClass::sendResponse(new ProductResource($Product), 'Product Update Successful', 200);

        }catch(\Exception $ex){
            return ApiResponseClass::rollback($ex);
        }
    }
    
    public function destroy($id){
        $this->ProductRepositoryInterface->delete($id);

        return ApiResponseClass::sendResponse([], 'Product Delete Successful', 204);
    }
}
