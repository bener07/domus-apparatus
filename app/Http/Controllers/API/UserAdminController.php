<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Models\User;
use App\Classes\ApiResponseClass;
use App\Http\Resources\UserResource;
use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;

class UserAdminController extends Controller
{
    public function index() {
        $users = User::all();
        return ApiResponseClass::sendResponse(UserResource::collection($users), '', 200);
    }

    public function store(StoreUserRequest $request) {
        if($request->has('avatar')){
            $image = $request->file('avatar');
            $fileName = '/images/image_' . time() . '_' . uniqid() . '.' . $image->getClientOriginalExtension();
            $content = file_get_contents($image->getRealPath());
            auth()->user()->saveFile($fileName, $content);
        }
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'avatar' => $fileName,
        ]);
        return ApiResponseClass::sendResponse(new UserResource($user), 'User created successfully', 201);
    }

    public function show($id) {
        $user = User::find($id);
        if($user) {
            return ApiResponseClass::sendResponse(new UserResource($user), 'User retrieved successfully', 200);
        }
        return ApiResponseClass::sendResponse([], 'User not found', 404);
    }

    public function update(UpdateUserRequest $request, $id) {
        $user = User::find($id);
        if(!$user) {
            return ApiResponseClass::sendResponse([], 'User not found', 404);
        }
        if($request->has('roles')) {
            $user->addRole($request->roles);
        }
        if($request->has('images')) {
            $image = $request->file('avatar');
            $fileName = '/images/image_' . time() . '_' . uniqid() . '.' . $image->getClientOriginalExtension();
            $content = file_get_contents($image->getRealPath());
            auth()->user()->saveFile($fileName, $content);
        }
        $user->update($request->all());
        return ApiResponseClass::sendResponse(new UserResource($user), 'User updated successfully', 200);
    }

    public function destroy($id) {
        $user = User::find($id);
        if($user) {
            $user->delete();
            return ApiResponseClass::sendResponse([], 'User deleted successfully', 204);
        }
        return ApiResponseClass::sendResponse([], 'User not found', 404);
    }
}
