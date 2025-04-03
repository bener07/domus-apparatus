<?php

namespace App\Http\Controllers\API\Admin;

use Illuminate\Http\Request;
use App\Classes\ApiResponseClass;
use App\Http\Resources\ClassroomResource;
use App\Http\Requests\Classrooms\StoreClassroomRequest;
use App\Http\Requests\Classrooms\UpdateClassroomRequest;
use App\Models\Classroom;

class ClassAdminController extends Controller
{
    public function index(Request $request){
        if ($request->ajax() && isset($request->start)) {
            $start = $request->get('start');
            $length = $request->get('length');
            $search = $request->get('search');
            $sortDirection = $request->get('orderDir');
            $orderByColumn = $request->get('orderColumn');

            $query = Classroom::query();

            if (!empty($search)) {
                $query->where(function($query) use ($search) {
                    $query->where('name', 'like', "%$search%")
                        ->orWhere('description', 'like', "%$search%");
                });
            }

            $totalData = $query->count();

            $columns = ['id', 'name', 'department_id', 'capacity'];
            $orderByColumnName = $columns[$orderByColumn] ?? 'id';
            $query->orderBy($orderByColumnName, $sortDirection);

            $classrooms = $query->offset($start)
                                ->limit($length)
                                ->get();

            $totalFiltered = $query->count();

            return response()->json([
                "draw" => intval($request->input('draw')),
                "recordsTotal" => $totalData,
                "recordsFiltered" => $totalFiltered,
                "data" => ClassroomResource::collection($classrooms)
            ]);
        }

        // Non-AJAX request (fallback)
        $classrooms = Classroom::all();
        return ApiResponseClass::sendResponse(ClassroomResource::collection($classrooms), '', 200);
    }

    public function store(StoreClassroomRequest $request){
        $department = Classroom::create($request->all());
        $department->disciplines()->attach($request->discipline_ids);
        return ApiResponseClass::sendResponse(new ClassroomResource($department), 'Classroom Created Successfully', 201);
    }

    public function show($id){
        $department = Classroom::find($id);
        if($department){
            return ApiResponseClass::sendResponse(new ClassroomResource($department), '', 200);
        }
        return ApiResponseClass::sendResponse([], 'Classroom not found', 404);
    }

    public function update(UpdateClassroomRequest $request, $id){
        $department = Classroom::find($id);
        if(!$department){
            return ApiResponseClass::sendResponse([], 'Classroom not found', 404);
        }
        $department->update($request->all());
        if($request->has('discipline_ids')){
            $department->disciplines()->sync($request->discipline_ids);
        }
        return ApiResponseClass::sendResponse(new ClassroomResource($department), 'Classroom Updated Successfully', 200);
    }

    public function destroy($id){
        $department = Classroom::find($id);
        if($department){
            $department->delete();
            return ApiResponseClass::sendResponse([], 'Classroom deleted successfully', 204);
        }
        return ApiResponseClass::sendResponse([], 'Classroom not found', 404);
    }
}
