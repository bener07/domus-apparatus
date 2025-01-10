<?php

namespace App\Http\Controllers\API\Admin;

use Illuminate\Http\Request;
use App\Classes\ApiResponseClass;
use App\Http\Resources\DepartmentResource;
use App\Http\Requests\Departments\StoreDepartmentRequest;
use App\Http\Requests\Departments\UpdateDepartmentRequest;
use App\Models\Department;

class DepartmentAdminController extends Controller
{
    public function index(){
        return ApiResponseClass::sendResponse(DepartmentResource::collection(Department::all()), '', 200);
    }

    public function store(StoreDepartmentRequest $request){
        $department = Department::create($request->all());
        return ApiResponseClass::sendResponse(new DepartmentResource($department), 'Department Created Successfully', 201);
    }

    public function show($id){
        $department = Department::find($id);
        if($department){
            return ApiResponseClass::sendResponse(new DepartmentResource($department), '', 200);
        }
        return ApiResponseClass::sendResponse([], 'Department not found', 404);
    }

    public function update(UpdateDepartmentRequest $request, $id){
        $department = Department::find($id);
        if(!$department){
            return ApiResponseClass::sendResponse([], 'Department not found', 404);
        }
        $department->update($request->all());
        return ApiResponseClass::sendResponse(new DepartmentResource($department), 'Department Updated Successfully', 200);
    }
    public function destroy($id){
        $department = Department::find($id);
        if($department){
            $department->delete();
            return ApiResponseClass::sendResponse([], 'Department deleted successfully', 204);
        }
        return ApiResponseClass::sendResponse([], 'Department not found', 404);
    }
}
