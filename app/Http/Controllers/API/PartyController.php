<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Requests\StorePartyRequest;
use App\Http\Requests\UpdatePartyRequest;
use App\Interfaces\PartyRepositoryInterface;
use App\Classes\ApiResponseClass;
use App\Http\Resources\PartyResource;
use Illuminate\Support\Facades\DB;


class PartyController extends Controller
{
    private PartyRepositoryInterface $partyRepositoryInterface;

    public function __construct(PartyRepositoryInterface $partyRepositoryInterface){
        $this->partyRepositoryInterface = $partyRepositoryInterface;
    }

    public function index(Request $request){
        $data = $this->partyRepositoryInterface->index();

        return ApiResponseClass::sendResponse(PartyResource::collection($data), '',200);
    }
    
    public function create(){
        //
    }

    public function store(StorePartyRequest $request){
        DB::beginTransaction();
        try{
             $party = $this->partyRepositoryInterface->store($request);

             DB::commit();
             return ApiResponseClass::sendResponse(new PartyResource($party), 'party Create Successful', 201);

        }catch(\Exception $ex){
            return ApiResponseClass::rollback($ex);
        }
    }

    public function show($id){
        $party = $this->partyRepositoryInterface->getById($id);

        if($party){
            return ApiResponseClass::sendResponse(new PartyResource($party), 'party retrieved successfully', 200);
        }else {
            return ApiResponseClass::sendResponse([], 'party not found', 404);
        }
    }

    public function edit($id){
        //
    }

    public function update(UpdatePartyRequest $request, $id){
        $details =[
            'name' => $request->name,
            'details' => $request->details
        ];
        DB::beginTransaction();
        try{
             $party = $this->partyRepositoryInterface->update($id, $details);

             DB::commit();
             return ApiResponseClass::sendResponse(new PartyResource($Party), 'party Update Successful', 200);

        }catch(\Exception $ex){
            return ApiResponseClass::rollback($ex);
        }
    }
    
    public function destroy($id){
        $this->partyRepositoryInterface->delete($id);

        return ApiResponseClass::sendResponse([], 'party Delete Successful', 204);
    }
}
