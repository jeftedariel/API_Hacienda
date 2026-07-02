<?php

namespace App\Services\Hacienda;

use Illuminate\Support\Facades\Http;

/**
 * Consulta de estado de un comprobante en Hacienda. Port de
 * legacy/api/contrib/consultar/consultar.php::consutar().
 *
 * Contrato conservado: mensajes de error como strings con status ok, y la
 * respuesta de Hacienda devuelta con json_decode del body (sin headers).
 */
class StatusClient
{
    public function __construct(private readonly HaciendaEnvironment $env) {}

    public function consultar(string $clave, string $clientId, string $token): mixed
    {
        if ($clave === '') {
            return 'La clave no puede ser en blanco';
        }

        $url = $this->env->receptionUrl($clientId);
        if ($url === null) {
            return 'Ha ocurrido un error en el client_id.';
        }

        try {
            $response = Http::withHeaders([
                'Authorization' => 'Bearer '.$token,
                'Cache-Control' => 'no-cache',
                'Content-Type' => 'application/x-www-form-urlencoded',
            ])
                ->withOptions(['verify' => $this->env->verifySsl()])
                ->timeout(30)
                ->get($url.$clave);
        } catch (\Throwable $e) {
            // El branch de error del legacy referencia $apiTo sin definir
            // (notice suprimido => null).
            return [
                'Status' => 0,
                'to' => null,
                'text' => $e->getMessage(),
            ];
        }

        return json_decode($response->body());
    }
}
