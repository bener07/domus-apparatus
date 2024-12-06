<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Classes\ApiResponseClass;
use App\Http\Resources\RolesResource;
use App\Models\Roles;
use App\Http\Requests\Roles\StoreRoleRequest;

class RoleAdminController extends Controller
{
    public function index(){
        return ApiResponseClass::sendResponse(RolesResource::collection(Roles::all()), '', 200);
    }

    public function store(StoreRoleRequest $request){
        $role = Roles::create($request->all());
        return ApiResponseClass::sendResponse(new RolesResource($role), 'Role Created Successfully', 201);
    }

    public function show($id){
        $role = Roles::find($id);
        if($role){
            return ApiResponseClass::sendResponse(new RolesResource($role), '', 200);
        }
        return ApiResponseClass::sendResponse([], 'Role not found', 404);
    }

    public function update(UpdateRoleRequest $request){
        $role = Roles::find($request->id);
        if(!$role){
            return ApiResponseClass::sendResponse([], 'Role not found', 404);
        }
        $role->update($request->all());
        return ApiResponseClass::sendResponse(new RolesResource($role), 'Role Updated Successfully', 200);
    }
}
