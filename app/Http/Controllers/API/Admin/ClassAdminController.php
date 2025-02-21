<?php

namespace App\Http\Controllers\API\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ClassRoom;
use App\Classes\ApiResponseClass;

class ClassAdminController extends Controller
{
    public function index(){
        $classes = ClassRoom::all();
        return ApiResponseClass::sendResponse($classes, 200);
    }
}
