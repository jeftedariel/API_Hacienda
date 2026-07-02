<?php

namespace App\Legacy\Modules;

use App\Legacy\LegacyModule;
use App\Legacy\LegacyParams;
use App\Legacy\LegacyResponse;
use App\Legacy\LegacyRoute;
use App\Services\Xml\Legacy\CurrentParams;
use App\Services\Xml\Legacy\LegacyReplyKill;

/**
 * Port de legacy/api/contrib/genXML: generación de los XML v4.4 de los 7
 * tipos de comprobante. Las tablas de parámetros vienen extraídas
 * mecánicamente del module.php original (data/genxml_routes.php) y la
 * lógica es el port verbatim de genXML.php
 * (app/Services/Xml/Legacy/genxml_functions.php).
 */
class GenXmlModule implements LegacyModule
{
    public function routes(): array
    {
        require_once app_path('Services/Xml/Legacy/genxml_functions.php');

        $routes = [];
        foreach (require __DIR__.'/data/genxml_routes.php' as $r => $spec) {
            $fn = 'App\\Services\\Xml\\Legacy\\'.$spec['action'];
            $routes[] = new LegacyRoute(
                r: $r,
                action: function (LegacyParams $p) use ($fn) {
                    try {
                        return CurrentParams::with($p, fn () => $fn());
                    } catch (LegacyReplyKill $e) {
                        return LegacyResponse::reply($e->getMessage(), true, $p->get('replyType', 'json'));
                    }
                },
                params: $spec['params'],
            );
        }

        return $routes;
    }
}
