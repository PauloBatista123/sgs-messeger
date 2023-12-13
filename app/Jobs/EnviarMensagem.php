<?php

namespace App\Jobs;

use App\Http\Services\ApiService;
use App\Models\Atendimento;
use App\Models\Job;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Imtigger\LaravelJobStatus\Trackable;

class EnviarMensagem implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels, Trackable;

    public Atendimento $atendimento;
    /**
     * Create a new job instance.
     */
    public function __construct(Atendimento $atendimento)
    {
        $this->atendimento = $atendimento;
        $this->setInput(['status' => 'progress', 'atendimento' => $atendimento->id]);
        $this->prepareStatus();
    }

    /**
     * Execute the job.
     */
    public function handle(ApiService $apiService): void
    {
        $apiService->criarMensagem($this->atendimento->cooperado->celular, $this->atendimento->cooperado->nome, $this->atendimento->id);
        $this->setInput(['status' => 'finished']);
        $this->setOutput(['message' => 'Mensagem enviada para '. $this->atendimento->cooperado->nome . ', contato ' . $this->atendimento->cooperado->celular]);
    }

     /**
     * Handle a job failure.
     */
    // public function failed(Throwable $exception): void
    // {
    //     $this->setOutput(['error' => $exception->getMessage()]);
    //     $this->setInput(['status' => 'error']);
    // }
}
