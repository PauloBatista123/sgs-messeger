<?php

namespace App\Http\Controllers;

use App\Http\Requests\ReceberAvaliacaoRequest;
use App\Http\Services\AtendimentoService;
use Exception;
use Illuminate\Http\Request;

class AtendimentoController extends Controller
{
    public function __construct(protected AtendimentoService $atendimentoService)
    {
    }

    public function enviarMensagem(Request $request)
    {
        try {

            $this->atendimentoService->enviarMensagem($request->get('atendimento'));

            return response("", 200);
        }catch(Exception $e){
            return response($e->getMessage(), 500);
        }
    }

    public function receberAvalicao(ReceberAvaliacaoRequest $request)
    {
        try {
            $this->atendimentoService->registrarAvaliacao($request->get('atendimento'), $request->get('avaliacao'), $request->get('comentario'));

            return response("", 200);

        }catch(Exception $e){
            return response($e->getMessage(), 500);
        }
    }
}
