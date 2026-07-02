<?php

namespace App\Legacy\Modules;

use App\Legacy\LegacyModule;
use App\Legacy\LegacyParams;
use App\Legacy\LegacyRoute;
use Illuminate\Support\Facades\Mail;

/**
 * Port de legacy/api/contrib/sendMail.
 *
 * El sendmail legacy era un PROTOTIPO roto: remitente/destinatario/PDF
 * hardcodeados e ignoraba `facturaPDF`. Aquí se reimplementa correctamente:
 * el correo va al destinatario indicado y adjunta los dos XML (con los
 * nombres legacy Comprobante_<clave>.xml y MH_<clave>.xml) y el PDF si viene.
 * Contrato de entrada conservado: xmlEnvia, xmlHacienda, clave, facturaPDF.
 */
class SendMailModule implements LegacyModule
{
    public function routes(): array
    {
        return [
            new LegacyRoute(
                r: 'sendmail',
                action: function (LegacyParams $p): string {
                    $clave = (string) $p->get('clave');
                    $to = (string) $p->get('destinatario', config('mail.from.address'));

                    try {
                        Mail::html('<p>Se adjuntan los comprobantes electrónicos.</p>', function ($m) use ($p, $clave, $to) {
                            $m->to($to)->subject('Documentos de Factura electronica #'.$clave);

                            if (($xml = base64_decode((string) $p->get('xmlEnvia'), true)) !== false && $xml !== '') {
                                $m->attachData($xml, 'Comprobante_'.$clave.'.xml', ['mime' => 'application/xml']);
                            }
                            if (($mh = base64_decode((string) $p->get('xmlHacienda'), true)) !== false && $mh !== '') {
                                $m->attachData($mh, 'MH_'.$clave.'.xml', ['mime' => 'application/xml']);
                            }
                            if (($pdf = base64_decode((string) $p->get('facturaPDF'), true)) !== false && $pdf !== '') {
                                $m->attachData($pdf, 'Factura_'.$clave.'.pdf', ['mime' => 'application/pdf']);
                            }
                        });
                    } catch (\Throwable $e) {
                        return 'Error: '.$e->getMessage();
                    }

                    // El legacy devolvía el string "test"; se conserva.
                    return 'test';
                },
                params: [
                    ['key' => 'xmlEnvia', 'def' => '', 'req' => true],
                    ['key' => 'facturaPDF', 'def' => '', 'req' => true],
                    ['key' => 'xmlHacienda', 'def' => '', 'req' => true],
                    ['key' => 'clave', 'def' => '', 'req' => true],
                ],
            ),
        ];
    }
}
