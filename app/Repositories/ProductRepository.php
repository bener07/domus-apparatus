<?php

namespace App\Repositories;

use App\Interfaces\ProductRepositoryInterface;
use App\Models\Product;
use Illuminate\Support\Facades\Storage;


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

    public function update(array $data, $id){
        return Product::whereId($id)->update($data);
    }

    public function delete($id){
        Product::destroy($id);
    }
}
