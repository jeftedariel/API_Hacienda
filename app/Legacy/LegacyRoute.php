<?php

namespace App\Legacy;

/**
 * Una entrada de la tabla de rutas de un módulo legacy: equivale a un
 * elemento del array que devolvía <modulo>_init().
 */
class LegacyRoute
{
    public const ACCESS_OPEN = 'open';           // users_openAccess

    public const ACCESS_NONE = 'none';           // users_noAccess

    public const ACCESS_USER = 'user';           // users_loggedIn

    public const ACCESS_COMPANY_USER = 'company'; // companny_users_loggedIn

    /**
     * @param  string  $r  Valor del parámetro r que activa esta ruta.
     * @param  \Closure(LegacyParams): mixed  $action  Handler; su retorno pasa por LegacyResponse::reply().
     * @param  string  $access  Una de las constantes ACCESS_*.
     * @param  array<int, array{key: string, def: string, req: bool}>  $params  Igual que el array 'params' legacy.
     */
    public function __construct(
        public readonly string $r,
        public readonly \Closure $action,
        public readonly string $access = self::ACCESS_OPEN,
        public readonly array $params = [],
    ) {}
}
