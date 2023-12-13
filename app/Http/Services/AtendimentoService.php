<?php

namespace App\Http\Services;

use App\Jobs\EnviarMensagem;
use App\Jobs\RegistrarAvaliacao;
use App\Models\Atendimento;
use App\Models\HistoricoAtendimento;
use Error;

class AtendimentoService {

    public function enviarMensagem($atendimento)
    {
        $atendimento = $this->getAtendimentoByID($atendimento);

        if($atendimento){
            return EnviarMensagem::dispatch($atendimento);
        }

        throw new Error("Não existe atendimento com parametro informado");
    }

    public function getAtendimentoByID(int $id)
    {
        return Atendimento::find($id);
    }

    public function getHistoricoAtendimentoByID(int $id)
    {
        return HistoricoAtendimento::find($id);
    }

    public function registrarAvaliacao(int $atendimentoId, int $avaliacao, string $comentario)
    {
        $atendimento = $this->getAtendimentoByID($atendimentoId);

        if(!$atendimento){
            $atendimento = $this->getHistoricoAtendimentoByID($atendimentoId);
        }

        if($atendimento){
            return RegistrarAvaliacao::dispatch($atendimento, $comentario, $avaliacao);
        }

        throw new Error("Não existe atendimento com o ID informado", 400);
    }
}
