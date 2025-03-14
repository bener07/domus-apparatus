<?php

namespace App\Http\Controllers\API\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Classes\ApiResponseClass;
use App\Http\Resources\RolesResource;
use App\Models\Roles;
use App\Http\Requests\Roles\StoreRoleRequest;
use App\Http\Requests\Roles\UpdateRoleRequest;

class RoleAdminController extends Controller
{
    public function index(Request $request) {
        if ($request->ajax() && isset($request->start)) {
            // Get DataTables parameters
            $start = $request->get('start');
            $length = $request->get('length');
            $search = $request->get('search'); // DataTables sends search as an array
            $sortDirection = $request->get('orderDir'); // Sorting direction
            $orderByColumn = $request->get('orderColumn'); // Column index to sort by
    
            // Base query
            $query = Roles::query();
    
            // Apply search filter
            if (!empty($search)) {
                $query->where(function($query) use ($search) {
                    $query->where('name', 'like', "%$search%")
                          ->orWhere('description', 'like', "%$search%");
                });
            }
    
            // Get total count before pagination
            $totalData = $query->count();
    
            // Apply sorting
            $columns = ['id', 'name', 'description']; // Columns in the table
            $orderByColumnName = $columns[$orderByColumn] ?? 'id'; // Default to 'id' if column index is invalid
            $query->orderBy($orderByColumnName, $sortDirection);
    
            // Apply pagination
            $roles = $query->offset($start)
                           ->limit($length)
                           ->get();
    
            // Get filtered count (after search)
            $totalFiltered = $query->count();
    
            // Return DataTables response
            return ApiResponseClass::dataTables([
                "draw" => intval($request->input('draw')),
                "total" => $totalData,
                "filtered" => $totalFiltered,
                "data" => RolesResource::collection($roles)
            ], '', 200);
        }
    
        // Non-AJAX request (fallback)
        $roles = Roles::all();
        return ApiResponseClass::sendResponse(RolesResource::collection($roles), '', 200);
    }
    

    public function store(StoreRoleRequest $request){
        $role = Roles::create($request->all());
        return ApiResponseClass::sendResponse(new RolesResource($role), 'Role Created Successfully', 201);
    }

    public function destroy($id){
        $role = Roles::find($id);
        if($role){
            // $role->delete();
            $role->users()->detach();
            $role->delete();
            return ApiResponseClass::sendResponse([], 'Role deleted successfully', 204);
        }
        return ApiResponseClass::sendResponse([], 'Role not found', 404);
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
        $role->update($request->all());
        return ApiResponseClass::sendResponse(new RolesResource($role), 'Role Updated Successfully', 200);
    }
}
