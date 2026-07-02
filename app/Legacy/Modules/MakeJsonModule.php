<?php

namespace App\Legacy\Modules;

use App\Legacy\LegacyModule;
use App\Legacy\LegacyParams;
use App\Legacy\LegacyRoute;

/**
 * Port de legacy/api/contrib/makeJson: arma el payload de recepción de
 * Hacienda (emisor/receptor/comprobanteXml).
 */
class MakeJsonModule implements LegacyModule
{
    public function routes(): array
    {
        return [
            new LegacyRoute(
                r: 'makeJson',
                action: fn (LegacyParams $p): array => [
                    'clave' => $p->get('clave'),
                    'fecha' => $p->get('fecha'),
                    'emisor' => [
                        'tipoIdentificacion' => $p->get('emi_tipoIdentificacion'),
                        'numeroIdentificacion' => $p->get('emi_numeroIdentificacion'),
                    ],
                    'receptor' => [
                        'tipoIdentificacion' => $p->get('recp_tipoIdentificacion'),
                        'numeroIdentificacion' => $p->get('recp_numeroIdentificacion'),
                    ],
                    'comprobanteXml' => $p->get('comprobanteXml'),
                ],
                params: [
                    ['key' => 'clave', 'def' => '', 'req' => true],
                    ['key' => 'fecha', 'def' => '', 'req' => true],
                    ['key' => 'emi_tipoIdentificacion', 'def' => '', 'req' => true],
                    ['key' => 'emi_numeroIdentificacion', 'def' => '', 'req' => false],
                    ['key' => 'recp_tipoIdentificacion', 'def' => '', 'req' => true],
                    ['key' => 'recp_numeroIdentificacion', 'def' => '', 'req' => true],
                    ['key' => 'comprobanteXml', 'def' => '', 'req' => true],
                ],
            ),
        ];
    }
}
