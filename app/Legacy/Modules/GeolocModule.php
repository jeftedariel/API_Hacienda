<?php

namespace App\Legacy\Modules;

use App\Legacy\LegacyModule;
use App\Legacy\LegacyParams;
use App\Legacy\LegacyRoute;

/**
 * Port de legacy/api/modules/geoloc (geolocalización por IP vía MaxMind).
 *
 * En el legacy la ruta pública `geoloc_get_by_ip` apuntaba a una función
 * inexistente (geoloc_locationGetByIp) => respondía ERROR_BAD_REQUEST (-1).
 * Las rutas de carga de datos eran CLI-only (users_access). El módulo no es
 * parte del flujo de facturación; se conserva el contrato observable (la
 * ruta pública rota) y las de carga quedan denegadas.
 */
class GeolocModule implements LegacyModule
{
    public function routes(): array
    {
        return [
            new LegacyRoute(
                r: 'geoloc_get_by_ip',
                action: fn (LegacyParams $p): int => -1, // ERROR_BAD_REQUEST, como el legacy
                params: [['key' => 'ip', 'def' => '', 'req' => true]],
            ),
            new LegacyRoute(
                r: 'geoloc_create_tables',
                access: LegacyRoute::ACCESS_NONE,
                action: fn (): int => -1,
            ),
            new LegacyRoute(
                r: 'geoloc_load_blocks',
                access: LegacyRoute::ACCESS_NONE,
                action: fn (): int => -1,
            ),
            new LegacyRoute(
                r: 'geoloc_load_locations',
                access: LegacyRoute::ACCESS_NONE,
                action: fn (): int => -1,
            ),
        ];
    }
}
