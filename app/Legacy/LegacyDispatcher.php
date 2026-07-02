<?php

namespace App\Legacy;

use Illuminate\Contracts\Container\Container;
use Symfony\Component\HttpFoundation\Response;

/**
 * Réplica del despacho legacy: boot_itUp() -> boot_initThisPath() ->
 * tools_proccesPath() (legacy/api/core/boot.php y tools.php).
 *
 * Orden exacto por ruta (importa para la paridad):
 *   1. Localizar módulo por 'w' (default 'cala'); si no existe:
 *      "Module not found" con status ok.
 *   2. Localizar ruta por 'r'; si no existe: "Function not found" con status ok.
 *   3. Verificar parámetros requeridos (ANTES del control de acceso);
 *      el primero que falte corta con "Falta el parametro requerido: X".
 *      Los opcionales ausentes reciben su default.
 *   4. Control de acceso; si falla: '-303' => HTTP 403 "Acceso denegado".
 *   5. Ejecutar el handler; su retorno pasa por LegacyResponse::reply().
 */
class LegacyDispatcher
{
    /**
     * @param  array<string, class-string<LegacyModule>>  $modules  Mapa w => módulo.
     * @param  array<string, \Closure(LegacyParams): bool>  $accessCheckers  Mapa LegacyRoute::ACCESS_* => verificador.
     */
    public function __construct(
        private readonly Container $container,
        private readonly array $modules,
        private array $accessCheckers = [],
    ) {
        $this->accessCheckers += [
            LegacyRoute::ACCESS_OPEN => fn (): bool => true,
            LegacyRoute::ACCESS_NONE => fn (): bool => false,
            // Los verificadores con estado (sesiones) se registran en
            // LegacyServiceProvider cuando exista la capa de usuarios.
            LegacyRoute::ACCESS_USER => fn (): bool => false,
            LegacyRoute::ACCESS_COMPANY_USER => fn (): bool => false,
        ];
    }

    public function dispatch(LegacyParams $params): Response
    {
        $replyType = $params->get('replyType', 'json');

        $w = $params->get('w', 'cala');
        $moduleClass = $this->modules[(string) $w] ?? null;

        if ($moduleClass === null) {
            return LegacyResponse::reply('Module not found', false, $replyType);
        }

        /** @var LegacyModule $module */
        $module = $this->container->make($moduleClass);

        $r = $params->get('r');

        foreach ($module->routes() as $route) {
            // Legacy: $p['r'] == params_get('r') con comparación laxa;
            // r ausente (false) empareja solo con r=''.
            $matches = $r === false ? $route->r === '' : $route->r === (string) $r;
            if (! $matches) {
                continue;
            }

            // 1. Verificación de parámetros (antes del acceso, como el legacy).
            foreach ($route->params as $spec) {
                if ($params->get($spec['key'], '') === '') {
                    if ($spec['req']) {
                        return LegacyResponse::reply(
                            'Falta el parametro requerido: '.$spec['key'],
                            true,
                            $replyType
                        );
                    }
                    $params->set($spec['key'], $spec['def']);
                }
            }

            // 2. Control de acceso.
            $checker = $this->accessCheckers[$route->access];
            if ($checker($params) === false) {
                return LegacyResponse::reply(LegacyErrorCode::USERS_ACCESS_DENIED, false, $replyType);
            }

            // 3. Ejecutar la acción.
            $result = ($route->action)($params);

            if ($result instanceof Response) {
                return $result;
            }

            return LegacyResponse::reply($result, false, $replyType);
        }

        return LegacyResponse::reply('Function not found', false, $replyType);
    }
}
