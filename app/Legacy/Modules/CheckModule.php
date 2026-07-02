<?php

namespace App\Legacy\Modules;

use App\Legacy\LegacyModule;
use App\Legacy\LegacyParams;
use App\Legacy\LegacyRoute;
use App\Services\Xsd\XsdValidatorService;

/**
 * Reimplementación del módulo legacy `check` (validación contra XSD).
 *
 * DIVERGENCIA DOCUMENTADA: el original estaba roto (cargaba un fac.xml
 * hardcodeado contra el XSD 4.2, solo para FE, y volcaba warnings HTML).
 * Aquí valida el XML recibido (base64 en `xml`) contra el XSD v4.4 del
 * tipo indicado. El mensaje de tipo desconocido se conserva.
 */
class CheckModule implements LegacyModule
{
    public function __construct(private readonly XsdValidatorService $validator)
    {
    }

    public function routes(): array
    {
        return [
            new LegacyRoute(
                r: 'checkxml',
                action: function (LegacyParams $p): array|string {
                    $tipo = (string) $p->get('tipoDocumento');
                    if (! $this->validator->supports($tipo)) {
                        return 'No se encuentra tipo de documento';
                    }

                    $xml = base64_decode((string) $p->get('xml', ''), true);
                    if ($xml === false || $xml === '') {
                        return 'Falta el parametro xml (comprobante en base64)';
                    }

                    return $this->validator->validate($xml, $tipo, signed: false);
                },
                params: [
                    ['key' => 'tipoDocumento', 'def' => '', 'req' => true],
                ],
            ),
        ];
    }
}
