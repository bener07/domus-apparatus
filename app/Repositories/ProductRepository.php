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
        // featured_image upload
        $file = $data->file('featured_image');
        $featured_name = 'image_' . time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
        $content = file_get_contents($data->file('featured_image')->getRealPath());
        auth()->user()->saveFile($featured_name, $content);
        if($data->has('images')){
            $imageNames = [];
            foreach($data->file('images') as $image)  {
                $fileName = 'image_' . time() . '_' . uniqid() . '.' . $image->getClientOriginalExtension();
                array_push($imageNames, "/".auth()->user()->directory."/".$fileName);
                $content = file_get_contents($image->getRealPath());
                auth()->user()->saveFile($fileName, $content);
            }
        }
        $newProduct = auth()->user()->products()->create([
            'name' => $data->name,
            'details' => $data->details,
            'location' => $data->local,
            'featured_image' => "/".auth()->user()->directory."/".$featured_name,
            'price' => $data->price,
            'images' => $imageNames,
            'owner_id' => auth()->user()->id,
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
