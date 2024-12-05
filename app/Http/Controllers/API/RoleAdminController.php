<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Classes\ApiResponseClass;
use App\Http\Resources\RolesResource;
use App\Models\Roles;

class RoleAdminController extends Controller
{
    public function index(){
        return ApiResponseClass::make(new RolesResource(Roles::all()));
    }
}
