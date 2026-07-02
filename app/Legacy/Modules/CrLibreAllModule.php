<?php

namespace App\Legacy\Modules;

use App\Legacy\LegacyModule;
use App\Legacy\LegacyRoute;

/**
 * Port de legacy/api/contrib/crlibreall ("todo en uno" FE/NC/ND).
 *
 * Las acciones del legacy eran STUBS sin implementar: allFE solo cargaba el
 * módulo clave (retorno null) y allNC/allND estaban vacías. La orquestación
 * real la hace el cliente encadenando clave->genXML->firmar->token->send.
 * Se conservan las tablas de parámetros porque la validación de requeridos
 * sí es contrato observable (golden 35-crlibreall-fe-stub).
 */
class CrLibreAllModule implements LegacyModule
{
    public function routes(): array
    {
        $routes = [];
        foreach (require __DIR__.'/data/crlibreall_routes.php' as $r => $spec) {
            $routes[] = new LegacyRoute(
                r: $r,
                action: fn () => null, // stub, igual que el legacy
                params: $spec['params'],
            );
        }

        return $routes;
    }
}
