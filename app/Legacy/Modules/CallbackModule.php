<?php

namespace App\Legacy\Modules;

use App\Legacy\LegacyModule;
use App\Legacy\LegacyParams;
use App\Legacy\LegacyRoute;
use Illuminate\Support\Facades\Log;

/**
 * Port de legacy/api/contrib/callback: recibe el callback asíncrono de
 * Hacienda (clave, ind-estado, respuesta-xml). El legacy solo lo registraba
 * en su log y respondía 202 (como resp de un JSON con status ok).
 */
class CallbackModule implements LegacyModule
{
    public function routes(): array
    {
        return [
            new LegacyRoute(
                r: 'callback',
                action: function (LegacyParams $p): int {
                    $json = str_replace(
                        ['ind-estado', 'respuesta-xml'],
                        ['ind_estado', 'respuesta_xml'],
                        $p->rawContent
                    );
                    $data = json_decode($json);

                    Log::info('[hacienda-callback]', [
                        'clave' => $data->clave ?? null,
                        'ind_estado' => $data->ind_estado ?? null,
                        'fecha' => $data->fecha ?? null,
                        'respuesta_xml_bytes' => isset($data->respuesta_xml) ? strlen((string) $data->respuesta_xml) : 0,
                    ]);

                    return 202;
                },
            ),
        ];
    }
}
