<?php

namespace App\Services;

/**
 * Réplica exacta del módulo crypto legacy (AES-256-CBC).
 *
 * Peculiaridad heredada imprescindible: el legacy pasa el string base64 de
 * la clave DIRECTAMENTE como passphrase a openssl_encrypt (la variable
 * decodificada se calculaba pero nunca se usaba), y openssl trunca la
 * passphrase a 32 bytes. Hay que replicarlo tal cual o los sessionKeys y
 * passwords existentes no descifran.
 *
 * Formato: base64( base64(ciphertext) . '::' . iv_crudo ).
 */
class LegacyCrypto
{
    public function __construct(private readonly string $key)
    {
    }

    public static function fromConfig(): self
    {
        return new self((string) config('hacienda.legacy.crypto_key'));
    }

    public function encrypt(string $data): string
    {
        $iv = openssl_random_pseudo_bytes(openssl_cipher_iv_length('aes-256-cbc'));
        $encrypted = openssl_encrypt($data, 'aes-256-cbc', $this->key, 0, $iv);

        return base64_encode($encrypted.'::'.$iv);
    }

    /**
     * @return string|false false si el payload no descifra (clave errónea o
     *                      datos corruptos), igual que openssl_decrypt.
     */
    public function decrypt(string $data): string|false
    {
        $decoded = base64_decode($data);
        if ($decoded === false || ! str_contains($decoded, '::')) {
            return false;
        }

        [$encryptedData, $iv] = explode('::', $decoded, 2);

        return openssl_decrypt($encryptedData, 'aes-256-cbc', $this->key, 0, $iv);
    }

    /**
     * Deshace el hash de password legacy. users_hash() almacenaba
     * base64( crypto_encrypt( bcrypt ) ); esto devuelve el hash bcrypt puro
     * (compatible con Hash::check de Laravel) o false si no descifra.
     */
    public function extractBcrypt(string $legacyPwd): string|false
    {
        $inner = base64_decode($legacyPwd, true);
        if ($inner === false) {
            return false;
        }

        $hash = $this->decrypt($inner);
        if ($hash === false) {
            return false;
        }

        return str_starts_with($hash, '$2') ? $hash : false;
    }
}
