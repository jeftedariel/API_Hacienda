<?php

namespace App\Legacy\Modules;

use App\Legacy\LegacyModule;
use App\Legacy\LegacyParams;
use App\Legacy\LegacyRoute;
use App\Services\Hacienda\ReceptionClient;

/**
 * Port de legacy/api/contrib/send: envío de comprobantes a la recepción de
 * Hacienda (r=json FE/NC/ND, r=sendMensaje MR, r=sendTE tiquete).
 */
class SendModule implements LegacyModule
{
    private const COMMON_PARAMS = [
        ['key' => 'token', 'def' => '', 'req' => true],
        ['key' => 'clave', 'def' => '', 'req' => true],
        ['key' => 'fecha', 'def' => '', 'req' => true],
        ['key' => 'emi_tipoIdentificacion', 'def' => '', 'req' => true],
        ['key' => 'emi_numeroIdentificacion', 'def' => '', 'req' => true],
        ['key' => 'comprobanteXml', 'def' => '', 'req' => true],
        ['key' => 'callbackUrl', 'def' => '', 'req' => false],
        ['key' => 'client_id', 'def' => '', 'req' => true],
    ];

    public function __construct(private readonly ReceptionClient $reception)
    {
    }

    public function routes(): array
    {
        return [
            new LegacyRoute(
                r: 'json',
                action: function (LegacyParams $p): array {
                    $datos = [
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
                        'callbackUrl' => $p->get('callbackUrl'),
                    ];

                    if ($p->get('callbackUrl', '') === '') {
                        unset($datos['callbackUrl']);
                    }
                    if ($p->get('recp_tipoIdentificacion', '') === '' || $p->get('recp_numeroIdentificacion', '') === '') {
                        unset($datos['receptor']);
                    }

                    return $this->reception->send($datos, (string) $p->get('client_id'), (string) $p->get('token'));
                },
                params: array_merge(self::COMMON_PARAMS, [
                    ['key' => 'recp_tipoIdentificacion', 'def' => '', 'req' => false],
                    ['key' => 'recp_numeroIdentificacion', 'def' => '', 'req' => false],
                ]),
            ),
            new LegacyRoute(
                r: 'sendMensaje',
                action: function (LegacyParams $p): array {
                    $datos = [
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
                        'consecutivoReceptor' => str_pad((string) $p->get('consecutivoReceptor'), 20, '0', STR_PAD_LEFT),
                        'comprobanteXml' => $p->get('comprobanteXml'),
                    ];

                    return $this->reception->send($datos, (string) $p->get('client_id'), (string) $p->get('token'));
                },
                params: array_merge(self::COMMON_PARAMS, [
                    ['key' => 'recp_tipoIdentificacion', 'def' => '', 'req' => true],
                    ['key' => 'recp_numeroIdentificacion', 'def' => '', 'req' => true],
                    ['key' => 'consecutivoReceptor', 'def' => '', 'req' => true],
                ]),
            ),
            new LegacyRoute(
                r: 'sendTE',
                action: function (LegacyParams $p): array {
                    $datos = [
                        'clave' => $p->get('clave'),
                        'fecha' => $p->get('fecha'),
                        'emisor' => [
                            'tipoIdentificacion' => $p->get('emi_tipoIdentificacion'),
                            'numeroIdentificacion' => $p->get('emi_numeroIdentificacion'),
                        ],
                        'comprobanteXml' => $p->get('comprobanteXml'),
                    ];

                    return $this->reception->send($datos, (string) $p->get('client_id'), (string) $p->get('token'));
                },
                params: self::COMMON_PARAMS,
            ),
        ];
    }
}
