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
    public function index(Request $request){
        if ($request->ajax() && isset($request->start)) {
            $start = $request->get('start');
            $length = $request->get('length');
            $search = $request->get('search');
            $sortDirection = $request->get('orderDir');
            $orderByColumn = $request->get('orderColumn');

            $query = Department::query();

            if (!empty($search)) {
                $query->where(function($query) use ($search) {
                    $query->where('name', 'like', "%$search%")
                        ->orWhere('description', 'like', "%$search%");
                });
            }

            $totalData = $query->count();

            $columns = ['id', 'name', 'description', 'manager'];
            $orderByColumnName = $columns[$orderByColumn] ?? 'id';
            $query->orderBy($orderByColumnName, $sortDirection);

            $departments = $query->offset($start)
                                ->limit($length)
                                ->get();

            $totalFiltered = $query->count();

            return response()->json([
                "draw" => intval($request->input('draw')),
                "recordsTotal" => $totalData,
                "recordsFiltered" => $totalFiltered,
                "data" => DepartmentResource::collection($departments)
            ]);
        }
    
        // Non-AJAX request (fallback)
        $departments = Department::all();
        return ApiResponseClass::sendResponse(DepartmentResource::collection($departments), '', 200);
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
