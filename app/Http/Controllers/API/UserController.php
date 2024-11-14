<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Resources\PartyResource;
use App\Classes\ApiResponseClass;
use App\Models\User;

class UserController extends Controller
{
    public function getUserParties(Request $request){
        $request->user()->parties();
        $user_parties = $request->user()->parties;
        return ApiResponseClass::sendResponse(PartyResource::collection($user_parties), '', 200);
    }

    public function getUserEvents(Request $request){
        return ApiResponseClass::sendResponse(PartyResource::collection($request->user()->events), '', 200);
    }

    public function addUserToParty(Request $request){
        $status = $request->user()->addParty($request->partyId);
        if($status)
            return ApiResponseClass::sendResponse([], 'Successfuly added to Party list', 200);
        else
            return ApiResponseClass::sendResponse([], 'You are already in this party list!', 409);
            
    }

    public function detachFromParty(Request $request){
        $request->user()->removeParty($request->partyId);
        return ApiResponseClass::sendResponse([], 'User removed from party', 200);
    }
}
