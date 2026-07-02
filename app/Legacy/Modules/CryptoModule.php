<?php

namespace App\Legacy\Modules;

use App\Legacy\LegacyModule;
use App\Legacy\LegacyParams;
use App\Legacy\LegacyRoute;
use App\Services\LegacyCrypto;

/**
 * Port de legacy/api/modules/crypto. makeKey genera la clave AES para el
 * .env; encrypt/desencrypt estaban deshabilitados hacia fuera
 * (users_noAccess) pero su validación de parámetros corre antes del gate,
 * y ese comportamiento es contrato (golden 37-crypto-encrypt-denied).
 */
class CryptoModule implements LegacyModule
{
    public function routes(): array
    {
        return [
            new LegacyRoute(
                r: 'makeKey',
                action: fn (): string => base64_encode(openssl_random_pseudo_bytes(32)),
            ),
            new LegacyRoute(
                r: 'encrypt',
                access: LegacyRoute::ACCESS_NONE,
                action: fn (LegacyParams $p): string => LegacyCrypto::fromConfig()->encrypt((string) $p->get('textEncrypt', '')),
                params: [['key' => 'textEncrypt', 'def' => '', 'req' => true]],
            ),
            new LegacyRoute(
                r: 'desencrypt',
                access: LegacyRoute::ACCESS_NONE,
                action: fn (LegacyParams $p) => LegacyCrypto::fromConfig()->decrypt((string) $p->get('textDesEncrypt', '0')),
                params: [['key' => 'textDesEncrypt', 'def' => '0', 'req' => false]],
            ),
        ];
    }
}
