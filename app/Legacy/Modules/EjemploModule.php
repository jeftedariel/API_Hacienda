<?php

namespace App\Legacy\Modules;

use App\Legacy\LegacyModule;
use App\Legacy\LegacyParams;
use App\Legacy\LegacyRoute;

/**
 * Port de legacy/api/contrib/ejemplo. Se conserva porque sirve como
 * verificación mínima de toda la tubería de compatibilidad.
 */
class EjemploModule implements LegacyModule
{
    public function routes(): array
    {
        return [
            new LegacyRoute(
                r: 'hola',
                action: fn (LegacyParams $p): string => 'hola :)',
            ),
            new LegacyRoute(
                r: 'un_usuario',
                action: fn (LegacyParams $p): string => $p->get('nombre').', '.$p->get('apellido'),
                params: [
                    ['key' => 'nombre', 'def' => '', 'req' => true],
                    ['key' => 'apellido', 'def' => '', 'req' => true],
                ],
            ),
        ];
    }
}
