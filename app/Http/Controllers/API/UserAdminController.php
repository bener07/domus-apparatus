<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Models\User;
use App\Classes\ApiResponseClass;
use App\Http\Resources\UserResource;
use App\Http\Requests\Users\StoreUserRequest;
use App\Http\Requests\Users\UpdateUserRequest;

class UserAdminController extends Controller
{
    public function index(Request $request) {
        if ($request->ajax()) {
            $start = $request->get('start');
            $length = $request->get('length');
            $search = $request->get('search');
            $sortDirection = $request->get('orderDir');
            $orderByColumn = $request->get('orderColumn')+1;
            $query = User::query();

            if (!empty($search)) {
                $query->where(function($query) use ($search) {
                    $query->where('name', 'like', "%$search%")
                          ->orWhere('email', 'like', "%$search%");
                });
            }

            $totalData = $query->count();

            $users = $query->offset($start)
                           ->limit($length)
                           ->orderBy('id', $sortDirection)
                           ->get();

            $totalFiltered = $query->count();

            return ApiResponseClass::dataTables([
                "draw" => intval($request->input('draw')),
                "total" => $totalData,
                "filtered" => $totalFiltered,
                "data" => UserResource::collection($users)
            ]);
        }
        $users = User::all();
        return ApiResponseClass::dataTables(UserResource::collection($users), '', 200);
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
            'avatar' => $fileName ?? 'http://localhost/storage/images/avatar.png',
        ]);
        if($request->has('roles')){
            $user->syncRoles($request->roles);
        }
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
            $user->syncRoles($request->roles);
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
