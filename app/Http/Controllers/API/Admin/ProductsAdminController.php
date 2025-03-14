<?php

namespace App\Http\Controllers\API\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Classes\FileClass;
use App\Models\Product;
use App\Models\BaseProducts;
use App\Classes\ApiResponseClass;
use App\Http\Resources\ProductsResource;
use App\Http\Resources\Charts\ProductsResource as ChartsProductsResource;
use App\Http\Requests\Product\StoreProductRequest;
use App\Http\Requests\Product\UpdateProductRequest;


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
                "data" => ChartsProductsResource::collection($products)
            ]);
        }
        $products = BaseProducts::all();
        return ApiResponseClass::sendResponse(ProductsResource::collection($products), '', 200);
    }

    public function store(StoreProductRequest $request){
        $product = BaseProducts::create([
            'name' => $request->name,
            'tag' => $request->tag,
            'details' => $request->details,
            'images' => $request->images,
            'isbns' => $request->isbns,
            'featured_image' => $request->featured_image,
            'quantity' => sizeof($request->isbns),
            'total' => sizeof($request->isbns)
        ]);
        return ApiResponseClass::sendResponse($product, 'Produtos guardados com sucesso', 201);
    }

    public function show($id){
        $product = BaseProducts::find($id);
        if($product){
            return ApiResponseClass::sendResponse(new ProductsResource($product), '', 200);
        }
        return ApiResponseClass::sendResponse([], 'Produto não encontrado', 404);
    }

    public function update(Request $request, $id)
    {
        // Encontra o produto pelo ID  
        $product = BaseProducts::find($id);
        if (!$product) {
            return ApiResponseClass::sendResponse([], 'Produto não encontrado', 404);
        }

        // Atualiza apenas os campos que foram enviados  
        if ($request->has('name')) {
            $product->name = $request->name;
        }

        if ($request->has('details')) {
            $product->details = $request->details;
        }

        if ($request->has('quantity')) {
            $product->quantity = $request->quantity; // Se a quantidade for enviada, atualiza
        }

        if ($request->has('tag_id')) {
            if(!$product->hasTag($request->get('tag_id')))
                $product->tags()->attach($request->tag_id);
        }

        // Tratamento de imagem principal  
        if ($request->hasFile('featured_image')) {
            $featuredImage = $request->file('featured_image');
            $does_file_exist = FileClass::fileExists($featuredImage);
            if(!$does_file_exist){
                $path = '/storage/'.$featuredImage->store('products', 'public');
            }else{
                $path = $does_file_exist;
            }
            $images = $product->images;
            $images[0] = $path;
            $product->images = $images;
        }

        // Atualiza as imagens adicionais  
        if ($request->has('images')) {
            $images = [];
            foreach ($request->images as $image) {
                if (is_file($image)) {
                    $does_file_exist = FileClass::fileExists($image);
                    if($does_file_exist){
                        $path = '/storage/' . $image->store('products', 'public');
                    }else{
                        $path = $does_file_exist;
                    }
                    $images[] = $path;
                } else {
                    $images[] = $image; // Se já for uma URL, adiciona
                }
            }
            $product->images = $images; // Atualiza o array de imagens
        }

        // Atualiza os ISBNs  
        if ($request->has('isbns')) {
            // Get existing ISBNs from the database
            $existingIsbns = $product->products()->pluck('isbn')->toArray();
            $newIsbns = $request->isbns; // ISBNs from the request

            // Delete ISBNs that no longer exist in the request
            $isbnsToDelete = array_diff($existingIsbns, $newIsbns);
            $product->products()->whereIn('isbn', $isbnsToDelete)->delete();

            // Update or create the new ISBNs
            foreach ($newIsbns as $isbn) {
                $product->products()->updateOrCreate(
                    ['isbn' => $isbn], // Find by ISBN
                    ['name' => $request->name, 'details' => $request->details] // Update fields
                );
            }

            $product->total = sizeof($request->isbns);
            $product->quantity = sizeof($request->isbns);
        }

        // Salva as alterações  
        $product->save();

        return ApiResponseClass::sendResponse(new ChartsProductsResource($product), 'Produto atualizado', 200);
    }

    // public function update($id, Request $request){
        
    //     $product = BaseProducts::where('id',$id)->get()->first();
    //     if(!$product){
    //         return ApiResponseClass::sendResponse([], 'Produto não encontrado', 404);
    //     }
    //     $product->update([
    //         'name' => $request->name,
    //         'details' => $request->details,
    //         'quantity' => sizeof($request->isbns),
    //     ]);
    //     if ($request->has('featured_image')) {
    //         // Get the value; it could be a file or a string.
    //         $file = $request->get('featured_image');
            
    //         if (!is_string($file)) {
    //             // Assume it's a file (an instance of UploadedFile)
    //             $existingPath = FileClass::fileExists($file);
    //             if (!$existingPath) {
    //                 // Store file and create a public URL path.
    //                 $storedPath = $file->store('products', 'public');
    //                 $image = '/storage/' . $storedPath;
    //             } else {
    //                 $image = $existingPath;
    //             }
    //         } else {
    //             // If it's already a string, we assume it's the image path/URL.
    //             $image = $file;
    //         }
        
    //         // Update the first element in the images array.
    //         $images = $product->images; // Get current images (should be an array)
    //         $images[0] = $image;        // Replace the first element.
    //         $product->images = $images; // Update the product's images property.
    //         $product->save();
    //     }
        
    //     if($request->has('images')){
    //         $product->images = $request->images;
    //         $product->save();
    //     }
    //     if($request->has('isbns')){
    //         foreach($request->isbns as $isbn){
    //             $product->products()->updateOrCreate(['isbn' => $isbn], ['name' => $request->name, 'details' => $request->details]);
    //         }
    //     }
    //     $product->tags()->attach($request->tag);
    //     return ApiResponseClass::sendResponse(new ChartsProductsResource($product), 'Produto atualizado', 200);
    // }

    public function destroy($id){
        $product = BaseProducts::find($id);
        if($product){
            $product->delete();
            return ApiResponseClass::sendResponse([], 'Product deleted successfully', 204);
        }
        return ApiResponseClass::sendResponse([], 'Product not found', 404);
    }

}
