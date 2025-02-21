<?php

namespace App\Http\Controllers\API\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Discipline;
use App\Classes\ApiResponseClass;

class DisciplinesAdminController extends Controller
{
    public function index(){
        $disciplines = Discipline::all();
        return ApiResponseClass::sendResponse($disciplines, 200);
    }
}
