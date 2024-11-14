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
        $parties = $tag->parties;
        return view('party.byTag', compact('parties', 'tag'));
    }
}
