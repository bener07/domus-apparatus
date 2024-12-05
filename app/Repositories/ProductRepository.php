<?php

namespace App\Repositories;

use App\Interfaces\ProductRepositoryInterface;
use App\Models\Product;
use App\Models\Tags;
use Illuminate\Support\Facades\Storage;
use App\Http\Requests\UpdateProductRequest;


class ProductRepository implements ProductRepositoryInterface
{
    /**
     * Create a new class instance.
     */
    public function index(){
        return Product::all();
    }

    public function getById($id){
        return Product::findOrFail($id);
    }

    public function store($data){
        if($data->has('images')){
            $imageNames = [];
            foreach($data->file('images') as $image)  {
                $fileName = 'image_' . time() . '_' . uniqid() . '.' . $image->getClientOriginalExtension();
                array_push($imageNames, "/images/".$fileName);
                $content = file_get_contents($image->getRealPath());
                auth()->user()->saveFile($fileName, $content);
            }
        }
        $newProduct = $data->user()->products()->create([
            'name' => $data->name,
            'details' => $data->details,
            'images' => $imageNames,
        ]);
        return $newProduct;
    }

    public function update($id, UpdateProductRequest $data){
        $product = Product::find($id);
        if($data->has('tags')){
            $tags = $data->tags;
            $tagIds = Tags::whereIn('name', $tags)
                ->pluck('id', 'name') // Retrieves existing tags
                ->union(
                collect($tags)
                    ->diff(Tags::pluck('name')) // Finds new tags not yet in the database
                    ->mapWithKeys(fn($tagName) => [Tags::create(['name' => $tagName])->name => Tags::latest('id')->first()->id]) // Creates new tags and maps them to IDs
                )
                ->values()
                ->toArray();
            $product->tags()->sync($tagIds);
        }
        if($product->update($data->all())){
            return $product;
        }
        return null;
    }

    public function delete($id){
        Product::destroy($id);
    }
}
