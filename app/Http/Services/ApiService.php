<?php

namespace App\Http\Services;

use Error;
use Exception;
use Illuminate\Http\Client\PendingRequest;
use Illuminate\Http\Client\RequestException;
use Illuminate\Queue\Jobs\Job;
use Illuminate\Support\Facades\Http;
use League\CommonMark\Environment\Environment;

class ApiService {

    public function access_token()
    {
        try {
            $response = Http::asForm()->withOptions([ 'verify' => false ])->withHeaders(['content-type' => 'application/json', 'accept' => '*/*'])
                                ->post('https://gateway.ubots.app/auth/oauth2/token', [
                                    "client_id" => env('UBOTS_CLIENT_ID'),
                                    "client_secret" => env('UBOTS_CLIENT_SECRET'),
                                    "grant_type" => "client_credentials",
                                    "scope" => "read"
                                ])->json();

            session()->put('access_token', $response['access_token']);
            session()->put('expires_in', $response['expires_in']);

            return $response['access_token'];
        } catch (\Throwable $th) {
            throw new Error('Erro ao gerar o access token:'. $th->getMessage());
        }
    }

    public function api(): PendingRequest
    {
        return Http::withToken(session()->get('access_token'))->retry(2, 0, function(Exception $exception, PendingRequest $request){
            if (! $exception instanceof RequestException || $exception->response->status() !== 401) {
                return false;
            }

            $request->withToken($this->access_token());

            return true;
        })->withOptions([
            'verify' => false
        ])->withHeaders([
            'Content-Type' => 'application/json',
            'Accept' => 'application/json',
        ])->bodyFormat('json');
    }

    public function criarMensagem(string $celular, string $nome, string $id)
    {
        $response = $this->api()->post('https://gateway.ubots.app/api/v1/messages', [
            "sender" => [
                "channel" => "WHATSAPP",
                "isBot" => true,
                "id" => env('BOT_ID'),
                "name" => "WhatsApp",
            ],
            "receiver" => [
                "channel" => "WHATSAPP",
                "id" => "55".$celular,
                "shouldCheckId" => true,
                "shouldAddToAgentPortfolio" => true,
            ],
            "messages" => [
                0 => [
                    "type" => "TEMPLATE",
                    "payload" => [
                        "id" => env("TEMPLATE_ID"),
                        "parameters" => [
                            "nome" => $nome,
                            "protocolo" => $id
                        ]
                    ]
                ],
            ]
        ]);

        if($response->status() != 200) {
            return [
                "error" => true,
                "message" => $response->body()
            ];
        }

        return [
            "error" => false,
            "message" => ""
        ];
    }
}
