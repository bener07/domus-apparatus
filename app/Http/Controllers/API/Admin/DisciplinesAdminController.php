<?php

namespace App\Http\Controllers\API\Admin;

use Illuminate\Http\Request;
use App\Classes\ApiResponseClass;
use App\Http\Resources\DisciplineResource;
use App\Http\Requests\Disciplines\StoreDisciplineRequest;
use App\Http\Requests\Disciplines\UpdateDisciplineRequest;
use App\Models\Discipline;

class DisciplinesAdminController extends Controller
{
    public function index(Request $request){
        if ($request->ajax() && isset($request->start)) {
            $start = $request->get('start');
            $length = $request->get('length');
            $search = $request->get('search');
            $sortDirection = $request->get('orderDir');
            $orderByColumn = $request->get('orderColumn');

            $query = Discipline::query();

            if (!empty($search)) {
                $query->where(function($query) use ($search) {
                    $query->where('name', 'like', "%$search%")
                        ->orWhere('description', 'like', "%$search%");
                });
            }

            $totalData = $query->count();

            $columns = ['id', 'name', 'discipline_id', 'capacity'];
            $orderByColumnName = $columns[$orderByColumn] ?? 'id';
            $query->orderBy($orderByColumnName, $sortDirection);

            $Disciplines = $query->offset($start)
                                ->limit($length)
                                ->get();

            $totalFiltered = $query->count();

            return response()->json([
                "draw" => intval($request->input('draw')),
                "recordsTotal" => $totalData,
                "recordsFiltered" => $totalFiltered,
                "data" => DisciplineResource::collection($Disciplines)
            ]);
        }

        // Non-AJAX request (fallback)
        $Disciplines = Discipline::all();
        return ApiResponseClass::sendResponse(DisciplineResource::collection($Disciplines), '', 200);
    }

    public function store(StoreDisciplineRequest $request){
        $discipline = Discipline::create($request->all());
        return ApiResponseClass::sendResponse(new DisciplineResource($discipline), 'Discipline Created Successfully', 201);
    }

    public function show($id){
        $discipline = Discipline::find($id);
        if($discipline){
            return ApiResponseClass::sendResponse(new DisciplineResource($discipline), '', 200);
        }
        return ApiResponseClass::sendResponse([], 'Discipline not found', 404);
    }

    public function update(UpdateDisciplineRequest $request, $id){
        $discipline = Discipline::find($id);
        if(!$discipline){
            return ApiResponseClass::sendResponse([], 'Discipline not found', 404);
        }
        $discipline->update($request->all());
        if($request->has('discipline_ids')){
            $discipline->disciplines()->sync($request->discipline_ids);
        }
        return ApiResponseClass::sendResponse(new DisciplineResource($discipline), 'Discipline Updated Successfully', 200);
    }

    public function destroy($id){
        $discipline = Discipline::find($id);
        if($discipline){
            $discipline->delete();
            return ApiResponseClass::sendResponse([], 'Discipline deleted successfully', 204);
        }
        return ApiResponseClass::sendResponse([], 'Discipline not found', 404);
    }
}
