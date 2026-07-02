<?php

namespace App\Legacy\Modules;

use App\Legacy\LegacyModule;
use App\Legacy\LegacyParams;
use App\Legacy\LegacyRoute;
use App\Services\Clave\ClaveService;

/**
 * Port de legacy/api/contrib/clave (w=clave&r=clave).
 */
class ClaveModule implements LegacyModule
{
    public function __construct(private readonly ClaveService $clave)
    {
    }

    public function routes(): array
    {
        return [
            new LegacyRoute(
                r: 'clave',
                action: fn (LegacyParams $p): array|string => $this->clave->generate(
                    tipoDocumento: (string) $p->get('tipoDocumento'),
                    tipoCedula: (string) $p->get('tipoCedula'),
                    cedula: (string) $p->get('cedula'),
                    situacion: (string) $p->get('situacion'),
                    codigoPais: (string) $p->get('codigoPais', '506'),
                    consecutivo: (string) $p->get('consecutivo'),
                    codigoSeguridad: (string) $p->get('codigoSeguridad'),
                    sucursal: (string) $p->get('sucursal', '001'),
                    terminal: (string) $p->get('terminal', '00001'),
                ),
                params: [
                    ['key' => 'tipoDocumento', 'def' => '', 'req' => true],
                    ['key' => 'tipoCedula', 'def' => '', 'req' => true],
                    ['key' => 'cedula', 'def' => '', 'req' => true],
                    ['key' => 'codigoPais', 'def' => '', 'req' => false],
                    ['key' => 'consecutivo', 'def' => '', 'req' => true],
                    ['key' => 'situacion', 'def' => '', 'req' => true],
                    ['key' => 'terminal', 'def' => '', 'req' => false],
                    ['key' => 'sucursal', 'def' => '', 'req' => false],
                    ['key' => 'codigoSeguridad', 'def' => '', 'req' => true],
                ],
            ),
        ];
    }
}
