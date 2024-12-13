<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Requisicao;

class ConfirmationController extends Controller
{
    public function confirmation($requisicao_id, Request $request){
        $requisicao = Requisicao::find($requisicao_id);
        $confirmation = $requisicao->confirmacao()
            ->where('token', $request->token)
            ->where('admin_id', $requisicao->admin->id)
            ->first();
        if(!$confirmation){
            abort(404);
        }
        $confirmation->update(['status' => 'confirmado']);
        $requisicao->authorize($confirmation);
        return redirect()->route('dashboard')->with('success', 'Requisição confirmada com sucesso.');
    }
}
