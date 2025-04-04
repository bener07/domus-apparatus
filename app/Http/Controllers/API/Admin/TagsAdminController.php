<?php

namespace App\Http\Controllers\API\Admin;

use App\Http\Requests\Tags\StoreTagsRequest;
use App\Http\Requests\Tags\UpdateTagsRequest;
use App\Models\Tags;
use App\Http\Resources\TagResource;
use App\Classes\ApiResponseClass;

class TagsAdminController extends Controller
{
    public function index(){
        return ApiResponseClass::sendResponse(TagResource::collection(Tags::all()), '', 200);
    }

    public function store(StoreTagsRequest $request){
        $tag = Tags::create($request->all());
        return ApiResponseClass::sendResponse(new TagResource($tag), 'Tag Created Successfully', 201);
    }

    public function show($id){
        $tag = Tags::find($id);
        if($tag){
            return ApiResponseClass::sendResponse(new TagResource($tag), '', 200);
        }
        return ApiResponseClass::sendResponse([], 'Tag not found', 404);
    }

    public function update(UpdateTagsRequest $request, $id){
        $tag = Tags::find($id);
        $tag->update($request->all());
        $tag->owner_id = $request->owner_id;
        return ApiResponseClass::sendResponse(new TagResource($tag), 'Tag updated successfully', 200);
    }

    public function destroy($id){
        $tag = Tags::find($id);
        if($tag){
            $tag->delete();
            return ApiResponseClass::sendResponse([], 'Tag deleted successfully', 204);
        }
        return ApiResponseClass::sendResponse([], 'Tag not found', 404);
    }
}
