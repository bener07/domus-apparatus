<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Tags;

class TagsController extends Controller
{
    public function index(){
        $tags = Tags::all();
        return view('tags.tags', compact('tags'));
    }

    public function show($id){
        $tag = Tags::find($id);
        $products = $tag->products;
        return view('Product.byTag', compact('products', 'tag'));
    }
}
