<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Requisicao;
use App\Models\AdminConfirmation;

class ConfirmationController extends Controller
{
    public function confirmation(Request $request){
        $confirmation = $request->confirmation;
        $confirmation->confirm();
        return redirect()->route('dashboard')->with('success', 'Requisição confirmada com sucesso.');
    }

    public function denial(Request $request){
        $confirmation = $request->confirmation;
        $confirmation->deny();
        return redirect()->route('dashboard')->with('success', 'Requisição negada com sucesso.');
    }
}
