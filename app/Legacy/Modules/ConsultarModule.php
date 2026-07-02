<?php

namespace App\Legacy\Modules;

use App\Legacy\LegacyModule;
use App\Legacy\LegacyParams;
use App\Legacy\LegacyRoute;
use App\Services\Hacienda\StatusClient;

/**
 * Port de legacy/api/contrib/consultar: consulta de estado por clave.
 */
class ConsultarModule implements LegacyModule
{
    public function __construct(private readonly StatusClient $status)
    {
    }

    public function routes(): array
    {
        return [
            new LegacyRoute(
                r: 'consultarCom',
                action: fn (LegacyParams $p): mixed => $this->status->consultar(
                    clave: (string) $p->get('clave', ''),
                    clientId: (string) $p->get('client_id', ''),
                    token: (string) $p->get('token', ''),
                ),
                params: [
                    ['key' => 'clave', 'def' => '', 'req' => true],
                    ['key' => 'token', 'def' => '', 'req' => true],
                    ['key' => 'client_id', 'def' => '', 'req' => true],
                ],
            ),
        ];
    }
}
