<?php

namespace App\Jobs;

use App\Models\Atendimento;
use App\Models\HistoricoAtendimento;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Imtigger\LaravelJobStatus\Trackable;

class RegistrarAvaliacao implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels, Trackable;

    public Atendimento|HistoricoAtendimento $atendimento;
    public string $comentario;
    public int $avaliacao;

    /**
     * Create a new job instance.
     */
    public function __construct(Atendimento|HistoricoAtendimento $atendimento, $comentario, $avaliacao)
    {
        $this->atendimento = $atendimento;
        $this->comentario = $comentario;
        $this->avaliacao = $avaliacao;
        $this->setInput(['status' => 'progress', 'atendimento' => $atendimento->id]);
        $this->prepareStatus();
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $this->atendimento->avaliacao = $this->avaliacao;
        $this->atendimento->comentario = $this->comentario;
        $this->atendimento->save();

        $this->setInput(['status' => 'finished']);
        $this->setOutput(['message' => 'Mensagem enviada para '. $this->atendimento->cooperado->nome . ', contato ' . $this->atendimento->cooperado->celular]);
    }
}
