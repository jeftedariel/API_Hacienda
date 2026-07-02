<?php

namespace App\Legacy\Modules;

use App\Legacy\LegacyModule;
use App\Legacy\LegacyParams;
use App\Legacy\LegacyRoute;

/**
 * Port de legacy/api/contrib/makeQR: genera un QR PNG (base64) a partir de
 * `string`. Usa la misma librería phpqrcode vendorizada
 * (packages/legacy/phpqrcode) para conservar la salida binaria.
 */
class MakeQrModule implements LegacyModule
{
    public function routes(): array
    {
        return [
            new LegacyRoute(
                r: 'makeQR',
                action: fn (LegacyParams $p): string => \App\Services\Xml\Legacy\withLegacyErrorSuppression(function () use ($p): string {
                    if (! class_exists(\QRcode::class, false)) {
                        require_once base_path('packages/legacy/phpqrcode/qrlib.php');
                    }

                    ob_start();
                    \QRcode::png((string) $p->get('string'), null);
                    $imageString = base64_encode((string) ob_get_contents());
                    ob_end_clean();

                    return $imageString;
                }),
            ),
        ];
    }
}
