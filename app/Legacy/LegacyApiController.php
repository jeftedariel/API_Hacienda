<?php

namespace App\Legacy;

use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Recibe todas las llamadas al endpoint de compatibilidad /api.php y las
 * despacha con la semántica del front controller legacy (www/api.php).
 */
class LegacyApiController
{
    public function __construct(private readonly LegacyDispatcher $dispatcher)
    {
    }

    public function __invoke(Request $request): Response
    {
        try {
            $params = LegacyParams::fromRequest($request);
            $response = $this->dispatcher->dispatch($params);
        } catch (LegacyDieException $e) {
            $response = LegacyResponse::plainDie($e->getMessage());
        }

        // CORS abierto, como el legacy (solo en este endpoint de compat).
        $response->headers->add([
            'Access-Control-Allow-Origin' => '*',
            'Access-Control-Allow-Headers' => 'Cache-Control, Pragma, Origin, Authorization, Content-Type, X-Requested-With',
            'Access-Control-Allow-Methods' => 'GET, PUT, POST',
        ]);

        return $response;
    }
}
