<?php

namespace App\Legacy;

use Symfony\Component\HttpFoundation\Response;

/**
 * Réplica byte a byte de tools_reply() + tools_returnJson()
 * (legacy/api/core/tools.php). Este contrato es sagrado: los clientes
 * existentes dependen de cada detalle, incluidos los "incorrectos":
 *
 *  - Falta de parámetro requerido => HTTP 200 con status:error.
 *  - Códigos '-300'..'-304' => 400/401/440/403/409 con texto en español.
 *  - Arrays con clave 'Status' => error:500 / ok:200 / otro:400, y la
 *    respuesta se colapsa a $response['text'].
 *  - El prefijo "ERROR: " solo se antepone a strings.
 *  - json_encode con flags por defecto (unicode escapado, slashes escapados).
 */
class LegacyResponse
{
    public static function reply(mixed $response, bool $killMe = false, mixed $replyType = 'json'): Response
    {
        $status = 200;

        // switch ($response) { case ERROR_USERS_*: ... } — solo aplica a
        // escalares; réplica de la comparación laxa de PHP sobre esos strings.
        if (is_scalar($response)) {
            // Nota: PHP castea las claves numéricas del array a int, por eso
            // la comparación es sobre (string) $code.
            foreach (LegacyErrorCode::map() as $code => [$httpStatus, $text]) {
                if ((string) $response === (string) $code) {
                    $status = $httpStatus;
                    $response = $text;
                    $killMe = true;
                    break;
                }
            }
        }

        if (is_array($response) && isset($response['Status'])) {
            switch ($response['Status']) {
                case 'error':
                    $status = 500;
                    $killMe = true;
                    break;
                case 'ok':
                    $status = 200;
                    break;
                default:
                    $status = 400;
                    $killMe = true;
            }
            $response = $response['text'] ?? null;
        }

        if ($killMe) {
            if (is_string($response)) {
                $response = 'ERROR: '.$response;
            }
        } else {
            $status = 200;
        }

        if ($replyType === 'json' || $replyType === false) {
            $body = json_encode([
                'status' => $killMe ? 'error' : 'ok',
                'resp' => $response,
            ]);

            return new Response($body, $status, ['Content-Type' => 'application/json']);
        }

        // replyType != json: el legacy imprime la respuesta cruda.
        return new Response(
            is_scalar($response) ? (string) $response : json_encode($response),
            $status,
            ['Content-Type' => 'text/html; charset=UTF-8']
        );
    }

    /**
     * Los die()/exit de texto plano (JSON inválido, settings faltante, etc.).
     */
    public static function plainDie(string $message): Response
    {
        return new Response($message, 200, ['Content-Type' => 'text/html; charset=UTF-8']);
    }
}
