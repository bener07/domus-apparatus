<?php

namespace App\Http\Controllers\API\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Classroom;
use App\Classes\ApiResponseClass;

class ClassAdminController extends Controller
{
    public function index(){
        $classes = Classroom::all();
        return ApiResponseClass::sendResponse($classes, 200);
    }
}
