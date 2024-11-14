<?php

namespace App\Repositories;

use App\Interfaces\PartyRepositoryInterface;
use App\Models\Party;
use Illuminate\Support\Facades\Storage;


class PartyRepository implements PartyRepositoryInterface
{
    /**
     * Create a new class instance.
     */
    public function index(){
        return Party::all();
    }

    public function getById($id){
        return Party::findOrFail($id);
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
        $newParty = auth()->user()->parties()->create([
            'name' => $data->name,
            'details' => $data->details,
            'location' => $data->local,
            'featured_image' => "/".auth()->user()->directory."/".$featured_name,
            'price' => $data->price,
            'images' => $imageNames,
            'owner_id' => auth()->user()->id,
        ]);
        return $newParty;
    }

    public function update(array $data, $id){
        return Party::whereId($id)->update($data);
    }

    public function delete($id){
        Party::destroy($id);
    }
}
