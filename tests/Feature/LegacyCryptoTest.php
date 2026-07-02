<?php

use App\Services\LegacyCrypto;

/**
 * Verifica que LegacyCrypto sea compatible con el módulo crypto legacy:
 * un round-trip encrypt/decrypt y la extracción de bcrypt de un password
 * en el formato base64(AES(bcrypt)) que usaba users_hash().
 */
test('encrypt/decrypt es round-trip con el formato legacy', function () {
    $crypto = new LegacyCrypto('AuM0LcFbL7GfNrsjRhK0fq6Kiuqd3QK926PiCIw74jc=');

    $cipher = $crypto->encrypt('hola mundo');
    expect($crypto->decrypt($cipher))->toBe('hola mundo');
});

test('extractBcrypt recupera el hash bcrypt de un password legacy', function () {
    $key = 'AuM0LcFbL7GfNrsjRhK0fq6Kiuqd3QK926PiCIw74jc=';
    $crypto = new LegacyCrypto($key);

    // users_hash(): base64( crypto_encrypt( bcrypt(pwd) ) )
    $bcrypt = password_hash('secret123', PASSWORD_BCRYPT, ['cost' => 4]);
    $legacyStored = base64_encode($crypto->encrypt($bcrypt));

    $recovered = $crypto->extractBcrypt($legacyStored);
    expect($recovered)->toBe($bcrypt);
    expect(password_verify('secret123', $recovered))->toBeTrue();
});

test('decrypt devuelve false ante datos corruptos', function () {
    $crypto = new LegacyCrypto('AuM0LcFbL7GfNrsjRhK0fq6Kiuqd3QK926PiCIw74jc=');

    expect($crypto->decrypt('no-es-base64-valido::'))->toBeFalse();
});
